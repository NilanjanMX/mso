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
                        url: "{{ route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave') }}",
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
<!-- <div class="banner bannerForAll container" style="padding-bottom: 0; margin-bottom: 15px;">
        <div id="main-banner" class="owl-carousel owl-theme">
            <div class="item">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="py-4">Premium Calculators</h2>
                        <p>Lörem ipsum rutavdrag bespepp. Danyre gereras, sar rugbyförälder, ären. Multitirade pabel men spökgarn medan nåfus kreddig. Decill eus. Osm kromera, diadunade intrarade. 
                        </p>
                        <a href="" class="createtempbtn" style=" margin-right: 22px; "><button class="btn banner-btn mt-3">Sample Reports</button></a>
                        <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">How to Use</button></a>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/pcalculatorbanner.png" alt="" /></div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="banner styleApril">
        <div class="container">
        @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Lumpsum Investment Required For Target Monthly SWP</h2>
                </div>
            </div>
        </div>
    </div>
    @php
    //Annuity Period (Months) T9*12
    $annuity_period_months = $period*12;
    //Monthly Rate of Return (1)  (1+T11%)^(1/12)-1
    if($deferment=='yes')
    {

    if($include_inflation=='yes')
    {
        $av33=$deferment_period*12;
        $monthly_rate_of_return1 = pow((1+$distribution_phase_interest_rate_1/100),(1/12))-1; 
        $av35=pow((1+$expected_inflation_rate/100),(1/12))-1;
        $lfb1=$balance_required/(1+$monthly_rate_of_return1)**$annuity_period_months; 
        $lfa1=$initial_investment*((1-((1+$av35)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av35)); 
        $av39=$lfb1+$lfa1;
    }else{

        $av33=$deferment_period*12;
        $monthly_rate_of_return1 = pow((1+$distribution_phase_interest_rate_1/100),(1/12))-1; 
        $av35=pow((1+$accumulation_phase_interest_rate_1/100),(1/12))-1;
        $lfb1=$balance_required/(1+$monthly_rate_of_return1)**$annuity_period_months; 
        $lfa1=($initial_investment*(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months)))/$monthly_rate_of_return1; 
        $av39=$lfb1+$lfa1;

    }
        
        if (isset($distribution_phase_interest_rate_2)){

        if($include_inflation=='yes')
        {
            $av36=pow((1+$expected_inflation_rate/100),(1/12))-1; 
            $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
            $lfb2=$balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months; 
            //$lfa2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2; 
            $lfa2=$initial_investment*((1-((1+$av36)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av35));
            //echo $lfa2; die; 
            $av40=$lfb2+$lfa2; 
        }else{

            $av36=pow((1+$accumulation_phase_interest_rate_2/100),(1/12))-1; 
            $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
            $lfb2=$balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months; 
            $lfa2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2; 
            $av40=$lfb2+$lfa2; 
            
        }

        }
    }else{
        $monthly_rate_of_return1 = pow((1+$interest1/100),(1/12))-1 ;
    }



    if (isset($expected_inflation_rate)){
       $av36_inf=pow((1+$expected_inflation_rate/100),(1/12))-1;
    }

    
    //PV of Balance Required (1)  T13/(1+AV27)^(AV26)
    $pv_of_balance_required1 = $balance_required/(1+$monthly_rate_of_return1)**($annuity_period_months);
    //Balance Available for Annuity (1) T8-AV29
    $balance_available_for_annuity1 = $initial_investment-$pv_of_balance_required1;
    //Monthly SWP Amount (1) (AV27*AV31)/(1-(1+AV27)^(-AV26))
    if($deferment=='yes')
    {
        $av43=$av39-$pv_of_balance_required1; 

        if (isset($expected_inflation_rate)){
            if($distribution_phase_interest_rate_1==$expected_inflation_rate)
            {
                $monthly_annuity_amount1 = $av43*(1+$monthly_rate_of_return1);
            }else{
                $monthly_annuity_amount1 = $av43/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av36_inf));
            }
        }else{
            $monthly_annuity_amount1 = ($monthly_rate_of_return1*$av43)/(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months));
        }
        
    }else{
        if (isset($expected_inflation_rate)){
            if($interest1==$expected_inflation_rate)
            {
                $monthly_annuity_amount1 = $balance_available_for_annuity1*(1+$monthly_rate_of_return1);
            }else{
                $monthly_annuity_amount1 = $balance_available_for_annuity1/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av36_inf));
            }
            $mir=(1+($expected_inflation_rate/100))**(1/12)-1;
            $lumpsum_for_annuity1=$initial_investment*((1-((1+$mir)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$mir)); 
            $lumpsum_investment_required1=$pv_of_balance_required1+$lumpsum_for_annuity1; 
        }else{
            $monthly_annuity_amount1 = ($monthly_rate_of_return1*$balance_available_for_annuity1)/(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months));

            $lumpsum_for_annuity1=($initial_investment*(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months)))/$monthly_rate_of_return1;
            $lumpsum_investment_required1=$pv_of_balance_required1+$lumpsum_for_annuity1;
        }
    }
    if($deferment=='yes')
    {
        if (isset($distribution_phase_interest_rate_2)){
        //Monthly Rate of Return (2)  (1+T12%)^(1/12)-1
        $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
        //PV of Balance Required (2)  T13/(1+AV27)^(AV26)
        $pv_of_balance_required2 = $balance_required/(1+$monthly_rate_of_return2)**($annuity_period_months);
         //Balance Available for Annuity (2) T8-AV30
        $balance_available_for_annuity2 = $initial_investment-$pv_of_balance_required2;
        //Monthly SWP Amount (2) (AV28*AV32)/(1-(1+AV28)^(-AV26))

        $lfb2=$balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months; 
        $lfa2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2; 
        $av44=$lfb2+$lfa2;
        
        //$av44=$av40-$pv_of_balance_required2;

         if (isset($expected_inflation_rate)){
             if($distribution_phase_interest_rate_2==$expected_inflation_rate)
             {
                $monthly_annuity_amount2 = $av44*(1+$monthly_rate_of_return2);
             }else{
                $monthly_annuity_amount2 = $av44/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av36_inf));
             }
         }else{
            $monthly_annuity_amount2 = ($monthly_rate_of_return2*$av44)/(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months));
            
         }
    
        }
    }else{
        if (isset($interest2)){
        //Monthly Rate of Return (2)  (1+T12%)^(1/12)-1
        $monthly_rate_of_return2 = pow((1+$interest2/100),(1/12))-1 ;
        //PV of Balance Required (2)  T13/(1+AV27)^(AV26)
        $pv_of_balance_required2 = $balance_required/(1+$monthly_rate_of_return2)**($annuity_period_months);
         //Balance Available for Annuity (2) T8-AV30
        $balance_available_for_annuity2 = $initial_investment-$pv_of_balance_required2;
        //Monthly SWP Amount (2) (AV28*AV32)/(1-(1+AV28)^(-AV26))
            if (isset($expected_inflation_rate)){
                if($interest1==$expected_inflation_rate)
                {
                    $monthly_annuity_amount2 = $balance_available_for_annuity2*(1+$monthly_rate_of_return2);
                }else{
                    $monthly_annuity_amount2 = $balance_available_for_annuity2/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av36_inf));
                }
                $mir=(1+($expected_inflation_rate/100))**(1/12)-1;
                $lumpsum_for_annuity2=$initial_investment*((1-((1+$mir)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$mir)); 
                $lumpsum_investment_required2=$pv_of_balance_required2+$lumpsum_for_annuity2;
            }else{
                $monthly_annuity_amount2 = ($monthly_rate_of_return2*$balance_available_for_annuity2)/(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months));
                $lumpsum_for_annuity2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2;
                $lumpsum_investment_required2=$pv_of_balance_required2+$lumpsum_for_annuity2;
            }
        }
    }
    //echo $monthly_rate_of_return1; 
    //echo $monthly_rate_of_return2; 
    //echo $pv_of_balance_required1;
    //echo $pv_of_balance_required2;
    //echo $lumpsum_investment_required2; die;
    //echo $av43_new=$av39/(1+$av35)**$av33; die;
    //echo $av43_new2=$av44/(1+$av36)**$av33; die;
    

