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
                        url: "{{ route('frontend.monthlyTargetAnnuityForSIPOutputSave') }}",
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
    <div class="banner styleApril">
        <div class="container">
        @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">SIP Required For Target Monthly SWP</h2>
                </div>
            </div>
        </div>
    </div>

    @php

    //SIP Period (Month)
    $sip_period_months = $sip_period*12;
     //Annuity Period (Months) T9*12
    $annuity_period_months = $annuity_period*12;
     //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
   
    $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
    
    //Lumpsum For Balance (1) T15/(1+AV31)^(AV30)
    $lumpsum_for_balance1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
    //Lumpsum For Annuity (1) (X30*(1-(1+AV31)^(-AV30)))/AV31

    if($include_inflation=='yes')
    {
        $av37 = (1+$expected_inflation_rate/100)**(1/12)-1;
        $lumpsum_for_annuity1 = $sip_amount*((1-((1+$av37)**($annuity_period_months))*((1+$distribution_monthly_return1)**(-$annuity_period_months)))/($distribution_monthly_return1-$av37));
    }else{
        $lumpsum_for_annuity1 = ($sip_amount*(1-(1+$distribution_monthly_return1)**(-$annuity_period_months)))/$distribution_monthly_return1;
    }
    


    //Annuity Purchase Amount (1) AV33+AV35
    $annuity_purchase_amount1 = $lumpsum_for_balance1+$lumpsum_for_annuity1;
    
    
    //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
    $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1 ;
    //SIP Required (1) AV37/((1+AV40)*((1+AV40)^(AV39)-1)/AV40)

    if($deferment=='yes')
    {
        $av43=$deferment_period*12;
        $av46=$annuity_purchase_amount1/(1+$accumulation_monthly_return1)**$av43;
        $sip_required1 = $av46/((1+$accumulation_monthly_return1)*((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
    }else{
        $sip_required1 = $annuity_purchase_amount1/((1+$accumulation_monthly_return1)*((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1); 
    }
    

    if (isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0){
         //Distribution Monthly Return (2)
         $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
         //Lumpsum For Balance (2) T15/(1+AV31)^(AV30)
         $lumpsum_for_balance2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
         //Lumpsum For Annuity (2) (X30*(1-(1+AV31)^(-AV30)))/AV31

        if($include_inflation=='yes')
        {
            $av37 = (1+$expected_inflation_rate/100)**(1/12)-1;
            $lumpsum_for_annuity2 = $sip_amount*((1-((1+$av37)**($annuity_period_months))*((1+$distribution_monthly_return2)**(-$annuity_period_months)))/($distribution_monthly_return2-$av37));
        }else{
            $lumpsum_for_annuity2 = ($sip_amount*(1-(1+$distribution_monthly_return2)**(-$annuity_period_months)))/$distribution_monthly_return2;
        }


         //Annuity Purchase Amount (2) AV33+AV35
        $annuity_purchase_amount2 = $lumpsum_for_balance2+$lumpsum_for_annuity2;
        //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
        $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1 ;
         //SIP Required (2) AV37/((1+AV40)*((1+AV40)^(AV39)-1)/AV40)

        if($deferment=='yes')
        {
            $av43=$deferment_period*12;
            $av47=$annuity_purchase_amount2/(1+$accumulation_monthly_return2)**$av43;
            $sip_required2 = $av47/((1+$accumulation_monthly_return2)*((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
        }else{
            $sip_required2 = $annuity_purchase_amount2/((1+$accumulation_monthly_return2)*((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
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

    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.monthlyTargetAnnuityForBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                        <a href="{{route('frontend.monthlyTargetAnnuityForSIPOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                    @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <div class="outputTableHolder">
                    <h1 class="midheading">Monthly SWP Planning @if(isset($clientname)) For {{$clientname?$clientname:''}} @endif</h1>
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
                            <td>
                                {{$current_age}} Years
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <strong>Target Monthly SWP</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($sip_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> SIP Period</strong>
                            </td>
                            <td>
                                {{$sip_period?$sip_period:0}} Years
                            </td>
                        </tr>
                        @if(isset($deferment_period) && $deferment_period)
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
                    </div>
                    
                    <h1 class="midheading">Accumulated Corpus Required</h1>
                    <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                            <tr>
                                <td>
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                <td>
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
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
                        @else
                            
                            <h5 class="outputSingleBox">₹ {{custome_money_format($annuity_purchase_amount1)}}</h5>

                        @endif
                        </tbody>
                    </table>
                    </div>

                    <h1 class="midheading">Monthly SIP Required</h1>
                    <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
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
                                    <strong>₹ {{custome_money_format($sip_required1)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($sip_required2)}} </strong>
                                </td>
                            </tr>
                        @else
                            
                             <h5 class="outputSingleBox">₹ {{custome_money_format($sip_required1)}}</h5>

                        @endif
                        </tbody>
                    </table>
                    </div>

                    <h1 class="midheading">Total Withdrawal</h1>
                    <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                         <h5 class="outputSingleBox">
                            ₹ 
                            @php
                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                            @endphp
                            {{custome_money_format($av50_new)}}
                         </h5>
                    <?php } elseif ($deferment == 'yes' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            @php
                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                            @endphp
                            {{custome_money_format($av50_new)}}
                         </h5>
                    <?php } elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            {{custome_money_format($sip_amount*$annuity_period_months)}}
                         </h5>
                    <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            {{custome_money_format($sip_amount*$annuity_period_months)}}
                         </h5>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            @php
                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                            @endphp
                            {{custome_money_format($av50_new)}}
                         </h5>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            @php
                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                            @endphp
                            {{custome_money_format($av50_new)}}
                         </h5>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            {{custome_money_format($sip_amount*$annuity_period_months)}}
                         </h5>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                        <h5 class="outputSingleBox">
                            ₹ 
                            {{custome_money_format($sip_amount*$annuity_period_months)}}
                         </h5>
                    <?php } ?>
                    
                    {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')

                    @if(isset($report) && $report=='detailed')
                        <h1 class="midheading">Accumulation Phase <br>Projected Annual Investment Value</h1>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered text-center">
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2" style="vertical-align: middle;">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    <th colspan="2" style="vertical-align: middle;">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle;">Annual Investment</th>
                                    <th>Year End Value</th>
                                    <th style="vertical-align: middle;">Annual Investment</th>
                                    <th>Year End Value</th>
                                </tr>

                                @for($i=1;$i<=$s_count;$i++)
                                    @php
                                        //Year End Value (1+AV65)*AS65*(((1+AV65)^(AU65*12)-1)/AV65)

                                        if($i<=$sip_period)
                                        {
                                            $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_required1*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                        }else{
                                            $year_end_value1 = $av46*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                                        }

                                        if($i<=$sip_period)
                                        {
                                            $year_end_value2 = (1+$accumulation_monthly_return2)*$sip_required2*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);
                                        }else{
                                            $year_end_value2 = $av47*(1+$accumulation_monthly_return2)**(($i-$sip_period)*12);
                                        }
                                        
                                        
                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec}}</td>
                                        @if($i>$sip_period)
                                        <td>--</td>
                                        @else
                                        <td>₹ {{custome_money_format($sip_required1*12)}}</td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value1)}}</td>
                                        @if($i>$sip_period)
                                        <td>--</td>
                                        @else
                                        <td>₹ {{custome_money_format($sip_required2*12)}}</td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align: middle;">Annual Investment</th>
                                    <th>Year End Value</th>
                                </tr>
                                @for($i=1;$i<=$s_count;$i++)
                                    @php
                                        //Year End Value (1+AV65)*AS65*(((1+AV65)^(AU65*12)-1)/AV65)
                                       if($i<=$sip_period)
                                        {
                                            $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_required1*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                        }else{
                                            $year_end_value1 = $av46*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec}}</td>
                                        @if($i>$sip_period)
                                        <td>--</td>
                                        @else
                                            <td>₹ {{custome_money_format($sip_required1*12)}}</td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        </div>

                        <h1 class="midheading">Distribution Phase <br>Annual Withdrawal & Projected Investment Value</h1>
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
                        <div class="roundBorderHolder">
                        <table class="table table-bordered text-center">
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
                                $aw107=$sip_amount;
                                $au107=$annuity_purchase_amount1+$annuity_purchase_amount1*$distribution_monthly_return1;

                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {
                                    $bd107=$sip_amount;
                                    $bb107=$annuity_purchase_amount2+$annuity_purchase_amount2*$distribution_monthly_return2;
                                }
                                

                            }else{
                                $aw107=$aw107+$aw107*$av37;
                                $au107=$ax107+$ax107*$distribution_monthly_return1;

                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {
                                    $bd107=$bd107+$bd107*$av37;
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
                                    <td>{{$yr+$count_sec+$dp+$sp}}</td>
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
                        </div>
                        @else
                        <div class="roundBorderHolder">
                        <table class="table table-bordered text-center">
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
                                        //Year End Balance (AS109*(1+AU109)^(AR109*12)-(AW109*((1+AU109)^(AR109*12)-1)/AU109))
                                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($sip_amount*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                                        $year_end_balance2 = ($annuity_purchase_amount2*(1+$distribution_monthly_return2)**($i*12)-($sip_amount*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));
                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec+$dp+$sp}}</td>
                                        @if($include_taxation=='yes')
                                            <td>₹ {{custome_money_format($sip_required1)}}</td>
                                        @else
                                            <td>₹ {{custome_money_format($sip_amount)}}</td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_balance1)}}</td>
                                        @if($include_taxation=='yes')
                                            <td>₹ {{custome_money_format($sip_required2)}}</td>
                                        @else
                                            <td>₹ {{custome_money_format($sip_amount)}}</td>
                                        @endif
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
                                        //Year End Balance (AS109*(1+AU109)^(AR109*12)-(AW109*((1+AU109)^(AR109*12)-1)/AU109))
                                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($sip_amount*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec+$dp+$sp}}</td>
                                        <td>₹ {{custome_money_format($sip_amount)}}</td>
                                        <td>₹ {{custome_money_format($year_end_balance1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        </div>
                        @endif
                        @if($include_taxation=='yes')
                        <h1 class="midheading">Annual Tax & Post-Tax Annual Withdrawal</h1>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered text-center">
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
                                    $ax=$sip_amount;
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
                                    $bo=$sip_amount;
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
                                
                                $ax=$sip_amount;
                                
                               

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

                               
                                $bo=$sip_amount;
                               

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
                                <td>₹ {{custome_money_format($sip_amount*12)}}</td>
                                <td>₹ {{custome_money_format($l147)}}</td>
                                <td>₹ {{custome_money_format($q147)}}</td>
                                @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                <td>₹ {{custome_money_format($sip_amount*12)}}</td>
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
                    <div class="description-text">
                        @php
                            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
                            if(!empty($note_data2)){
                        @endphp
                            {!!$note_data2->description!!}
                        @php } @endphp
                        Report Date : {{date('d/m/Y')}}
                    </div>
                        <!--<p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>-->
                    @endif
                    @include('frontend.calculators.suggested.output')
                </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.monthlyTargetAnnuityForBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                        <a href="{{route('frontend.monthlyTargetAnnuityForSIPOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                    @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
    
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>  
                    </div>
                </div>
                
                
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
        <!--</div>-->
    </section>

    <script type="text/javascript">
        var base_url = "{{route('frontend.monthlyTargetAnnuityForSIPOutputDownloadPdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
