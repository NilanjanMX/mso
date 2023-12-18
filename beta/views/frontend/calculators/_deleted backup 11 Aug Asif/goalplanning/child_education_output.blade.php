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
                        url: "{{ route('frontend.childEducationOutputSave') }}",
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
        $child_name = $child_name;
        $age = $current_age;
        $fund_requirement_purpose = $fund_requirement_purpose;
        $age1 = isset($fund_required_age) ? $fund_required_age : 0;
        $amount1 = isset($fund_required_amount) ? $fund_required_amount : 0;
        
        if(empty($investment_amount))
        { 
            $investment_amount =  0;
        }else{
            $investment_amount =  $investment_amount;
        }
        
        if(empty($return_rate))
        { 
            $return_rate =  0;
        }else{
            $return_rate =  $return_rate;
        }
        
        
        $inflation_rate = $inflation_rate;
        $return_rate_1 = $return_rate_1;
        $monthly_return_rate_1 = (1+$return_rate_1/100)**(1/12)-1;
        $period = isset($period) ? $period : 0;

        //Age 1
        $num_of_years1 = $age1 - $age;
        $total_number_of_months1 = $num_of_years1 * 12;

        $fv_fund_required1 = $amount1*(1+$inflation_rate/100)**$num_of_years1;
        
        $fv_current_investment = $investment_amount*(1+$return_rate/100)**$num_of_years1;
       

        $balance_required = $fv_fund_required1 - $fv_current_investment;

        $lumpsum_investment_required_1 = $balance_required / ((1+($return_rate_1/100))**($num_of_years1));


        //5 years
        $balance_after_5_years_opt1 = ( $num_of_years1 < 5 ) ? "NA" : $balance_required / ((1+($return_rate_1/100))**( $num_of_years1 - 5 ));

        $required_sip_for_5_years_opt1 = ($balance_after_5_years_opt1 == "NA") ? "NA" : ($balance_after_5_years_opt1*$monthly_return_rate_1)/((1+$monthly_return_rate_1)*(((1+$monthly_return_rate_1)**60) - 1));


        //10 years
        $balance_after_10_years_opt1 = ( $num_of_years1 < 10 ) ? "NA" : $balance_required / ((1+($return_rate_1/100))**( $num_of_years1 -10 ));

        $required_sip_for_10_years_opt1 = ($balance_after_10_years_opt1 == "NA") ? "NA" : ($balance_after_10_years_opt1*$monthly_return_rate_1)/((1+$monthly_return_rate_1)*(((1+$monthly_return_rate_1)**120) - 1));


        //Till end
        $required_sip_till_end_opt1 = ($balance_required*$monthly_return_rate_1)/((1+$monthly_return_rate_1)*(((1+$monthly_return_rate_1)**($total_number_of_months1))-1));


    if (isset($return_rate_2)){
            $return_rate_2 = $return_rate_2;
            $monthly_return_rate_2 = (1+$return_rate_2/100)**(1/12)-1;

            $lumpsum_investment_required_2 = $balance_required / ((1+($return_rate_2/100))**($num_of_years1));
            $balance_after_5_years_opt2 = ( $num_of_years1 < 5 ) ? "NA" : $balance_required / ((1+($return_rate_2/100))**( $num_of_years1 - 5));
            $required_sip_for_5_years_opt2 = ($balance_after_5_years_opt2 == "NA") ? "NA" : ($balance_after_5_years_opt2*$monthly_return_rate_2)/((1+$monthly_return_rate_2)*(((1+$monthly_return_rate_2)**60) - 1));
            $balance_after_10_years_opt2 = ( $num_of_years1 < 10 ) ? "NA" : $balance_required / ((1+($return_rate_2/100))**( $num_of_years1 - 10 ));
            $required_sip_for_10_years_opt2 = ($balance_after_10_years_opt2 == "NA") ? "NA" : ($balance_after_10_years_opt2*$monthly_return_rate_2)/((1+$monthly_return_rate_2)*(((1+$monthly_return_rate_2)**120) - 1));
            $required_sip_till_end_opt2 = ($balance_required*$monthly_return_rate_2)/((1+$monthly_return_rate_2)*(((1+$monthly_return_rate_2)**($total_number_of_months1))-1));
        }
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
                        <a href="{{route('frontend.childEducationOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Child {{$fund_requirement_purpose}} @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Expenses Calculation @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Child Name</strong>
                            </td>
                            <td>
                                {{$child_name}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Child Age</strong>
                            </td>
                            <td>
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Fund Required at Age</strong>
                            </td>
                            <td>
                                {{$fund_required_age?$fund_required_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Fund Required</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($fund_required_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Current Investment</strong>
                            </td>
                            <td>
                                <?php
                                if($investment_amount=='0')
                                {
                                    echo "Nil";
                                }else{ ?>
                                ₹ {{custome_money_format($investment_amount)}}
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Rate of Return (CI)</strong>
                            </td>
                            <td>
                                {{$return_rate?number_format($return_rate, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Expected Inflation Rate</strong>
                            </td>
                            <td>
                                {{$inflation_rate?number_format($inflation_rate, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Return @if(isset($return_rate_2)) (Scenario 1) @endif</strong>
                            </td>
                            <td>
                                {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        @if(isset($return_rate_2))
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Return (Scenario 2)</strong>
                            </td>
                            <td>
                                {{$return_rate_1?number_format($return_rate_2, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Inflated Cost of Funds Required</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($fv_fund_required1)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Expected FV of Current Investment</strong>
                            </td>
                            <td>
                                {{ ($investment_amount > 0) ? "₹" . custome_money_format($fv_current_investment) : "NA"  }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Balance Fund Required</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($balance_required)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    @if($fv_current_investment>$fv_fund_required1)
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td colspan="2"><h3 style="margin-bottom: 0;"><strong>You don't need any further investment for the above Goal!</strong></h3></td>
                            </tr>
                            </tbody>
                        </table>
                    @else
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Available Investment Options:</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($return_rate_2))
                            <tr>
                                <td>
                                   <strong>Investment Option</strong>
                                </td>
                                <td>
                                    <strong>Option 1 @ {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %</strong>
                                </td>
                                <td>
                                    <strong>Option 2 @ {{$return_rate_2?number_format($return_rate_2, 2, '.', ''):0}} %</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age {{$fund_required_age}}</td>
                                <td>₹ {{($required_sip_till_end_opt1=='NA')?$required_sip_till_end_opt1:custome_money_format($required_sip_till_end_opt1)}}</td>
                                <td>₹ {{($required_sip_till_end_opt2=='NA')?$required_sip_till_end_opt2:custome_money_format($required_sip_till_end_opt2)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹ {{$required_sip_for_5_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_5_years_opt1)}}</td>
                                <td>₹ {{$required_sip_for_5_years_opt2 == "NA" ? "NA" : custome_money_format($required_sip_for_5_years_opt2)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹ {{$required_sip_for_10_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_10_years_opt1)}}</td>
                                <td>₹ {{$required_sip_for_10_years_opt2 == "NA" ? "NA" : custome_money_format($required_sip_for_10_years_opt2)}}</td>
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹ {{($lumpsum_investment_required_1=='NA')?$lumpsum_investment_required_1:custome_money_format($lumpsum_investment_required_1)}}</td>
                                <td>₹ {{($lumpsum_investment_required_2=='NA')?$lumpsum_investment_required_2:custome_money_format($lumpsum_investment_required_2)}}</td>
                            </tr>
                         @else
                            <tr>
                                <td>
                                    <strong>Investment Option</strong>
                                </td>
                                <td>
                                    <strong> Amount Required @ {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age {{$fund_required_age}}</td>
                                <td>₹ {{($required_sip_till_end_opt1=='NA')?$required_sip_till_end_opt1:custome_money_format($required_sip_till_end_opt1)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹ {{$required_sip_for_5_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_5_years_opt1)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹ {{$required_sip_for_10_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_10_years_opt1)}}</td>
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹ {{($lumpsum_investment_required_1=='NA')?$lumpsum_investment_required_1:custome_money_format($lumpsum_investment_required_1)}}</td>
                            </tr>
                         @endif
                        </tbody>
                    </table>
                    @endif
                    <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_output')
                    
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>

                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.childEducationOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