@endphp

    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    

                    

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <div class="outputTableHolder">
                        <h5 class="midheading">Monthly SWP Calculation @if(isset($clientname)) For {{$clientname?$clientname:''}} @endif</h5>
                        @php
                    if(isset($current_age) && $current_age>0)
                    {
                        $count_sec=$current_age;
                    }else{
                        $count_sec=0;
                    }

                    if(isset($deferment_period) && $deferment_period>0)
                    {
                        $dif_sec=$deferment_period;
                    }else{
                        $dif_sec=0;
                    }

                    @endphp
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                @if(isset($current_age) && $current_age>0)
                                <tr>
                                    <td>
                                        <strong>Current Age</strong>
                                    </td>
                                    <td colspan="2">
                                        {{$current_age}} Years
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        <strong>Target Monthly SWP</strong>
                                    </td>
                                    <td colspan="2">
                                        ₹ {{custome_money_format($initial_investment)}}
                                    </td>
                                </tr>
                                @if($deferment=='yes')
                                    <tr>
                                        <td>
                                            <strong>Deferment Period</strong>
                                        </td>
                                        <td colspan="2">
                                            {{$deferment_period?$deferment_period:0}} Years
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <strong>SWP Period</strong>
                                    </td>
                                    <td colspan="2">
                                        {{$period?$period:0}} Years
                                    </td>
                                </tr>
                                <!-- @if($deferment=='no')
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <strong>Expected Rate of Return</strong>
                                    </td>
                                    <td style="padding: 0;">
                                        @if(isset($interest2))
                                            <table width="100%">
                                                <tbody><tr>
                                                    <td>
                                                        Scenario 1
                                                    </td>
                                                    <td>
                                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Scenario 2
                                                    </td>
                                                    <td>
                                                        {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
        
                                        @else
                                            {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                        @endif
                                    </td>
                                </tr>
                                @endif -->
        
                                @if(isset($expected_inflation_rate) && $expected_inflation_rate>0)
                                    <tr>
                                        <td>
                                            <strong>Expected Inflation Rate</strong>
                                        </td>
                                        <td colspan="2">
                                           {{$expected_inflation_rate?number_format($expected_inflation_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    
                                @endif
                                
                                @if(isset($balance_required))
                                    <tr>
                                        <td>
                                            <strong>Balance Required</strong>
                                        </td>
                                        <td colspan="2">
                                            ₹ {{custome_money_format($balance_required)}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody></table>
                        </div>
        
                                @if(isset($is_note))
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
                                
                            @if($deferment=='yes')
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Accumulated Corpus Required @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif</h1>
                            
                            @if(isset($distribution_phase_interest_rate_2))
                            <div class="roundBorderHolder">
                             <table class="table table-bordered text-center">
                                <tbody>
                                
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>₹ {{custome_money_format($av39)}}</strong>
                                        </td>
                                        @if(isset($accumulation_phase_interest_rate_2))
                                        <td>
                                            <strong>₹ {{custome_money_format($av40)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                
                                </tbody></table>
                            </div>
                                @else
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">₹ {{custome_money_format($av39)}}</h2>
                                @endif
        
                            @endif
        
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Lumpsum Investment Required @if($deferment=='yes') @if(!isset($accumulation_phase_interest_rate_2)) @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif</h1>
        
                            @if($deferment=='no')
                            @if(isset($interest2))
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                        </td>
                                        <td>
                                            Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>₹ {{custome_money_format($lumpsum_investment_required1)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{custome_money_format($lumpsum_investment_required2)}} </strong>
                                        </td>
                                    </tr>
                                
                                </tbody></table>
                            </div>
                            @else
        
                            
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ {{custome_money_format($lumpsum_investment_required1)}}</h2>
                           
        
                            @endif
                            
                            @else
                            @if(isset($accumulation_phase_interest_rate_2))
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <td>
                                            Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if($include_inflation=='yes')
                                        @php
                                        //$av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                        $av35=(1+($accumulation_phase_interest_rate_1/100))**(1/12)-1;
                                        $av43_new=$av39/(1+$av35)**$av33;
                                        @endphp
                                        
                                        <td>
                                            <strong>₹ {{custome_money_format($av43_new)}}</strong>
                                        </td>
                                        @else
                                        @php
                                        $av43_new=$av39/(1+$av35)**$av33;
                                        @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av43_new)}}</strong>
                                        </td>
                                        @endif
                                        @if(isset($distribution_phase_interest_rate_2))
                                        @if($include_inflation=='yes')
                                        @php
                                        //$av43_new2=($monthly_annuity_amount2*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                        $av45=(1+($accumulation_phase_interest_rate_2/100))**(1/12)-1;
                                        $av43_new2=$av40/(1+$av45)**$av33;
                                        @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av43_new2)}}</strong>
                                        </td>
                                        @else
                                        @php
                                        $av43_new2=$av44/(1+$av36)**$av33;
                                        @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av43_new2)}} </strong>
                                        </td>
                                        @endif
                                        @endif
                                    </tr>
                                
                                </tbody></table>
                            </div>
                            @else
        
                            @if($include_inflation=='yes')
                            @php
                            //$av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                            $av35=(1+($accumulation_phase_interest_rate_1/100))**(1/12)-1;
                            $av43_new=$av39/(1+$av35)**$av33;
                            @endphp
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">₹ {{custome_money_format($av43_new)}}</h2>
                            @else
                            @php
                            $av43_new=$av39/(1+$av35)**$av33;
                            @endphp
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">₹ {{custome_money_format($av43_new)}}</h2>
                            @endif
        
                            @endif
                            @endif
        
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Total Withdrawal</h1>
        
                                
                            <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp
                                    {{custome_money_format($av50_new)}}
                                </h2>
                            <?php } elseif ($deferment == 'yes' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp
                                    {{custome_money_format($av50_new)}}
                                </h2>
                            <?php } elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    {{custome_money_format($initial_investment * $annuity_period_months)}}
                                </h2>
                            <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    {{custome_money_format($initial_investment * $annuity_period_months)}}
                                </h2>
                            <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp
                                    {{custome_money_format($av50_new)}}
                                </h2>
                            <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp
                                    {{custome_money_format($av50_new)}}
                                </h2>
                            <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    {{custome_money_format($initial_investment * $annuity_period_months)}}
                                </h2>
                            <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                                <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">
                                    ₹ 
                                    {{custome_money_format($initial_investment * $annuity_period_months)}}
                                </h2>
                            <?php } ?>
        
                            @if(isset($report) && $report=='detailed')
                            @if($deferment=='yes')
                           
                            <h5 class="text-center">Accumulation Phase <br> Projected Annual Investment Value</h5>
                            <div class="roundBorderHolder">
                             <table class="table table-bordered text-center" style="background: #fff;">
                                <tbody>
                                    <tr>
                                            <th>@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
        
                                            @if(isset($accumulation_phase_interest_rate_2))
                                            <th>Annual Investment</th>
                                            <th>Year End Value @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                            @endif
                                    </tr>
                                    @for($i=1;$i<=$deferment_period;$i++)
                                    @php
                                        $av43_new=$av39/(1+$av35)**$av33;
                                        $yev1=$av43_new*(1+$accumulation_phase_interest_rate_1/100)**$i;
                                        if(isset($accumulation_phase_interest_rate_2))
                                        {
                                            $yev2=$av43_new2*(1+$accumulation_phase_interest_rate_2/100)**$i;
                                        }
                                    @endphp
                                    <tr>
                                            <td>{{$count_sec+$i}} </td>
                                            <td> @if($i==1) ₹ {{custome_money_format($av43_new)}} @else -- @endif</td>
                                            <td>₹ {{custome_money_format($yev1)}}</td>
                                            @if(isset($accumulation_phase_interest_rate_2))
                                            <td> @if($i==1) ₹ {{custome_money_format($av43_new2)}} @else -- @endif</td>
                                            <td>₹ {{custome_money_format($yev2)}}</td>
                                            @endif
                                    </tr>
                                    @endfor
                                </tbody>
                             </table>
                            </div>
                             
                            <h5 class="text-center">Distribution Phase <br> Monthly Withdrawal & Projected Investment Value</h5>
                            @if(isset($expected_inflation_rate))
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                            </tr>
                            <tr>
                                <th>Monthly SWP</th>
                                <th>Year End Balance</th>
                                @if(isset($distribution_phase_interest_rate_2))
                                <th>Monthly SWP</th>
                                <th>Year End Balance</th>
                                @endif
                            </tr>
                                    @php
                                        $ff1=0;
                                        $ff2=0;
                                        $z=1;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
                                        if($i==1)
                                        {
                                            $as63=$av39;
                                        }else{
                                            $as63=$ax63;
                                        }
                                        
                                        $at63=$monthly_rate_of_return1;
                                        $au63=$as63+$as63*$at63;
                                        $av63=$av36_inf;
                                        if($i==1)
                                        {
                                            $aw63=$initial_investment;
                                        }else{
                                            $aw63=$aw63+$aw63*$av63;
                                        }
                                        $ax63=$au63-$aw63;
                                        $ff1+=$aw63;
        
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
                                            if($i==1)
                                            {
                                                $az63=$av40;
                                            }else{
                                                $az63=$be63;
                                            }
                                            
                                            $ba63=$monthly_rate_of_return2;
                                            $bb63=$az63+$az63*$ba63;
                                            $bc63=$av36_inf;
                                            if($i==1)
                                            {
                                                $bd63=$initial_investment;
                                            }else{
                                                $bd63=$bd63+$bd63*$bc63;
                                            }
                                            $be63=$bb63-$bd63;
                                            $ff2+=$bd63;
                                        }
                                        
                                        
                                        if($i%12==0)
                                        {
                                        $ff1r=$ff1/12;
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
                                            $ff2r=$ff2/12;
                                        }
                                        @endphp
                                            <tr>
                                            <td>{{$count_sec+$dif_sec+$z}}</td>
                                            <td>₹ {{custome_money_format($ff1r)}}</td>
                                            <td>₹ {{custome_money_format($ax63)}}</td>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <td>₹ {{custome_money_format($ff2r)}}</td>
                                            <td>₹ {{custome_money_format($be63)}}</td>
                                            @endif
                                            </tr>
                                        @php
                                        $z++;
                                        $ff1=0;
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
                                            $ff2=0;
                                        }
                                        }
                                        }
                                    @endphp
                            </tbody>
                            </table>
                            </div>
                            
                            @else
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                    @if(isset($distribution_phase_interest_rate_2))
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        </tr>
                                        <tr>
                                            <th>Monthly SWP</th>
                                            <th>Year End Balance</th>
                                            <th>Monthly SWP</th>
                                            <th>Year End Balance</th>
                                        </tr>
        
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                               //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                $year_end_value_senario_1 = ($av39*(1+$monthly_rate_of_return1)**($i*12)-($initial_investment*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                                //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                                $year_end_value_senario_2 = ($av40*(1+$monthly_rate_of_return2)**($i*12)-($initial_investment*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                            @endphp
                                            <tr>
                                                <td>{{$count_sec+$dif_sec+$i}}</td>
                                                <td>₹ {{custome_money_format($initial_investment)}}</td>
                                                <td>₹ {{custome_money_format($year_end_value_senario_1)}}</td>
                                                <td>₹ {{custome_money_format($initial_investment)}}</td>
                                                <td>₹ {{custome_money_format($year_end_value_senario_2)}}</td>
                                            </tr>
                                        @endfor
                                    @else
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                           
                                        </tr>
                                        <tr>
                                            <th>Monthly SWP</th>
                                            <th>Year End Balance</th>
                                            
                                        </tr>
        
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                               //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                $year_end_value_senario_1 = ($av39*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                                //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                               
                                            @endphp
                                            <tr>
                                                <td>{{$count_sec+$dif_sec+$i}}</td>
                                                <td>₹ {{custome_money_format($monthly_annuity_amount1)}}</td>
                                                <td>₹ {{custome_money_format($year_end_value_senario_1)}}</td>
                                                
                                            </tr>
                                        @endfor
                                        <!-- <tr>
                                            <th rowspan="2" style="vertical-align: middle;">Year</th>
                                            @if($deferment=='no')
                                            <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            @else
                                            <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>wwwwww Monthly SWP</th>
                                            <th>Year End Balance</th>
                                        </tr>
        
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                                //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                 $year_end_value_senario_1 = ($initial_investment*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
        
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>₹ {{custome_money_format($monthly_annuity_amount1)}}</td>
                                                <td>₹ {{custome_money_format($year_end_value_senario_1)}}</td>
                                            </tr>
                                        @endfor -->
                                    @endif
                                    </tbody>
                                </table>
                            </div>
        
                                
        
                                @endif
                                <!-- xxxxxxxxxxxx -->
                            @else
                                <h5 class="text-center">Monthly Withdrawal & Projected Investment Value</h5>
        
                            @if(isset($expected_inflation_rate))
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @if(isset($interest2))
                                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    @endif
                            </tr>
                            <tr>
                                <th>Monthly SWP</th>
                                <th>Year End Balance</th>
                                @if(isset($interest2))
                                <th>Monthly SWP</th>
                                <th>Year End Balance</th>
                                @endif
                            </tr>
                                    @php
                                        $ff1=0;
                                        $ff2=0;
                                        $z=1;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
                                        if($i==1)
                                        {
                                            $as63=$initial_investment;
                                        }else{
                                            $as63=$ax63;
                                        }
                                        
                                        $at63=$monthly_rate_of_return1;
                                        if($i==1)
                                        {
                                        $au63=$lumpsum_investment_required1+$lumpsum_investment_required1*$at63;
                                        }else{
                                        $au63=$as63+$as63*$at63;
                                        }
                                        $av63=$av36_inf;
                                        if($i==1)
                                        {
                                            $aw63=$initial_investment;
                                        }else{
                                            $aw63=$aw63+$aw63*$av63;
                                        }
                                        $ax63=$au63-$aw63;
                                        $ff1+=$aw63;
                                        if(isset($interest2))
                                        {
                                            if($i==1)
                                            {
                                                $az63=$initial_investment;
                                            }else{
                                                $az63=$be63;
                                            }
                                            
                                            $ba63=$monthly_rate_of_return2;
        
                                            //$bb63=$az63+$az63*$ba63;
                                            
                                            if($i==1)
                                            {
                                            $bb63=$lumpsum_investment_required2+$lumpsum_investment_required2*$ba63;
                                            }else{
                                            $bb63=$az63+$az63*$ba63;
                                            }
        
                                            $bc63=$av36_inf;
                                            if($i==1)
                                            {
                                                $bd63=$initial_investment;
                                            }else{
                                                $bd63=$bd63+$bd63*$bc63;
                                            }
                                            $be63=$bb63-$bd63;
                                            $ff2+=$bd63;
                                        }
                                        
                                        
                                        if($i%12==0)
                                        {
                                        $ff1r=$ff1/12;
                                        if(isset($interest2))
                                        {
                                            $ff2r=$ff2/12;
                                        }
                                        @endphp
        
                                            <tr>
                                            <td>{{$count_sec+$dif_sec+$z}}</td>
                                            @if($include_taxation=='yes' && $include_inflation=='no')
                                                <td>₹ {{custome_money_format($initial_investment)}}</td>
                                            @else
                                                <td>₹ {{custome_money_format($ff1r)}}</td>
        
                                            @endif
                                            
                                            <td>₹ {{custome_money_format($ax63)}}</td>
                                            @if(isset($interest2))
                                            @if($include_taxation=='yes' && $include_inflation=='no')
                                                <td>₹ {{custome_money_format($initial_investment)}}</td>
                                            @else
                                                <td>₹ {{custome_money_format($ff2r)}}</td>
                                            @endif
                                            <td>₹ {{custome_money_format($be63)}}</td>
                                            @endif
                                            </tr>
                                        @php
                                        $z++;
                                        $ff1=0;
                                        if(isset($interest2))
                                        {
                                            $ff2=0;
                                        }
                                        }
                                        }
                                    @endphp
                            </tbody>
                            </table>
                            </div>
                            @else
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                    @if(isset($interest2))
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        </tr>
                                        <tr>
                                            <th>Monthly SWP</th>
                                            <th>Year End Balance</th>
                                            <th>Monthly SWP</th>
                                            <th>Year End Balance</th>
                                        </tr>
        
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                               //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                $year_end_value_senario_1 = ($lumpsum_investment_required1*(1+$monthly_rate_of_return1)**($i*12)-($initial_investment*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                                //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                                $year_end_value_senario_2 = ($lumpsum_investment_required2*(1+$monthly_rate_of_return2)**($i*12)-($initial_investment*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                               
                                            @endphp
        
                                            <tr>
                                                <td>{{$count_sec+$dif_sec+$i}}</td>
                                                @if($include_taxation=='yes' && $include_inflation=='no')
                                                    <td>₹ {{custome_money_format($initial_investment)}}</td>
                                                @else
                                                    <td>₹ <!-- {{custome_money_format($lumpsum_investment_required1)}} -->{{custome_money_format($initial_investment)}}</td>
                                                @endif
                                                <td>₹ {{custome_money_format($year_end_value_senario_1)}}</td>
                                                @if($include_taxation=='yes' && $include_inflation=='no')
                                                    <td>₹ {{custome_money_format($initial_investment)}}</td>
                                                @else
                                                    <td>₹ <!-- {{custome_money_format($lumpsum_investment_required2)}} -->{{custome_money_format($initial_investment)}}</td>
                                                @endif
                                                <td>₹ {{custome_money_format($year_end_value_senario_2)}}</td>
                                            </tr>
                                        @endfor
                                    @else
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            @if($deferment=='no')
                                            <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            @else
                                            <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Monthly SWP</th>
                                            <th>Year End Balance</th>
                                        </tr>
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                                //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                 $year_end_value_senario_1 = ($lumpsum_investment_required1*(1+$monthly_rate_of_return1)**($i*12)-($initial_investment*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
        
                                            @endphp
                                            <tr>
                                                <td>{{$count_sec+$dif_sec+$i}}</td>
                                                @if($include_taxation=='yes' && $include_inflation=='no')
                                                    <td>₹ {{custome_money_format($initial_investment)}}</td>
                                                @else
                                                    <td>₹ <!-- {{custome_money_format($lumpsum_investment_required1)}} -->{{custome_money_format($initial_investment)}}</td>
                                                @endif
                                                <td>₹ {{custome_money_format($year_end_value_senario_1)}}</td>
                                            </tr>
                                        @endfor
                                    @endif
                                    </tbody>
                                </table>
                            </div>
        
                            @endif
        
                           
        
                                @endif
        
        
                                <!-- ************************* -->
        
                                @if($include_taxation=='yes' && $deferment=='no')
                                <h5 class="text-center">Annual Tax & Post-Tax Withdrawal</h5>
                                <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                     <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            @if(isset($interest2))
                                            <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Annual Withdrawal</th>
                                            <th>Tax Payable</th>
                                            <th>Post - Tax Withdrawal</th>
                                            @if(isset($interest2))
                                            <th>Annual Withdrawal</th>
                                            <th>Tax Payable</th>
                                            <th>Post - Tax Withdrawal</th>
                                            @endif
                                    </tr>
                                    @if($include_inflation=='yes')
        
                                     @php
                                        $yr=1;
                                        $l147=0;
                                        $q147=0;
                                        $g103=0;
        
                                        $w103=0;
                                        $ab107=0;
                                        $ag107=0;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $ax=$initial_investment;
                                        }else{
                                            $ax=$ax+$ax*$av41_inf;
                                        }
        
                                        $av37=(1+($interest1/100))**(1/12)-1;
                                        $az=$ax/(1+$av37)**$i;
        
                                        $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bd63=$ax-$az;
                                        }else{
                                            $bd63=$ax-$bc63;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $bh63=$ax-$bg63;
        
                                        $g103+=$ax;
                                        $l147+=$bg63;
                                        $q147+=$bh63;
                                        
                                       //--------ggggggggg
        
                                        if(isset($interest2))
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $bo=$initial_investment;
                                        }else{
                                            $bo=$bo+$bo*$av41_inf;
                                        }
        
                                        $w103+=$bo;
        
                                        $bl63=(1+($interest2/100))**(1/12)-1;
                                        $bq63=$bo/(1+$bl63)**$i;
        
                                        $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bu63=$bo-$bq63;
                                        }else{
                                            $bu63=$bo-$bt63;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $ab107+=$bx63;
                                        $by63=$bo-$bx63;
                                        $ag107+=$by63;
        
                                        }
        
                                        if($i%12==0)
                                        {
                                    @endphp
                                    <!-- nnnnnnnnnnnnnn -->
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$yr}}</td>
                                        <td>₹ {{custome_money_format($g103)}}</td>
                                        <td>₹ {{custome_money_format($l147)}}</td>
                                        <td>₹ {{custome_money_format($q147)}}</td>
                                        @if(isset($interest2))
                                        <td>₹ {{custome_money_format($w103)}}</td>
                                        <td>₹ {{custome_money_format($ab107)}}</td>
                                        <td>₹ {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @php
                                            $g103=0;
                                            $q147=0;
                                            $l147=0;
                                            
                                            $w103=0;
                                            $ag107=0;
                                            $ab107=0;
                                            $yr++;
                                        }
                                        }
                                    @endphp
        
                                    @else
                                    @php
                                        $yr=1;
                                        $l147=0;
                                        $q147=0;
                                        $g103=0;
        
                                        $w103=0;
                                        $ab107=0;
                                        $ag107=0;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
                                        //zzzzzzzzzzzz
                                        
                                        $ax=$initial_investment;
                                        
        
                                        $av37=(1+($interest1/100))**(1/12)-1;
                                        $az=$ax/(1+$av37)**$i;
        
                                        $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bd63=$ax-$az;
                                        }else{
                                            $bd63=$ax-$bc63;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $bh63=$ax-$bg63;
        
                                        $g103+=$ax;
                                        $l147+=$bg63;
                                        $q147+=$bh63;
                                        
                                       //--------
        
                                        if(isset($interest2))
                                        {
        
                                       
                                        $bo=$initial_investment;
                                       
        
                                        $w103+=$bo;
        
                                        $bl63=(1+($interest2/100))**(1/12)-1;
                                        $bq63=$bo/(1+$bl63)**$i;
        
                                        $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bu63=$bo-$bq63;
                                        }else{
                                            $bu63=$bo-$bt63;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $ab107+=$bx63;
                                        $by63=$bo-$bx63;
                                        $ag107+=$by63;
        
                                        }
        
                                        if($i%12==0)
                                        {
                                    @endphp
                                    <!-- nnnnnnnnnnnnnn -->
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$yr}}</td>
                                        <td>₹ {{custome_money_format($initial_investment*12)}}</td>
                                        <td>₹ {{custome_money_format($l147)}}</td>
                                        <td>₹ {{custome_money_format($q147)}}</td>
                                        @if(isset($interest2))
                                        <td>₹ {{custome_money_format($initial_investment*12)}}</td>
                                        <td>₹ {{custome_money_format($ab107)}}</td>
                                        <td>₹ {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @php
                                            $g103=0;
                                            $q147=0;
                                            $l147=0;
                                            
                                            $w103=0;
                                            $ag107=0;
                                            $ab107=0;
                                            $yr++;
                                        }
                                        }
                                    @endphp
        
                                    @endif
        
                                    </tbody>
                                </table>
                                </div>
        
                                @endif
        
                                @if($include_taxation=='yes' && $deferment=='yes')
        
                                <h5 class="text-center">Annual Tax & Post-Tax Withdrawal</h5>
                                <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                     <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th colspan="3">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <th colspan="3">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th>Annual Withdrawal</th>
                                            <th>Tax Payable</th>
                                            <th>Post - Tax Withdrawal</th>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <th>Annual Withdrawal</th>
                                            <th>Tax Payable</th>
                                            <th>Post - Tax Withdrawal</th>
                                            @endif
                                    </tr>
                                    @if($include_inflation=='yes')
        
                                        @php
                                        $yr=1;
                                        $l147=0;
                                        $q147=0;
                                        $g103=0;
        
                                        $w103=0;
                                        $ab107=0;
                                        $ag107=0;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $ax=$initial_investment;
                                        }else{
                                            $ax=$ax+$ax*$av41_inf;
                                        }
                                        //echo $ax.'---';
        
                                        $av37=(1+($distribution_phase_interest_rate_1/100))**(1/12)-1;
                                        $az=$ax/(1+$av37)**$i;
        
                                        $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bd63=$ax-$az;
                                        }else{
                                            $bd63=$ax-$bc63;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $bh63=$ax-$bg63;
        
                                        $g103+=$ax;
                                        $l147+=$bg63;
                                        $q147+=$bh63;
                                        
                                       //--------
        
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $bo=$initial_investment;
                                        }else{
                                            $bo=$bo+$bo*$av41_inf;
                                        }
        
                                        $w103+=$bo;
        
                                        $bl63=(1+($distribution_phase_interest_rate_2/100))**(1/12)-1;
                                        $bq63=$bo/(1+$bl63)**$i;
        
                                        $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bu63=$bo-$bq63;
                                        }else{
                                            $bu63=$bo-$bt63;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $ab107+=$bx63;
                                        $by63=$bo-$bx63;
                                        $ag107+=$by63;
        
                                        }
        
                                        if($i%12==0)
                                        {
                                    @endphp
                                    <!-- nnnnnnnnnnnnnn -->
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$yr}}</td>
                                        <td>₹ {{custome_money_format($g103)}}</td>
                                        <td>₹ {{custome_money_format($l147)}}</td>
                                        <td>₹ {{custome_money_format($q147)}}</td>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <td>₹ {{custome_money_format($w103)}}</td>
                                        <td>₹ {{custome_money_format($ab107)}}</td>
                                        <td>₹ {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @php
                                            $g103=0;
                                            $q147=0;
                                            $l147=0;
                                            
                                            $w103=0;
                                            $ag107=0;
                                            $ab107=0;
                                            $yr++;
                                        }
                                        }
                                    @endphp
                                    @else
        
                                    @php
                                        $yr=1;
                                        $l147=0;
                                        $q147=0;
                                        $ab107=0;
                                        $ag107=0;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
                                        $av37=(1+($distribution_phase_interest_rate_1/100))**(1/12)-1;
                                        $ay107=$monthly_annuity_amount1/(1+$av37)**$i;
                                        $bb107=$ay107*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
                                        if($yr<=$for_period_upto)
                                        {
                                            $bc=$monthly_annuity_amount1-$ay107;
                                        }else{
                                            $bc=$monthly_annuity_amount1-$bb107;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bf=$bc*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bf=$bc*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $l147+=$bf;
                                        $bg107=$monthly_annuity_amount1-$bf;
                                        $q147+=$bg107;
                                        
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
                                        $bm107=$monthly_annuity_amount2;
                                        $bk107=(1+($distribution_phase_interest_rate_2/100))**(1/12)-1;
                                        $bo107=$monthly_annuity_amount2/(1+$bk107)**$i;
                                        $br107=$bo107*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
                                        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bs107=$bm107-$bo107;
                                        }else{
                                            $bs107=$bm107-$br107;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bv107=$bs107*$applicable_short_term_tax_rate/100;
                                        }else{
                                            $bv107=$bs107*$applicable_long_term_tax_rate/100;
                                        }
                                        $ab107+=$bv107;
                                        $bw107=$bm107-$bv107;
                                        $ag107+=$bw107;
                                        }
                                        if($i%12==0)
                                        {
                                    @endphp
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$yr}}-</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount1*12)}}</td>
                                        <td>₹ {{custome_money_format($l147)}}</td>
                                        <td>₹ {{custome_money_format($q147)}}</td>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <td>₹ {{custome_money_format($monthly_annuity_amount2*12)}}</td>
                                        <td>₹ {{custome_money_format($ab107)}}</td>
                                        <td>₹ {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @php
                                            $ag107=0;
                                            $ab107=0;
                                            $q147=0;
                                            $l147=0;
                                            $yr++;
                                        }
                                        }
                                    @endphp
                                    @endif
                                    </tbody>
                                </table>
                                </div>
        
                                @endif
                            <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                        @endif
                            @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
