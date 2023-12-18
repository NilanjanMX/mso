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
                    url: "{{ route('frontend.bank_fixed_deposit_vs_debt_fund_save') }}",
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
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.bank_fixed_deposit_vs_debt_fund_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <h5 class="mb-3">Bank FD vs Debt Mutual Fund Comparison @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h5>

    

                    <table class="table table-bordered text-center">
                        <tbody>
                         <tr>
                            <th style="height:45px;">
                                <strong>Particulars</strong>
                            </th>
                            <th style="height:45px;">
                                <strong>Fixed Deposit</strong>
                            </th>
                            <th style="height:45px;">
                                <strong>Debt Mutual Fund</strong>
                            </th>
                         </tr>

                        <tr>
                            <td style="text-align:left;">
                               Initial Investment
                            </td>
                            <td style="text-align:right;">
                               ₹ {{custome_money_format($initial_investment)}}
                            </td>
                            <td style="text-align:right;">
                               ₹ {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                               Investment Period
                            </td>
                            <td style="text-align:right;">
                               {{$period}} Years
                            </td>
                            <td style="text-align:right;">
                               {{$period}} Years
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                               Assumed Return
                            </td>
                            <td style="text-align:right;">
                               {{number_format($fixed_deposit,2)}} %
                            </td>
                            <td style="text-align:right;">
                               {{number_format($debt_fund,2)}} %
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                               Maturity / Redemption Amount
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u36=$initial_investment*pow((1+($fixed_deposit/100)),$period);
                               @endphp
                               ₹ {{custome_money_format($u36)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad36=$initial_investment*pow((1+($debt_fund/100)),$period);
                               @endphp
                               ₹ {{custome_money_format($ad36)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Interest Income / Capital Gain
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u37=$u36-$initial_investment;
                               @endphp
                               ₹ {{custome_money_format($u37)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad37=$ad36-$initial_investment;
                               @endphp
                               ₹ {{custome_money_format($ad37)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Assumed Indexation Rate
                            </td>
                            <td style="text-align:right;">
                               NA
                            </td>
                            <td style="text-align:right;">
                               {{number_format($assumed_inflation_rate_for_indexation,2)}}%
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                             Indexed Cost of Investment
                            </td>
                            <td style="text-align:right;">
                               ₹ {{custome_money_format($initial_investment)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               if($period<$for_period_upto)
                               {
                                  $ad39=$initial_investment;
                               }else{
                                  $ad39=$initial_investment*pow((1+($assumed_inflation_rate_for_indexation/100)),$period);
                               }
                               @endphp
                               ₹ {{custome_money_format($ad39)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Taxable Income
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u40=$u36-$initial_investment;
                               @endphp
                               ₹ {{custome_money_format($u40)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad40=$ad36-$ad39;
                               @endphp
                               ₹ {{custome_money_format($ad40)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Applicable Tax Rate
                            </td>
                            <td style="text-align:right;">
                               {{number_format($applicable_short_term_tax_rate,2)}}%
                            </td>
                            <td style="text-align:right;">
                               @php
                               if($period<$from_the_year-1)
                               {
                                  $ad41=$applicable_short_term_tax_rate;
                               }else{
                                  $ad41=$applicable_long_term_tax_rate;
                               }
                               @endphp
                               {{number_format($ad41,2)}}%
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Tax Payable
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u42=$u40*$applicable_short_term_tax_rate/100;
                               @endphp
                               ₹ {{custome_money_format($u42)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad42=$ad40*$ad41/100;
                               @endphp
                               ₹ {{custome_money_format($ad42)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Post-Tax Returns (Rs)
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u43=$u37-$u42;
                               @endphp
                               ₹ {{custome_money_format($u43)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad43=$ad37-$ad42;
                               @endphp
                               ₹ {{custome_money_format($ad43)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Post-Tax IRR (%)
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u44=(pow((($initial_investment+$u43)/$initial_investment),(1/$period))-1)*100;
                          
                               @endphp
                               {{number_format($u44,2)}}%
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad44=(pow((($initial_investment+$ad43)/$initial_investment),(1/$period))-1)*100;
                               @endphp
                               {{number_format($ad44,2)}}%
                            </td>
                        </tr>
                       
                    
                        </tbody>
                    </table>

                    <p>
                      *For simplicity in calculating LTCG, it is assumed that withdrawal from debt fund is made after the end of the year. It is assumed that in case of Fixed Deposit, the annual interest is re-invested at the same rate of interest and income tax is paid at the end of investment term. Mutual fund investments are subject to market risks, read all scheme related documents carefully. Returns are not guaranteed. The above is for illustration purpose only.<br>
                    </p>
                   
                    
                    <?php if(isset($benefit)){ 
                      $page_data = \App\Models\Calculator_note::where('category','debt_funds')->first();
                      if(!empty($page_data)){
                    ?>

                    <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">{{$page_data->name}}</h2>
                    {!!$page_data->description!!}

                    <?php }} ?>



                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.bank_fixed_deposit_vs_debt_fund_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
