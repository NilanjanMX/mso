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
                        url: "{{ route('frontend.termInsuranceSIPgoalBaseOutputSave') }}",
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
        //Number of Months R9*12
        $number_of_months = $term_insurance_period*12;
         //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return2 = (1+$rate_of_return/100)**(1/12)-1;
         //Monthly SIP Amount (R7*AV28)/((1+AV28)*((1+AV28)^(AV27)-1))
        $monthly_sip_amount = ($goal_amount*$rate_of_return2)/((1+$rate_of_return2)*((1+$rate_of_return2)**($number_of_months)-1));

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
                        <a href="{{route('frontend.termInsuranceSIPgoalBaseOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Term Insurance + SIP (Goal Based) @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Current Age</strong>
                            </td>
                            <td>
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Insurance / Goal Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($goal_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Insurance Period</strong>
                            </td>
                            <td>
                                {{$term_insurance_period?$term_insurance_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Insurance Annual Premium</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($term_insurance_annual_premium)}}
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">
                        Monthly SIP Required <br>
                        To Achieve Goal @ {{number_format($rate_of_return, 2, '.', '')}} %
                    </h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                ₹ {{custome_money_format($monthly_sip_amount)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p>
                        If you take Term Cover of<b> ₹ {{custome_money_format($goal_amount)}}</b> and do Monthly SIP of  <b>₹ {{custome_money_format($monthly_sip_amount)}}</b> you may be
                        assured of minimum payout of <b>₹ {{custome_money_format($goal_amount)}}</b> either on survival at age <b>{{$term_insurance_period+$current_age}}</b> or unfortunate event of death, subject to fund performance at Assumed rate of return mentioned herewith.
                    </p>
                    <p style="text-align: left">
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        * Returns are not guaranteed. The above is for illustration purpose only.
                    </p>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Yearwise Projected Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <th style="vertical-align: middle;">Age</th>
                            <th style="vertical-align: middle;width: 20%;">Life Cover</th>
                            <th>Annual Investment (Insurance Premium + SIP)</th>
                            <th>Year End SIP Value @ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                            <th>In case of Death<br> (Life Cover + SIP Value)</th>
                        </tr>
                        @for($i=1;$i<=$term_insurance_period;$i++)
                            @php
                                //Annual Investment (Insurance Premium + SIP) AS59+AT59*12
                                $annual_investment = $term_insurance_annual_premium+$monthly_sip_amount*12;
                                //Year End Value  (1+AW59)*AT59*(((1+AW59)^(AV59*12)-1)/AW59)
                                $year_end_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($i*12)-1)/$rate_of_return2);
                                $current_age++;
                            @endphp
                            <tr>
                                <td>{{$current_age}}</td>
                                <td>₹ {{custome_money_format($goal_amount)}}</td>
                                <td>₹ {{custome_money_format($annual_investment)}}</td>
                                <td>₹ {{custome_money_format($year_end_value)}}</td>
                                <td>₹ {{custome_money_format($goal_amount+$year_end_value)}}</td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>

                    <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.termInsuranceSIPgoalBaseOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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