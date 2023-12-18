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
                        url: "{{ route('frontend.sipGoalPlanningReadyRecoknerOutputSave') }}",
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
    //(1+D34)^(1/12)-1
    $return1 = (pow((1+$interest1/100),(1/12))-1);
    // (AT35*BD35)/((1+BD35)*((1+BD35)^(AY35*12)-1))/1000
    //$price1 = ((1+$return1)*$amount*((pow((1+$return1),($period1*12))-1)/$return1));
    $price1 = ($amount*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period1*12))-1))/1000;
    @endphp
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.sipGoalPlanningReadyRecoknerOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">SIP Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h5>
                    <table class="table table-bordered text-left">
                        <tbody><tr>
                            <td>
                                <strong> Target Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($amount)}}
                            </td>
                        </tr>
                        </tbody></table>
                    <h5 class="text-center">Monthly SIP Required</h5>
                    <table class="table table-bordered table-striped text-center" style="background: #fff;">
                        <tbody>

                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="5"><strong>SIP Period (Years)</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Rate of Return</strong></td>
                                <td><strong>{{$period1?$period1:''}}</strong></td>
                                @php if($period2!='0'){ @endphp
                                <td><strong>{{$period2?$period2:''}}</strong></td>
                                @php } @endphp
                                @php if($period3!='0'){ @endphp
                                <td><strong>{{$period3?$period3:''}}</strong></td>
                                @php } @endphp
                                @php if($period4!='0'){ @endphp
                                <td><strong>{{$period4?$period4:''}}</strong></td>
                                @php } @endphp
                                @php if($period5!='0'){ @endphp
                                <td><strong>{{$period5?$period5:''}}</strong></td>
                                @php } @endphp
                            </tr>
                            <tr>
                                <td>
                                    <strong>{{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                                </td>
                                @php if($period2!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period3!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period4!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period5!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                            </tr>
                            @php if($interest2!='0'){ @endphp
                            <tr>
                                <td>
                                    <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                                </td>
                                @php if($period2!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period3!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period4!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period5!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                            </tr>
                            @php } @endphp
                            @php if($interest3!='0'){ @endphp
                            <tr>
                                <td>
                                    <strong>{{$interest3?number_format((float)$interest3, 2, '.', ''):0}} %</strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                                </td>
                                @php if($period2!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period3!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period4!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                                @php if($period5!='0'){ @endphp
                                <td>
                                    <strong>₹ {{custome_money_format(($amount*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                                </td>
                                @php } @endphp
                            </tr>
                            @php } @endphp
                            <!-- <tr>
                                <td colspan="6" style="text-align: right"><strong>(₹ in Thousands)</strong></td>
                            </tr> -->

                        </tbody>
                    </table>
                    <p>
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        *Returns are not guaranteed. The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}
                    </p>
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.sipGoalPlanningReadyRecoknerOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>
    @include('frontend.calculators.modal')

@endsection
