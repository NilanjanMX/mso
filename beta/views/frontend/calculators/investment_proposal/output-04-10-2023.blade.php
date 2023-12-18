
@extends('layouts.frontend')
@section('js_after')
<script>

    
    $(document).delegate(".bounce-out", "click", function(){
        return confirm('All selections will be lost, continue?')
    });

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
                    url: "{{ route('frontend.investment_proposal_output_save') }}",
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
                    <h2 class="page-title">All-in-one Investment Proposal</h2>
                </div>
            </div>
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    <a href="{{route('frontend.investment_proposal_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>

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
                            <a href="{{route('frontend.investment_proposal_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <div class="outputTableHolder">
                        <h1 class="midheading">All-in-one Investment Proposal @if(isset($client_name) && !empty($client_name)) <br> For {{$client_name?$client_name:''}}  @else  @endif</h1>
                        
                        @if($lumpsum_checkbox || $sip_checkbox || $stp_checkbox || $swp_checkbox)
                            <h1 class="midheading">Mutual Fund Schemes</h1>
                        @endif
    
                        @if($lumpsum_checkbox)
                            @if(count($lumpsum_form_list))
                                <h1 class="midheading text-left">Lumpsum Investment</h1>
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">Asset Class</th>
                                            <th style="width: 22%;">Investment Amount</th>
                                            <th style="width: 22%;">Investment Period (Yrs)</th>
                                            <th style="width: 22%;">Assumed Return</th>
                                            <th style="width: 22%;">Expected Future&nbsp;Value</th> 
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($lumpsum_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td style="">{{isset($value['asset_class'])?$value['asset_class']:""}}</td>
                                                    <td>₹&nbsp;{{isset($value['investment_amount'])?custome_money_format($value['investment_amount']):""}}</td>
                                                    <td>{{isset($value['investment_period'])?$value['investment_period']:""}}</td>
                                                    <td>{{isset($value['assumed_rate_of_return'])?number_format((float)$value['assumed_rate_of_return'], 2, '.', ''):""}} %</td>
                                                    <td>₹&nbsp;{{isset($value['actual_end_value'])?custome_money_format($value['actual_end_value']):""}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                                <h1 class="midheading text-left">Suggested Schemes</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($lumpsum_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 30%;">Scheme</th>
                                            @if($lumpsum_category_checkbox)
                                                <th style="">Category</th>
                                            @endif
                                            <th style="width: 15%;">Investment Amount</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($lumpsum_table_list as $key => $value)
                                                <tr>
                                                    @if($lumpsum_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    @if($lumpsum_category_checkbox)
                                                        <td style="">{{$value['category']}}</td>
                                                    @endif
                                                    <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($sip_checkbox)
                            @if(count($sip_form_list))
                                <h1 class="midheading text-left">SIP Investment</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">Asset Class</th>
                                            <th style="width: 15%;">SIP Amount</th>
                                            <th style="width: 22%;">Frequency</th>
                                            <th style="width: 22%;">SIP Period</th>
                                            <th style="width: 22%;">Investment Period</th>
                                            <th style="width: 22%;">Assumed Return</th>
                                            <th style="width: 22%;">Total Investment</th>
                                            <th style="width: 22%;">Expected Future&nbsp;Value</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($sip_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td style="">{{isset($value['asset_class'])?$value['asset_class']:""}}</td>
                                                    <td>₹&nbsp;{{isset($value['sip_amount'])?custome_money_format($value['sip_amount']):""}}</td>
                                                    <td>{{isset($value['frequency'])?$value['frequency']:""}}</td>
                                                    <td>{{isset($value['sip_period'])?$value['sip_period']:""}}</td>
                                                    <td>{{isset($value['investment_period'])?$value['investment_period']:""}}</td>
                                                    <td>{{isset($value['assumed_rate_of_return'])?number_format((float)$value['assumed_rate_of_return'], 2, '.', ''):""}} %</td>
                                                    <td>₹&nbsp;{{isset($value['total_investment'])?custome_money_format($value['total_investment']):""}}</td>
                                                    <td>₹&nbsp;{{isset($value['expected_future_value'])?custome_money_format($value['expected_future_value']):""}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                                <h1 class="midheading text-left">Suggested Schemes</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($sip_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 30%;">Scheme</th>
                                            @if($sip_category_checkbox)
                                                <th style="">Category</th>
                                            @endif
                                            <th style="width: 15%;">SIP Amount</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($sip_table_list as $key => $value)
                                                <tr>
                                                    @if($sip_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    @if($sip_category_checkbox)
                                                        <td style="">{{$value['category']}}</td>
                                                    @endif
                                                    <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($stp_checkbox)
                            @if(count($stp_form_list))
                                <h1 class="midheading text-left">STP Investment</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th rowspan="2">Initial Investment</th>
                                            <th>Assumed Return</th>
                                            <th rowspan="2">Transfer Mode / Frequency</th>
                                            <th rowspan="2">No. of Frequency / Investment Period</th>
                                            <th rowspan="2">STP Amount</th> 
                                            <th rowspan="2">Expected Future Value</th> 
                                          </tr>
                                          <tr>
                                            <th>From Scheme / To Scheme</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($stp_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td>₹&nbsp;{{custome_money_format($value['initial_investment_amount'])}}</td>
                                                    <td style="">{{number_format((float)$value['from_scheme'], 2, '.', '')}} % / {{number_format((float)$value['to_scheme'], 2, '.', '')}} % </td>
                                                    <td>{{$value['transfer_mode_value']}} / {{$value['frequency']}}</td>
                                                    <td>{{$value['no_of_frequency']}} / {{$value['investment_period']}} Years</td>
                                                    <td>₹&nbsp;{{custome_money_format($value['stp_amount'])}}</td>
                                                    <td>₹&nbsp;{{custome_money_format($value['expected_future_value'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
    
                            @if(count($stp_table_list))
                                <h1 class="midheading text-left">Suggested Schemes</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($stp_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 22%;">From Scheme</th>
                                            <th style="width: 22%;">Initial Investment</th>
                                            <th style="width: 22%;">To Scheme</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($stp_table_list as $key => $value)
                                                <tr>
                                                    @if($stp_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    <td style="text-align: center">₹&nbsp;{{custome_money_format($value['investment'])}}</td>
                                                    <td style="">{{$value['equity_schemecode_name']}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($swp_checkbox)
                            @if(count($swp_form_list))
                                <div class="midheading text-left">SWP Investment</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">Total Investment(₹)</th>
                                            <th style="width: 22%;">Assumed Return</th>
                                            <th style="width: 22%;">SWP Frequency</th>
                                            <th style="width: 22%;">SWP Period</th>
                                            <th style="width: 22%;">SWP Amt(₹)</th>
                                            <th style="width: 22%;">Expected&nbsp;End Value(₹)</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($swp_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td>₹&nbsp;{{custome_money_format($value['total_investment_amount'])}}</td>
                                                    <td>{{number_format((float)$value['assumed_rate_of_return'], 2, '.', '')}} %</td>
                                                    <td>{{$value['frequency']}}</td>
                                                    <td>
                                                        {{$value['period_year']}} Yrs 
                                                        @if($value['period_month'] || $value['period_month'] !=0)
                                                            {{$value['period_month']}} Month
                                                        @endif
                                                    </td>
                                                    <td>
                                                        ₹&nbsp;
                                                        @if($value['type_amount'] == 2)
                                                            {{custome_money_format($value['in_amount_hide'])}}
                                                        @else
                                                            {{custome_money_format($value['in_amount'])}}
                                                        @endif
                                                    </td>
                                                    <td>₹&nbsp;{{custome_money_format($value['actual_end_value'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                                <div class="midheading text-left">Suggested Schemes</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($swp_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 30%;">Scheme</th>
                                            @if($swp_category_checkbox)
                                                <th style="">Category</th>
                                            @endif
                                            <th style="width: 15%;">Investment Amount</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($swp_table_list as $key => $value)
                                                <tr>
                                                    @if($swp_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    @if($swp_category_checkbox)
                                                        <td style="">{{$value['category']}}</td>
                                                    @endif
                                                    <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($non_mf_product_checkbox)
                            @if(count($non_mf_product_list))
                                <div class="midheading">Other Investment Schemes</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <?php if($non_mf_product_investor_checkbox){ ?>
                                                    <th style="width: 15%;">Investor</th>
                                                <?php } ?>
                                                    <th style="">Product</th>
                                                    <th style="">Scheme / Company</th>
                                                <?php if($non_mf_product_amount_checkbox){ ?>
                                                    <th style="">Amount</th>
                                                <?php } ?>
                                                <?php if($non_mf_product_remark_checkbox){ ?>
                                                    <th style="width: 20%;">Remarks</th>
                                                <?php } ?>
                                                    <!-- <th style="">Attach Scheme Detail</th> -->
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($non_mf_product_list as $key => $value)
                                                <tr>
                                                    <?php if($non_mf_product_investor_checkbox){ ?>
                                                        <td style="width: 15%;">{{isset($value['inverstor'])?$value['inverstor']:""}}</td>
                                                    <?php } ?>
                                                        <td style="">{{$value['product_name']}}</td>
                                                        <td style="">{{$value['company']}}</td>
                                                    <?php if($non_mf_product_amount_checkbox){ ?>
                                                        <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                    <?php } ?>
                                                    <?php if($non_mf_product_remark_checkbox){ ?>
                                                        <td style="">{{$value['remark']}}</td>
                                                    <?php } ?>
                                                        <!-- <td style="">{{($value['attach'])?"Yes":"No"}}</td> -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($insurance_product_checkbox)
                            @if(count($insurance_product_list))
                                <div class="midheading">Insurance Schemes</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                                    <th style="width: 15%;">Investor</th>
                                                <?php } ?>
                                                    <th style="width: 18%;">Product</th>
                                                    <th style="">Scheme / Company</th>
                                                    <th style="">Sum Assured</th>
                                                    <th style="">Annual Premium</th>
                                                <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                                    <th style="width: 20%;">Remarks</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($insurance_product_list as $key => $value)
                                                <tr>
                                                    <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                                        <td style="width: 15%;">{{isset($value['inverstor'])?$value['inverstor']:""}}</td>
                                                    <?php } ?>
                                                        <td style="width: 18%;">{{$value['product_type_name']}}</td>
                                                        <td style="">{{$value['company']}}</td>
                                                        <td style="text-align:center;"> ₹ {{custome_money_format($value['sum_assured'])}}</td>
                                                        <td>₹&nbsp;{{custome_money_format($value['annual_premium'])}}</td>                                        
                                                    <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                                        <td style="">{{$value['remark']}}</td>
                                                    <?php } ?>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($comment)
                            <div class="midheading">Comment</div>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td>{{$comment}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
    
                        <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                        
    
                        @if($performance_of_selected_mutual_fund)
                            <div class="midheading">Performance of Selected Mutual Fund Scheme</div>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%;">Scheme Name</th>
                                            <th style="width: 30%;">Category</th>
                                            <th style="width: 5%;">6 Month</th>
                                            <th style="width: 5%;">1 Year</th>
                                            <th style="width: 5%;">3 Year</th>
                                            <th style="width: 5%;">5 Year</th>
                                            <th style="width: 5%;">10 Year</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($scheme_data_list as $key => $value)
                                            @php
                                                $value['6MONTHRET'] = ($value['6MONTHRET'])?number_format((float)($value['6MONTHRET']), 2, '.', ''):"-";
                                                $value['1YEARRET'] = ($value['1YEARRET'])?number_format((float)($value['1YEARRET']), 2, '.', ''):"-";
                                                $value['3YEARRET'] = ($value['3YEARRET'])?number_format((float)($value['3YEARRET']), 2, '.', ''):"-";
                                                $value['5YEARRET'] = ($value['5YEARRET'])?number_format((float)($value['5YEARRET']), 2, '.', ''):"-";
                                                $value['10YEARRET'] = ($value['10YEARRET'])?number_format((float)($value['10YEARRET']), 2, '.', ''):"-";
                                            @endphp
                                            <tr>
                                                <td style="width: 35%;">{{$value['S_NAME']}}</td>
                                                <td style="width: 30%;">{{$value['CATEGORY']}}</td>
                                                <td style="width: 5%;">{{$value['6MONTHRET']}}</td>
                                                <td style="width: 5%;">{{$value['1YEARRET']}}</td>
                                                <td style="width: 5%;">{{$value['3YEARRET']}}</td>
                                                <td style="width: 5%;">{{$value['5YEARRET']}}</td>
                                                <td style="width: 5%;">{{$value['10YEARRET']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                        @include('frontend.calculators.suggested.output')


                    
                    </div>

                    
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        <a href="{{route('frontend.investment_proposal_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>

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
                                <a href="{{route('frontend.investment_proposal_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
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
        var base_url = "{{route('frontend.investment_proposal_output_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection

