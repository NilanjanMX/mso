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
                        url: "{{ route('frontend.recover_emis_through_sip_save') }}",
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
                    <h2 class="page-title">Recover EMI Through SIP</h2>
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
                        <a href="{{route('frontend.recover_emis_through_sip_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.recover_emis_through_sip_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h5 class="midheading">Lumpsum Investment Ready Reckoner @if(isset($clientname)) <br/> For {{$clientname?$clientname:''}} @endif</h5>
                        <div class="roundBorderHolder">

                        @if($enter_loan_details == 1)
                            @php 
                                $totalInterest = (($amount * $interest) / 100) * $period;
                                $ins = ($interest/100)/12;
                                $monthlyEmi = ($amount * $ins)/(1-pow((1+$ins),(-($period * 12))));
                                
                                $totalMonths = $period * 12;
                                $totalInterest = ($monthlyEmi * $totalMonths)-$amount;
                                $totalReplayment = $totalInterest + $amount;
                                $monthlyReturnOnSip = 0;
                                $monthlySipRequired = 0;
                                
                                if($expected_interest){
                                    $monthlyReturnOnSip = pow((1+($expected_interest/100)),(1/12))-1;
                                    $monthlySipRequired = ($totalReplayment * $monthlyReturnOnSip)/((1+$monthlyReturnOnSip)*(pow((1+$monthlyReturnOnSip),($totalMonths))-1));
                                }                        
                            @endphp
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Loan Amount</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($amount)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Rate of Interest</strong>
                                        </td>
                                        <td>
                                            {{$interest}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Loan Tenure</strong>
                                        </td>
                                        <td>
                                            {{$period}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Monthly EMI</h5>

                            <h5 style="
                                    margin: 0 auto;
                                    padding: 12px 10px;
                                    max-width: 237px;
                                    border: 1px solid #ccc;
                                ">
                                    ₹ {{custome_money_format($monthlyEmi)}}
                            </h5>
                            <br>
                            <table class="table table-bordered text-center" style="margin-bottom:20px;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: left;">Principal Repayment</td>
                                        <td>₹ {{custome_money_format($totalReplayment - $totalInterest)}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">Interest Repayment</td>
                                        <td>₹ {{custome_money_format($totalInterest)}}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">Total Repayment</td>
                                        <td>₹ {{custome_money_format($totalReplayment)}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <h5 class="mb-3 text-center">
                                Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %</h5>
                            <h5 style="
                                margin: 0 auto 15px auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                            ">
                                ₹  @if($monthlySipRequired)
                                        {{custome_money_format(round($monthlySipRequired))}}
                                    @else  
                                        N/A
                                    @endif
                            </h5>

                            <h6 class="mb-3 text-center">If you do an SIP for ₹ {{ custome_money_format($monthlySipRequired) }} ,ie., {{number_format((float)($monthlySipRequired/$monthlyEmi) * 100, 2, '.', '')}} % of the EMI amount, you will recover the full amount of EMI paid by you.</h6>
                        @else  
                            @php 
                                $monthlyEmi = $amount;
                
                                $totalMonths = $period * 12;
                                $totalReplayment = $monthlyEmi * $totalMonths;
                                
                                $monthlyReturnOnSip = 0;
                                $monthlySipRequired = 0;
                                if(!empty($expected_interest)){
                                    $monthlyReturnOnSip = pow((1+($expected_interest/100)),(1/12))-1;
                                    $monthlySipRequired = ($totalReplayment * $monthlyReturnOnSip)/((1+$monthlyReturnOnSip)*(pow((1+$monthlyReturnOnSip),($totalMonths))-1));
                                }
                            @endphp
                            <table class="table table-bordered" style="width: 80%; margin: 0 auto; margin-bottom: 30px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Monthly EMI</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($monthlyEmi)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Loan Tenure</strong>
                                        </td>
                                        <td>
                                            {{$period}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="mb-3 text-center">Total Repayment</h5>

                            <h5 style="
                                    margin: 0 auto;
                                    padding: 12px 10px;
                                    max-width: 237px;
                                    border: 1px solid #ccc;
                                ">
                                    ₹ {{custome_money_format($totalReplayment)}}
                            </h5>
                            <br>

                            <h5 class="mb-3 text-center">
                                Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %</h5>
                            <h5 style="
                                margin: 0 auto 15px auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                            ">
                                ₹  @if($monthlySipRequired)
                                        {{custome_money_format(round($monthlySipRequired))}}
                                    @else  
                                        N/A
                                    @endif
                            </h5>

                            <h6 class="mb-3 text-center">If you do an SIP for ₹ {{ custome_money_format($monthlySipRequired) }} ,ie., {{ number_format((($monthlySipRequired/$monthlyEmi) * 100),2)}} % of the EMI amount, you will recover the full amount of EMI paid by you.</h6>
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
                            <a href="{{route('frontend.recover_emis_through_sip_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.recover_emis_through_sip_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.recover_emis_through_sip_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
