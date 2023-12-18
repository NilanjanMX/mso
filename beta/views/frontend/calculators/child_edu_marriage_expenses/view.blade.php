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
                        url: "{{ route('frontend.childEducation_save') }}",
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
    @include('frontend.calculators.common.view_style')
@endsection
@section('content')
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
    <div class="banner styleApril">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div> --}}
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h1 class="midheading">Child {{$fund_requirement_purpose}} @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Expenses Calculation @endif</h1>
                            <div class="roundBorderHolder">
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
                            </div>
                                @if($fv_current_investment>$fv_fund_required1)
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    <tr>
                                        <td colspan="2"><h3 style="margin-bottom: 0;"><strong>You don't need any further investment for the above Goal!</strong></h3></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <h1 class="midheading">Available Investment Options:</h1>
                            <div class="roundBorderHolder">
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
                            </div>
                            @endif
                            
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center viewBelowBtn">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.lumsumInvestmentRequiredForTargetFutureValuePdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput" style="width: 320px;">Save & Merge with Sales Presenters</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.childEducation_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

    <div class="modal fade" id="mergeSalesPresentersOutput" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SALES PRESENTER SOFTCOPY SAVED LIST</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form target="_blank" action="{{route('frontend.childEducation_merge_download')}}" method="get">
                        <input type="hidden" name="save_file_id" value="{{$id}}">
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>List Name</th>
                                <th>Valid Till</th>
                            </tr>
                            @if(isset($savelists) && count($savelists)>0)
                                @foreach($savelists as $svlist)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="saved_sp_list_id[]" value="{{$svlist['id']}}">
                                        </td>
                                        <td>{{$svlist['created_at']->format('d/m/Y - h:i A')}}</td>
                                        <td>{{$svlist['title']}} ({{$svlist->softcopies->count()}} images)</td>
                                        <td>{{date('d/m/Y - h:i A',strtotime($svlist['validate_at']))}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">SUGGESTED PRESENTATION LIST</h5>
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th style="text-align: left">List Name</th>
                            </tr>
                            @if(isset($suggestedlists) && count($suggestedlists)>0)
                                @foreach($suggestedlists as $sglist)
                                    <tr>
                                        <td>
                                            <input type="radio" name="saved_list_id" value="{{$sglist['id']}}">
                                        </td>
                                        <td style="text-align: left" >{{$sglist['title']}} ({{$sglist->softcopies->count()}} images)</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">WHERE YOU WANT TO MERGE?</h5>
                        <table>
                            <tbody>
                            <tr>
                                <td style="text-align: left">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="before" name="mergeposition">Before
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="after" name="mergeposition" checked>After
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @if($permission['is_cover'])
                            <h5 class="modal-title">&nbsp;</h5>
                            <table>
                                <tbody>
                                <tr>
                                    <td style="text-align: left">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="1" name="is_cover" onchange="changeCover(1);">With Cover
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="0" name="is_cover"  onchange="changeCover(0);" checked>Without Cover
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                        <h5 class="modal-title">&nbsp;</h5>
                        <div id="pdf_title_line_id" style="display: none;">
                            <div class="form-group">
                                <input type="text" name="pdf_title_line1" class="form-control" id="pdf_title_line1" placeholder="PDF Title Line 1" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="pdf_title_line2" class="form-control" id="pdf_title_line2" placeholder="PDF Title Line 2" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_name" class="form-control" id="client_name" placeholder="Client Name" value="" maxlength="22">
                            </div>
                        </div>
                        <p></p>
                        <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary btn-round" >Merge & Download</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection

