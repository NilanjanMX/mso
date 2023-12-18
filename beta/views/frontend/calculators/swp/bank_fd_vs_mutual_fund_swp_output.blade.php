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
                    url: "{{ route('frontend.bank_fd_vs_mutual_fund_swp_save') }}",
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
    .fckDesign table {
        margin: 0 auto;
        margin-bottom: 20px;
    }
    .fckDesign table td, .fckDesign table th {
        padding: 4px 5px;
        border: 1px solid #dee2e6;
        vertical-align: top;
    }
</style>
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
                        <a href="{{route('frontend.bank_fd_vs_mutual_fund_swp_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <h5 class="mb-3">Bank FD vs Mutual Fund SWP Comparison @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h5>

    

                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Initial Investment</strong>
                            </td>
                            <td colspan="2">₹ {{custome_money_format($initial_investment)}}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Investment Period</strong>
                            </td>
                            <td colspan="2">{{$period?$period:0}} Years</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Expected Indexation Rate</strong>
                            </td>
                            <td colspan="2">{{number_format($assumed_inflation_rate_for_indexation,2)}}%</td>
                        </tr>
                         <tr>
                            <td>
                                <strong>Applicable Tax Slab</strong>
                            </td>
                            <td colspan="2">{{number_format($applicable_short_term_tax_rate,2)}}%</td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Applicable LTCG Tax Rate</strong>
                            </td>
                            <td colspan="2">{{number_format($applicable_long_term_tax_rate,2)}}%</td>
                        </tr>

                        <tr>
                            <td>
                                <strong>LTCG Tax Rate Applicable After</strong>
                            </td>
                            <td colspan="2">{{$for_period_upto}} Years</td>
                        </tr>
                       
                        
                        </tbody>
                    </table>
                   
                   

                    <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">{{$withdrawal_mode}} SWP Amount</h2>
                    @php

                    if($withdrawal_mode=='Yearly')
                    {
                      $cval=1;
                    }elseif($withdrawal_mode=='Half-Yearly')
                    {
                      $cval=2;
                    }elseif($withdrawal_mode=='Quarterly')
                    {
                      $cval=4;
                    }elseif($withdrawal_mode=='Monthly')
                    {
                      $cval=12;
                    }

                    if($withdrawal_mode=='Yearly')
                    {
                      $div=12;
                    }elseif($withdrawal_mode=='Half-Yearly')
                    {
                      $div=6;
                    }elseif($withdrawal_mode=='Quarterly')
                    {
                      $div=3;
                    }elseif($withdrawal_mode=='Monthly')
                    {
                      $div=1;
                    }

                    $t8=$initial_investment;
                    $t11=$fixed_deposit/100;
                    $t12=$debt_fund/100;
                    $t14=$assumed_inflation_rate_for_indexation/100;
                    $av33=pow((1+$t11),(1/$cval))-1;
                    $av34=pow((1+$t12),(1/$cval))-1;
                    $av35=$t8*$av33;
                    $av36=$t8*$av34;
                    $bg60=1;
                    @endphp
                   <table class="table table-bordered text-center">
                     <tbody>
                        <tr>
                            <td>
                                <strong>Bank FD @ {{number_format($fixed_deposit,2)}}%</strong>
                            </td>
                            <td>
                                <strong>Debt Fund @ {{number_format($debt_fund,2)}}%</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>₹ {{custome_money_format($av35)}}</strong>
                            </td>
                            <td>
                                <strong>₹ {{custome_money_format($av36)}}</strong>
                            </td>
                        </tr>
                     </tbody>
                    </table>
                    @php
                      $iHtml = "";
                      $yr=1;
                      $ival=$period*12;
                      $x=1;
                      $xx=1;
                      $sm=1;
                      $wd=0;
                      $at=0;
                      $nh=0;
                      $wd2=0;
                      $at2=0;
                      $nh2=0;
                      $last_eq=0;
                      $rwd=0;
                      $rat=0;
                      $rnh=0;
                      $rwd2=0;
                      $rat2=0;
                      $rnh2=0;
                      


                      $av34_n = pow((1+$t12),(1/12))-1;
                      $av36_n = $t8*$av34_n;
                      if($period < $for_period_upto){
                        $bs97 = ($initial_investment)-($av36/$av34)*(1-(1+$av34)**(-$ival));
                        $bu97 = $initial_investment - $bs97;
                        $debt_tax_payable = $bu97*$applicable_short_term_tax_rate/100;

                        $bs97_n = ($initial_investment)-($av36_n/$av34_n)*(1-(1+$av34_n)**(-$ival));
                        $bu97_n = $initial_investment - $bs97_n;
                        $debt_tax_payable_n = $bu97_n*$applicable_short_term_tax_rate/100;
                      }else{
                        //echo $assumed_inflation_rate_for_indexation;
                        $bs97 = ($initial_investment)-($av36/$av34)*(1-(1+$av34)**(-$ival));
                        $bt97 = round($bs97)*(1+$t14)**($period);
                        $bu97 = $initial_investment - $bt97;
                        $debt_tax_payable = $bu97*$applicable_long_term_tax_rate/100;

                        $bs97_n = ($initial_investment)-($av36_n/$av34_n)*(1-(1+$av34_n)**(-$ival));
                        $bt97_n = round($bs97_n)*(1+$t14)**($period);
                        $bu97_n = $initial_investment - $bt97_n;
                        $debt_tax_payable_n = $bu97_n*$applicable_long_term_tax_rate/100;
                      }

                      //exit;
                      @endphp

                      <?php for($i=1;$i<=$ival;$i=$i+$div){ ?>
                        @php 
                        $as60=$av33;
                        $at60=$t8;
                        $au60=$as60*$at60;
                        $av60=$au60;
                        $aw60=$applicable_short_term_tax_rate/100;
                        $ax60=$av60*$aw60;
                        $ay60=$av60-$ax60;

                        $az60=$for_period_upto;
                        $ba60=$i;

                        if($x>$cval) {
                           $bg60=$bg60+1;
                           if($withdrawal_mode=='Yearly')
                           {
                            $ba60=$yr;
                           }else{
                            $ba60=$xx;
                           }
                           $x=1;
                           $x++;
                        }else{
                           $bg60=$bg60;
                           if($withdrawal_mode=='Yearly')
                           {
                            $ba60=$bg60;
                           }else{
                            $ba60=$xx;
                           }
                           $x++;
                        }

                        $bb60=$t8;
                        $bc60=$av34;
                        $bd60=$bb60*$bc60;
                        $be60=$bd60;
                        $bf60=$be60/pow((1+$bc60),$ba60);

                       

                        $bh60=pow((1+$t14),(1/$cval))-1;
                        $bi60=$bf60*pow((1+$bh60),($bg60-1));

                        if($bg60<=$az60)
                        {
                            $bj60=$be60-$bf60;
                        }else{
                            $bj60=$be60-$bi60;
                        }

                        $bk60=$applicable_short_term_tax_rate/100;
                        $bl60=$applicable_long_term_tax_rate/100;

                        if($bg60<=$az60)
                        {
                              $bm60=$bj60*$bk60;
                        }else{
                              $bm60=$bj60*$bl60;
                        }

                        $bn60=$be60-$bm60;
                        
                        @endphp

                      @php 
                      if($sm==$cval) {
                         $wd+=$au60;
                         $at+=$ax60;
                         $nh+=$ay60;
                         $wd2+=$be60;
                         $at2+=$bm60;
                         $nh2+=$bn60;
                         $rat2=$rat2+$at2;
                         //echo "<br>";
                         $sm=1;
                        @endphp
                        <?php if($period==$yr){ ?>
                          <?php 
                          $last_eq=$last_eq+($at-$at2); 
                          $iHtml = $iHtml."<tr>
                            <td>
                                ".$yr."
                            </td>
                            <td>
                               ".custome_money_format($wd)."
                            </td>
                            <td>
                               ".custome_money_format($at)."
                            </td>
                            <td>
                               ".custome_money_format($nh)."
                            </td>
                             <td>
                               ".custome_money_format($x)."
                            </td>
                            <td>
                               ".custome_money_format($at2)."
                            </td>
                            <td>
                               ".custome_money_format($nh2)."
                            </td>
                            <td>
                                ".custome_money_format(($last_eq))."
                            </td>
                          </tr>";
                          // echo $iHtml; exit;
                          ?>
                          <?php 
                            $rwd=$wd;
                            $rat=$at;
                            $rnh=$nh;
                            $rwd2=$wd2;
                          ?>
                          <?php 
                          $bal_inv_cost=($at60)-($be60/$bc60)*(1-pow((1+$bc60),(-$yr*$cval)));
                           $index_cost=$bal_inv_cost*pow((1+$assumed_inflation_rate_for_indexation/100),($yr));
                           
                           if($period<=($for_period_upto-1))
                           {
                              $taxable_capital_gain=$initial_investment-$bal_inv_cost;
                              $tax_amount=$taxable_capital_gain*$applicable_short_term_tax_rate/100;
                           }elseif($period>($for_period_upto-1))
                           {
                              $taxable_capital_gain=$initial_investment-$index_cost;
                              $tax_amount=$taxable_capital_gain*$applicable_long_term_tax_rate/100;
                           }
                           
                           $eq2=$tax_amount+$at2;
                          $last_eq=$last_eq-$debt_tax_payable_n; 
                          $rnh2=$last_eq+($at-$eq2); 

                          $iHtml = $iHtml."<tr>
                            <td>
                                Final Redemption
                            </td>
                            <td>
                               ".custome_money_format($initial_investment)."
                            </td>
                            <td>
                               0
                            </td>
                            <td>
                               ".custome_money_format($initial_investment)."
                            </td>
                             <td>
                               ".custome_money_format($initial_investment)."
                            </td>
                            <td>
                               ".custome_money_format($debt_tax_payable_n)."
                            </td>
                            <td>
                               ".custome_money_format(($initial_investment-$debt_tax_payable_n))."
                            </td>
                            <td>
                                ".custome_money_format(($last_eq))."
                            </td>
                          </tr>";
                          ?>
                          

                        <?php }else{ ?>
                          <?php 
                            $last_eq=$last_eq+($at-$at2); 
                            $iHtml = $iHtml."<tr>
                            <td>
                                ".$yr."
                            </td>
                            <td>
                               ".custome_money_format($wd)."
                            </td>
                            <td>
                               ".custome_money_format($at)."
                            </td>
                            <td>
                               ".custome_money_format($nh)."
                            </td>
                             <td>
                               ".custome_money_format($wd2)."
                            </td>
                            <td>
                               ".custome_money_format($at2)."
                            </td>
                            <td>
                               ".custome_money_format($nh2)."
                            </td>
                            <td>
                                ".custome_money_format(($last_eq))."
                            </td>
                          </tr>

                            ";
                          ?>
                        <?php } ?>
                      @php
                      $wd=0;
                      $at=0;
                      $nh=0;
                      $wd2=0;
                      $at2=0;
                      $nh2=0;
                      $yr++;
                      }else{
                             $wd+=$au60;
                             $at+=$ax60;
                             $nh+=$ay60;
                             $wd2+=$be60;
                             $at2+=$bm60;
                             $nh2+=$bn60;
                             $sm++;
                       }
                      @endphp

                      <?php $xx++; }

                      // echo $iHtml;

                      //exit; ?>
                      <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Particulars</strong>
                            </td>
                            <td>
                                <strong>Bank FD</strong>
                            </td>
                            <td>
                                <strong>Debt Fund</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Total Withdrawal & Redemption</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($initial_investment+$rwd*$period)}}
                            </td>
                            <td>
                                ₹ {{custome_money_format($initial_investment+$rwd2*$period)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Tax Payable</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($rat*$period)}}
                            </td>
                            <td>
                                ₹ {{custome_money_format($rat2+$debt_tax_payable_n)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Post Tax Receipt</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format(($initial_investment+$rwd*$period) - $rat*$period)}}
                            </td>
                            <td>
                                ₹ {{custome_money_format(($initial_investment+$rwd2*$period) - ($rat2+$debt_tax_payable_n))}}
                            </td>
                        </tr>
                        </tbody>
                    </table>


                    <p>
                      * It is assumed that Bank FD Interest and SWP Withdrawal Amount is received on the last day of the month. Mutual fund investments are subject to market risks, read all scheme related documents carefully. Returns are not guaranteed. The above is for illustration purpose only.<br>
                    </p>

                    <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">Annual Tax & Post-Tax Annual Withdrawal</h2>
                     <table class="table table-bordered text-center">
                     <tbody>
                        <tr>
                            <td rowspan="2">
                               <strong>Year</strong>
                            </td>
                            <td colspan="3">
                                <strong>Fixed Deposit</strong>
                            </td>
                            <td colspan="3">
                                <strong>Debt Fund</strong>
                            </td>
                             <td rowspan="2">
                               <strong>Cumulative Tax <br> Saved in Debt Fund</strong>
                            </td>
                        </tr>
                        <tr>
                           
                            <td>
                                <strong>Total Withdrawal</strong>
                            </td>
                            <td>
                                <strong>Annual Tax</strong>
                            </td>
                            <td>
                                <strong>Net In Hand</strong>
                            </td>
                            <td>
                                <strong>Total Withdrawal</strong>
                            </td>
                            <td>
                                <strong>Annual Tax</strong>
                            </td>
                            <td>
                                <strong>Net In Hand</strong>
                            </td>
                        </tr>
                        {!!$iHtml!!}
                    </tbody>
                    </table>
                    <p>*The above is for illustration purpose only. Actual figures may vary depending on market.</p>
                    
                    <div class="fckDesign">
                        <?php if(isset($benefit)){ 
                          $page_data = \App\Models\Calculator_note::where('category','debt_funds')->first();
                          if(!empty($page_data)){
                        ?>
    
                        <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">{{$page_data->name}}</h2>
                        
                          {!!$page_data->description!!}
                       
                        <?php }} ?>
    
    
    
                        <?php if(isset($tax_calculation)){ 
                          $page_data = \App\Models\Calculator_note::where('category','bank_fd_vs_mutual_fund_swp_display_tax_calculation')->first();
                          if(!empty($page_data)){
                        ?>
    
                        <h2 style="color: #131f55;font-size:22px;margin-bottom:5px;">{{$page_data->name}}</h2>
                        
                          {!!$page_data->description!!}
                       
                        <?php }} ?>
    
    
    
                        @include('frontend.calculators.suggested.output')
                    </div>
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>

                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.bank_fd_vs_mutual_fund_swp_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
