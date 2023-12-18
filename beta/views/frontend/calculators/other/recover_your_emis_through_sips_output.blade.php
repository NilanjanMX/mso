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
                    url: "{{ route('frontend.RecoverYourEMIsThroughSIPs_output_save') }}",
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
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                	<a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)" target="_blank" class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.RecoverYourEMIsThroughSIPs_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <h5 class="mb-3">EMI Vs. SIP Planning @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h5>

                    <table class="table table-bordered text-center">
                        <tbody><tr>
                            <td>
                                <strong>Loan Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($loan_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Rate of Interest</strong>
                            </td>
                            <td>
                                {{$rate_of_interest?custome_money_format($rate_of_interest):0}} %
                            </td>
                        </tr>
                       <tr>
                            <td>
                                <strong>Tenure</strong>
                            </td>
                            <td>
                                {{$period?$period:0}} Years
                            </td>
                        </tr>
                        </tbody></table>
                    @php
                    $tenure=$period*12;
                    $roi=($rate_of_interest/100)/12;
                    $memi=($roi*$loan_amount)/(1-pow(1+$roi,-$tenure));
                    $ip=($memi*$tenure)-$loan_amount;
                    $tr=$loan_amount+$ip;
                    @endphp
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;">Monthly EMI</h1>
                    <h2 style="color: #131f55;font-size:22px;margin-bottom:20px;">₹ {{custome_money_format($memi)}}</h2>
                    
                    <!-- (0.0075*10000000)/(1-(1+0.0075)^(-300)) 75000/0.89371216618-->
                    <table class="table table-bordered text-center">
                    <tbody><tr>
                            <td>
                                <strong>Principal Repayment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($loan_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Interest Payment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($ip)}}
                            </td>
                        </tr>
                       <tr>
                            <td>
                                <strong>Total Repayment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($tr)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    
                    <h2 style="color: #131f55;font-size:22px;margin-bottom:20px;">Monthly SIP Required @ {{number_format($expected_return_sip,2)}}%</h2>
                    @php
                    $mrs=pow((1+$expected_return_sip/100),(1/12))-1;
                    $msr=($tr*$mrs)/((1+$mrs)*(pow(1+$mrs,$tenure)-1));
                    @endphp
                    <h2 style="color: #131f55;font-size:22px;margin-bottom:20px;">₹ {{custome_money_format($msr)}}</h2>

                    <p>If you do an SIP for ₹ {{custome_money_format($msr)}} ,ie., {{number_format($msr/$memi*100,2)}}% of the EMI amount, you will recover the full amount of EMI paid by you.</p>

                    * It is assumed that Rate of Interest on Loan Amount is compounded monthly.<br>
                    * It is assumed that EMI payment starts at the end of 1st month from the date of loan.<br>
                    * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                    * Returns are not guaranteed. The above is for illustration purpose only.<br><br>

                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)" target="_blank" class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.RecoverYourEMIsThroughSIPs_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>


@endsection
