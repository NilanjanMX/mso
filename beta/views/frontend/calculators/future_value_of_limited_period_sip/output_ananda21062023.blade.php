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
                        url: "{{ route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_save') }}",
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
@php
    $total_number_of_months = $sip_period*12;
    $totalinvestment = $amount*$sip_period*12;
    //(1+Q11%)^(1/12)-1
    $rate1_percent  = pow((1+$interest1/100),(1/12))-1;
    //(1+AV31)*Q7*(((1+AV31)^(AV30)-1)/AV31)
    $senario1_fund_amount = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($total_number_of_months))-1)/$rate1_percent);
    //AV33*(1+Q11%)^Q9
    $senario1_amount = $senario1_fund_amount*pow((1+$interest1/100),$deferment_period);
    $senario2_amount = 0;
        if (isset($interest2)){
        $rate2_percent  = pow((1+$interest2/100),(1/12))-1;
        //(1+AV31)*Q7*(((1+AV31)^(AV30)-1)/AV31)
        $senario2_fund_amount = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($total_number_of_months))-1)/$rate2_percent);
        //AV33*(1+Q11%)^Q9
        $senario2_amount = $senario2_fund_amount*pow((1+$interest2/100),$deferment_period);
        }
@endphp

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
                    <h2 class="page-title">Future Value of Limited Period SIP</h2>
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
                        <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h5 class="midheading">Future Value of Limited Period SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Monthly SIP Amount </strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>SIP Period </strong>
                                    </td>
                                    <td>
                                        {{$sip_period?$sip_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Deferment Period </strong>
                                    </td>
                                    <td>
                                        {{$deferment_period?$deferment_period:0}} Years
                                    </td>
                                </tr>
        
                                </tbody></table>
                            <h5 class="text-center">Total Investment</h5>
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        ₹ {{custome_money_format($totalinvestment)}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Expected Future Value</h1>
                            <table class="table table-bordered text-center">
                                <tbody>
                                @if(isset($interest2))
                                        <tr>
                                            <td>
                                                Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </td>
                                            <td>
                                                Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                            </td>
                                            <td>
                                                <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                            </td>
                                        </tr>
                                @else

                                        <tr>
                                            <td>
                                                @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($senario1_amount)}}
                                            </td>
                                        </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(isset($report) && $report=='detailed')
                            <h5 class="text-center">Year-Wise Projected Value</h5>
                            <table class="table table-bordered text-center" style="background: #fff;">
                                <tbody>
                                @if(isset($interest2))
                                    <tr>
                                        <th>Year</th>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        <th>Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    @php
                                        $previous_amount_int1 = $amount;
                                        $previous_amount_int2 = $amount;
                                    @endphp
                                    @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                        @php
                                            //(AX69>=AW69,(1+BC69)*BB69*(((1+BC69)^(AZ69*12)-1)/BC69),(BE68*(1+BC69)^12))
                                            //
                                            if ($sip_period>=$i){
                                            $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                            }else{
                                            //(BE69*(1+BC70)^12)
                                            $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                            }
                                            if ($sip_period>=$i){
                                            $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                            }else{
                                            //(BE69*(1+BC70)^12)
                                            $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                @if($i<=$sip_period)
                                                    ₹ {{$amount?custome_money_format($amount):0}}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if($i<=$sip_period)
                                                    ₹ {{$amount?custome_money_format($amount*12):0}}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                            <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr>
                                        <th>Year</th>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    </tr>
                                    @php
                                        $previous_amount_int1 = $amount;
                                    @endphp
                                    @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                        @php
                                            //(AX69>=AW69,(1+BC69)*BB69*(((1+BC69)^(AZ69*12)-1)/BC69),(BE68*(1+BC69)^12))
                                            //
                                            if ($sip_period>=$i){
                                            $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                            }else{
                                            //(BE69*(1+BC70)^12)
                                            $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                            }

                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                @if($i<=$sip_period)
                                                    ₹ {{$amount?custome_money_format($amount):0}}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if($i<=$sip_period)
                                                    ₹ {{$amount?custome_money_format($amount*12):0}}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                            <p>*The above chart is approximate and for illustration purpose only</p>
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
                            <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection