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
                        url: "{{ route('frontend.monthlyAnnuityForSIPOutputSave') }}",
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
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>
    </div>

    @php
        //SIP Period (Month)
        $sip_period_months = $sip_period*12;
         //Annuity Period (Months) T9*12
        $annuity_period_months = $annuity_period*12;
        //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
        $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1 ;
        //Annuity Purchase Amount (1)  (1+AV29)*T9*(((1+AV29)^(AV28)-1)/AV29)

        if($deferment=='yes')
        {
            
            $annuity_purchase_amount1_pre = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
            $av35=$deferment_period*12;
            $annuity_purchase_amount1=$annuity_purchase_amount1_pre*(1+$accumulation_monthly_return1)**$av35;
        }else{
            $annuity_purchase_amount1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
        }
        


        //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
        $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
        //PV of Balance Required (1) T15/(1+AV35)^(AV33)
        $pv_of_balance_required1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
        //Balance Available for Annuity (1) AV31-AV37
        $balance_available_for_annuity1 = $annuity_purchase_amount1 - $pv_of_balance_required1;
        //Monthly Annuity Amount (1) (AV35*AV39)/(1-(1+AV35)^(-AV33))
        if($include_inflation=='yes')
        {
            if($distribution_phase_interest_rate_1==$expected_inflation_rate)
            {
                $monthly_annuity_amount1 = $balance_available_for_annuity1*(1+$distribution_monthly_return1)/$annuity_period_months;
            }else{
                $av46_inf=(1+$expected_inflation_rate/100)**(1/12)-1;
                $monthly_annuity_amount1 = $balance_available_for_annuity1/((1-((1+$av46_inf)**($annuity_period_months))*((1+$distribution_monthly_return1)**(-$annuity_period_months)))/($distribution_monthly_return1-$av46_inf));
            }
         
        }else{
         $monthly_annuity_amount1 = ($distribution_monthly_return1*$balance_available_for_annuity1)/(1-(1+$distribution_monthly_return1)**(-$annuity_period_months));
        }
       

        if (isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0){
            //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
            $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1 ;
            //Annuity Purchase Amount (2)  (1+AV29)*T9*(((1+AV29)^(AV28)-1)/AV29)

            if($deferment=='yes')
            {
                $annuity_purchase_amount2_pre = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
                $av35=$deferment_period*12;
                $annuity_purchase_amount2=$annuity_purchase_amount2_pre*(1+$accumulation_monthly_return2)**$av35;
            }else{
                $annuity_purchase_amount2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
            }
            


            //Distribution Monthly Return (2)
             $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
             //PV of Balance Required (1) T15/(1+AV35)^(AV33)
            $pv_of_balance_required2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
            //Balance Available for Annuity (1) AV31-AV37
            $balance_available_for_annuity2 = $annuity_purchase_amount2 - $pv_of_balance_required2;
            //Monthly Annuity Amount (2) (AV35*AV39)/(1-(1+AV35)^(-AV33))
            

            if($include_inflation=='yes')
            {
                if($distribution_phase_interest_rate_2==$expected_inflation_rate)
                {
                    $monthly_annuity_amount2 = $balance_available_for_annuity2*(1+$distribution_monthly_return2)/$annuity_period_months;
                }else{
                    $av46_inf2=(1+$expected_inflation_rate/100)**(1/12)-1;
                    $monthly_annuity_amount2 = $balance_available_for_annuity2/((1-((1+$av46_inf2)**($annuity_period_months))*((1+$distribution_monthly_return2)**(-$annuity_period_months)))/($distribution_monthly_return2-$av46_inf2));
                }
             
            }else{
                $monthly_annuity_amount2 = ($distribution_monthly_return2*$balance_available_for_annuity2)/(1-(1+$distribution_monthly_return2)**(-$annuity_period_months));
            }
        }

    
    if(isset($sip_period) && $sip_period>0)
    {
            $sp=$sip_period;
    }else{
            $sp=0;
    }

    if(isset($deferment_period) && $deferment_period>0)
    {
            $dp=$deferment_period;
    }else{
            $dp=0;
    }

    @endphp

    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round cal_back_glob" ><i class="fa fa-angle-left"></i> Back</a>

                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.monthlyAnnuityForSIPOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Monthly SWP Calculation @if(isset($clientname)) For {{$clientname?$clientname:''}} @endif</h5>
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
                                <strong>Monthly SIP Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($sip_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>SIP Period</strong>
                            </td>
                            <td>
                                {{$sip_period?$sip_period:0}} Years
                            </td>
                        </tr>
                        @if(isset($deferment_period) && $deferment_period>0)
                        <tr>
                            <td>
                                <strong>Deferment Period</strong>
                            </td>
                            <td>
                                {{$deferment_period}} Years
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <strong>SWP Period</strong>
                            </td>
                            <td>
                                {{$annuity_period?$annuity_period:0}} Years
                            </td>
                        </tr>
                        @if(isset($balance_required))
                            <tr>
                                <td>
                                    <strong>Balance Required</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($balance_required)}}
                                </td>
                            </tr>
                        @endif
                        </tbody></table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Accumulated Corpus @if($deferment=='yes') @if(!isset($accumulation_phase_interest_rate_2)) @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} % @endif @endif</h1>
                    
                        @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                        <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td>
                                    Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                <td>
                                    Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>₹ {{custome_money_format($annuity_purchase_amount1)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($annuity_purchase_amount2)}} </strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @else

                        <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">₹ {{custome_money_format($annuity_purchase_amount1)}}</h2>
                            
                        @endif
                        
                    @if($include_inflation=='yes')

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">First Year Average Monthly SWP @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif  @endif</h1>

                    @else

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Monthly SWP Amount @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif @endif</h1>

                    @endif
                    
                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                        <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td>
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                <td>
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                @if($include_inflation=='yes')
                                @php
                                
                                $av43_new=($monthly_annuity_amount1*(1-(1+$av46_inf)**12)/(1-(1+$av46_inf)))/12;
                             
                                @endphp
                                <td>
                                    <strong>₹ {{custome_money_format($av43_new)}}</strong>
                                </td>
                                @else
                                <td>
                                    <strong>₹ {{custome_money_format($monthly_annuity_amount1)}} </strong>
                                </td>
                                @endif
                                @if($include_inflation=='yes')
                                @php
                                $av43_new2=($monthly_annuity_amount2*(1-(1+$av46_inf)**12)/(1-(1+$av46_inf)))/12;
                                @endphp
                                <td>
                                    <strong>₹ {{custome_money_format($av43_new2)}}</strong>
                                </td>
                                @else
                                <td>
                                    <strong>₹ {{custome_money_format($monthly_annuity_amount2)}} </strong>
                                </td>
                                @endif
                            </tr>
                            </tbody>
                    </table>
                        @else

                        <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ {{custome_money_format($monthly_annuity_amount1)}}</h2>
                            
                        @endif

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Total Withdrawal</h1>

                        
                    <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php } elseif ($deferment == 'yes' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                   <?php } elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                                $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                                $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            @php
                                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @  {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        @php
                                            $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                            $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                        @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        @php
                                            $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                            $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                        @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @  {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                         <td>
                                            @php
                                                $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                        @if(isset($monthly_annuity_amount2))
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td>
                                            Scenario 1 @  {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                        <td>
                                            Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                        </td>
                                        @endif
                                    </tr>
                                    <tr>
                                         <td>
                                            @php
                                                $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                            @endphp
                                           
                                            <strong>₹ {{custome_money_format($av50_new)}}</strong>
                                        </td>
                                        @if(isset($monthly_annuity_amount2))
                                            @php
                                                $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                            @endphp
                                        <td>
                                            <strong>₹ {{custome_money_format($av50_new)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <h2 style="color: #131f55;font-size:22px;marginn-bottom:5px;">₹ 
                                @php
                                    $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                @endphp
                                       
                                {{custome_money_format($av50_new)}}
                            </h2>
                        @endif
                    <?php } ?>
                        

                    @if(isset($report) && $report=='detailed')
                        <h5 class="text-center">Accumulation Phase <br>Projected Annual Investment Value</h5>
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            @php
                            $ci=0;
                            if($deferment=='yes')
                            {
                                $s_count=$sip_period+$deferment_period;
                            }else{
                                $s_count=$sip_period;
                            }
                            @endphp
                            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)

                                <tr>
                                    <th style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th>Annual Investment</th>
                                    @if($deferment=='yes')
                                    <th>Cumulative Investment</th>
                                    @endif
                                    <th>Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                    <th>Year End Value <br> @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                                </tr>
                               
                                    @for($i=1;$i<=$s_count;$i++)
                                
                                    @php
                                        //Year End Value (1+AV64)*AT64*(((1+AV64)^(AU64*12)-1)/AV64)
                                       //$year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                     if($i<=$sip_period)
                                     {
                                        $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                     }else{
                                        $year_end_value1 = $annuity_purchase_amount1_pre*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                                     }
                                       //Year End Value AT65*(1+AV65)^AU65
                                       //$year_end_value2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);

                                     if($i<=$sip_period)
                                     {
                                        $year_end_value2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);
                                     }else{
                                        $year_end_value2 = $annuity_purchase_amount2_pre*(1+$accumulation_monthly_return2)**(($i-$sip_period)*12);
                                     }
                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec}}</td>
                                        @if($i>$sip_period)
                                        <td>--</td>
                                        @else
                                        <td>₹ {{custome_money_format($sip_amount*12)}}</td>
                                        @endif
                                        @if($deferment=='yes')
                                        @php
                                        if($i>$sip_period){
                                            $ci=$ci;
                                        }else{
                                            $ci=($sip_amount*12)+$ci;
                                        }
                                        
                                        @endphp
                                        <td>₹ {{custome_money_format($ci)}}</td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value1)}}</td>
                                        <td>₹ {{custome_money_format($year_end_value2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th>Annual Investment</th>
                                    @if($deferment=='yes')
                                    <th>Cumulative Investment</th>
                                    @endif
                                    <th>Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                </tr>
                                @for($i=1;$i<=$s_count;$i++)
                                    @php
                                        //Year End Value (1+AV64)*AT64*(((1+AV64)^(AU64*12)-1)/AV64)
                                     if($i<=$sip_period)
                                     {
                                        $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                     }else{
                                        $year_end_value1 = $annuity_purchase_amount1_pre*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                                     }
                                       
                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec}}</td>
                                        @if($i>$sip_period)
                                        <td>--</td>
                                        @else
                                        <td>₹ {{custome_money_format($sip_amount*12)}}</td>
                                        @endif
                                        @if($deferment=='yes')
                                        @php
                                        if($i>$sip_period){
                                            $ci=$ci;
                                        }else{
                                            $ci=($sip_amount*12)+$ci;
                                        }
                                        
                                        @endphp
                                        <td>₹ {{custome_money_format($ci)}}</td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>

                        <h5 class="text-center">Distribution Phase <br>Annual Withdrawal & Projected Investment Value</h5>
                        @if($include_inflation=='yes')
                        @php
                        $aw107=0;
                        $bd107=0;
                        $ax107=0;
                        $be107=0;

                        $h107=0;
                        $w107=0;

                        $yr=1;
                        @endphp
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>

                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    @endif
                                </tr>
                            @for($i=1;$i<=$annuity_period*12;$i++)
                            @php
                            if($i==1)    
                            {
                                $aw107=$monthly_annuity_amount1;
                                $au107=$annuity_purchase_amount1+$annuity_purchase_amount1*$distribution_monthly_return1;

                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {
                                    $bd107=$monthly_annuity_amount2;
                                    $bb107=$annuity_purchase_amount2+$annuity_purchase_amount2*$distribution_monthly_return2;
                                }
                                

                            }else{
                                $aw107=$aw107+$aw107*$av46_inf;
                                $au107=$ax107+$ax107*$distribution_monthly_return1;

                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {
                                    $bd107=$bd107+$bd107*$av46_inf2;
                                    $bb107=$be107+$be107*$distribution_monthly_return2;
                                }
                            }       
                            
                            $ax107=$au107-$aw107;
                            $h107+=$aw107;

                            if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                            {
                                $be107=$bb107-$bd107;
                                $w107+=$bd107;
                            }
                            if($i%12==0)
                            {
                            @endphp
                            <tr>
                                    <td>{{$yr+$count_sec+$sp+$dp}}</td>
                                    <td>₹ {{custome_money_format($h107/12)}}</td>
                                    <td>₹ {{custome_money_format($ax107)}}</td>

                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)

                                    <td>₹ {{custome_money_format($w107/12)}}</td>
                                    <td>₹ {{custome_money_format($be107)}}</td>

                                    @endif
                            </tr>
                            @php 
                            $h107=0;
                            $w107=0;
                            $yr++;
                            } @endphp
                            @endfor
                        </table>
                        @else
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)

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

                                @for($i=1;$i<=$annuity_period;$i++)
                                    @php
                                        //Year End Balance (AS106*(1+AU106)^(AR106*12)-(AW106*((1+AU106)^(AR106*12)-1)/AU106))
                                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                                        $year_end_balance2 = ($annuity_purchase_amount2*(1+$distribution_monthly_return2)**($i*12)-($monthly_annuity_amount2*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));

                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec+$sp+$dp}}</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount1)}}</td>
                                        <td>₹ {{custome_money_format($year_end_balance1)}}</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount2)}}</td>
                                        <td>₹ {{custome_money_format($year_end_balance2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2"> @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>

                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>

                                @for($i=1;$i<=$annuity_period;$i++)
                                    @php
                                        //Year End Balance (AS106*(1+AU106)^(AR106*12)-(AW106*((1+AU106)^(AR106*12)-1)/AU106))
                                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));

                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec+$sp+$dp}}</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount1)}}</td>
                                        <td>₹ {{custome_money_format($year_end_balance1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        @endif

                        @if($include_taxation=='yes')
                        <h5 class="text-center">Annual Tax & Post-Tax Withdrawal</h5>
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                             <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th>Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
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
                                for($i=1;$i<=$annuity_period*12;$i++)
                                {

                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                if($i==1)
                                {
                                    $ax=$monthly_annuity_amount1;
                                }else{
                                    $ax=$ax+$ax*$av41_inf;
                                }

                                $av37=(1+($accumulation_phase_interest_rate_1/100))**(1/12)-1;
                                //$az=$ax/(1+$av37)**$i;
                                $az=$ax/(1+$distribution_monthly_return1)**$i;

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

                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {

                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                if($i==1)
                                {
                                    $bo=$monthly_annuity_amount2;
                                }else{
                                    $bo=$bo+$bo*$av41_inf;
                                }

                                $w103+=$bo;

                                //$bl63=(1+($accumulation_phase_interest_rate_2/100))**(1/12)-1;
                                //$bq63=$bo/(1+$bl63)**$i;

                                $bq63=$bo/(1+$distribution_monthly_return2)**$i;

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
                                <td>{{$count_sec+$dif_sec+$yr+$sp}}</td>
                                <td>₹ {{custome_money_format($g103)}}</td>
                                <td>₹ {{custome_money_format($l147)}}</td>
                                <td>₹ {{custome_money_format($q147)}}</td>
                                @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
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
                                for($i=1;$i<=$annuity_period*12;$i++)
                                {
                                //zzzzzzzzzzzz
                                
                                $ax=$monthly_annuity_amount1;
                                
                               

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

                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {

                               
                                $bo=$monthly_annuity_amount2;
                               

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
                                <td>{{$count_sec+$dif_sec+$yr+$sp}}</td>
                                <td>₹ {{custome_money_format($monthly_annuity_amount1*12)}}</td>
                                <td>₹ {{custome_money_format($l147)}}</td>
                                <td>₹ {{custome_money_format($q147)}}</td>
                                @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                <td>₹ {{custome_money_format($monthly_annuity_amount2*12)}}</td>
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


                        @endif

                        <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @endif
                    @include('frontend.calculators.suggested.output')
                    <div class="row" style="width: 100%;">
                        <div class="col-md-12 text-center">
                            <a href="javascript:history.back()" class="btn btn-primary btn-round cal_back_glob" ><i class="fa fa-angle-left"></i> Back</a>
                            @if($calculator_permissions['is_save'])
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                            @else
                                <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                            @endif
                            @if($calculator_permissions['is_download'])
                                <a href="{{route('frontend.monthlyAnnuityForSIPOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                            @endif
                            <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

    @include('frontend.calculators.modal')

@endsection
