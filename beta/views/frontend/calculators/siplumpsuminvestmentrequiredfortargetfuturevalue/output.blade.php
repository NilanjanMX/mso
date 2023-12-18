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
                        url: "{{ route('frontend.sipLumpsumInvestmentTargetFutureValueOutputSave') }}",
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
                    <h2 class="page-title">SIP / Lumpsum Required For Target Future Value</h2>
                </div>
            </div>
        </div>
    </div>
    @php
    $target_amount = $target_amount;
    $period = $investment_period;
    $period_in_months = $period * 12;

    $investment_type = $investment_type;
    $investment_amount = $investment_amount;

    $sip_interest_rate = $sip_interest_rate;
    $monthly_sip_interest_rate = (1+$sip_interest_rate/100)**(1/12)-1;

    $onetime_interest_rate = $onetime_interest_rate;
    $monthly_onetime_interest_rate = (1+$onetime_interest_rate/100)**(1/12)-1;

    if ($investment_type == "SIP") {
        $lumpsum_investment_amount = $investment_amount;
        $lumpsum_future_value = $investment_amount*(1+$monthly_onetime_interest_rate)**$period_in_months ;
        $required_sip_future_value = $target_amount - $lumpsum_future_value;
        $required_sip = ($required_sip_future_value * $monthly_sip_interest_rate) / ((1 + $monthly_sip_interest_rate) * (pow((1 + $monthly_sip_interest_rate), ($period_in_months)) - 1));
    }
    if ($investment_type == "lumpsum") {
        $sip_amount = $investment_amount;
        //(1+AR32)*Q12*(((1+AR32)^(AR31)-1)/AR32)
        $sip_future_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($period_in_months)-1)/$monthly_sip_interest_rate);
        $required_lumpsum_future_value = $target_amount - $sip_future_value;
        //AR36/(1+AR33)^AR31
        $required_onetime_investment = $required_lumpsum_future_value/(1+$monthly_onetime_interest_rate)**$period_in_months;
    }
   @endphp

    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.sipLumpsumInvestmentTargetFutureValueBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    

                    

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <div class="outputTableHolder">
                        <h1 class="midheading">SIP + Lumpsum @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($target_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>
                                            @if ($investment_type == "SIP") Lumpsum Investment @else SIP Amount @endif</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($investment_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{$investment_period?$investment_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; vertical-align: middle;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="padding: 0px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                            @if ($investment_type == "lumpsum")
                                            <tr>
                                                <td>SIP</td>
                                                <td>{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                                            </tr>
                                            <tr>
                                                <td>Lumpsum</td>
                                                <td>{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                                            </tr>
                                            @else
                                                <tr>
                                                    <td>Lumpsum</td>
                                                    <td>{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                                                </tr>
                                                <tr>
                                                    <td>SIP</td>
                                                    <td>{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
        
                                </tbody>
                            </table>
                        </div>
                        
                        
                            <h1 class="midheading">
                                @if ($investment_type == "SIP") Monthly SIP Required @else Lumpsum Investment Required @endif
                            </h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        @if ($investment_type == "SIP")
                                            ₹ {{custome_money_format($required_sip)}}
                                        @else
                                            ₹ {{custome_money_format($required_onetime_investment)}}
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                            
                            {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                            <p class="text-left">
                                * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                                * Returns are not guaranteed. The above is for illustration purpose only.
                            </p>
                            @if ($report != "summary")
                            <h1 class="midheading">Annual Investment & Yearwise Projected Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle;">Year</th>
                                    <th style="vertical-align: middle;">Annual Investment</th>
                                    <th style="vertical-align: middle;">Cumulative Investment</th>
                                    <th style="vertical-align: middle;">SIP Fund Value</th>
                                    <th style="vertical-align: middle;">Lumpsum Fund Value</th>
                                    <th style="vertical-align: middle;">Total Fund Value</th>
                                </tr>
                                    @php
                                        $cumulative_investment = 0;
                                    @endphp
        
        
                                     @if ($investment_type == "SIP")
                                         @for ($i = 1; $i <= $investment_period; $i++)
                                            @php
                                                $annual_investment = ($required_sip * 12);
                                                        if ($i == 1) {
                                                            $annual_investment = $lumpsum_investment_amount + ($required_sip * 12);
                                                        }
        
                                                        $cumulative_investment = $lumpsum_investment_amount + (($required_sip * 12) * $i);
        
                                                        $sip_value = (1+$monthly_sip_interest_rate)*$required_sip*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                                        $lumpsum_value = $lumpsum_investment_amount*(1+$monthly_onetime_interest_rate)**($i*12);
        
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>₹ {{custome_money_format($annual_investment)}}</td>
                                                <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                                <td>₹ {{custome_money_format($sip_value)}}</td>
                                                <td>₹ {{custome_money_format($lumpsum_value)}}</td>
                                                <td>₹ {{custome_money_format($sip_value + $lumpsum_value)}}</td>
                                            </tr>
                                        @endfor
        
                                   @else
                                         @for ($i = 1; $i <= $investment_period; $i++)
                                             @php
                                                $annual_investment = ($sip_amount * 12);
                                                            if ($i == 1) {
                                                                $annual_investment = $required_onetime_investment + ($sip_amount * 12);
                                                            }
        
                                                            $cumulative_investment = $required_onetime_investment + (($sip_amount * 12) * $i);
                                                            $sip_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                                            $lumpsum_value = $required_onetime_investment*(1+$monthly_onetime_interest_rate)**($i*12);
                                             @endphp
                                             <tr>
                                                 <td>{{$i}}</td>
                                                 <td>₹ {{custome_money_format($annual_investment)}}</td>
                                                 <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                                 <td>₹ {{custome_money_format($sip_value)}}</td>
                                                 <td>₹ {{custome_money_format($lumpsum_value)}}</td>
                                                 <td>₹ {{custome_money_format($sip_value + $lumpsum_value)}}</td>
                                             </tr>
                                         @endfor
                                   @endif
                                </tbody>
                            </table>
                            </div>
                            <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                            @endif
                            @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.sipLumpsumInvestmentTargetFutureValueBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
