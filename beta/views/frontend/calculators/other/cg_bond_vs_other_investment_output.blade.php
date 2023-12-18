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
                    url: "{{ route('frontend.CG_Bond_vs_Other_Investment_save') }}",
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
                        <a href="{{route('frontend.CG_Bond_vs_Other_Investment_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <h5 class="mb-3">Capital Gains Bond vs. Other Investment Planning @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h5>

                    <h6>Computation of Maturity Amount and Post-Tax IRR</h6>

                    @php
                    $t8=$capital_gain_amount;
                    $t9=$ltcg_tax_rate;
                    $l45=$t8;
                    $l46=0;
                    $t11=$interest_cg_bond_scheme;
                    $t12=$expected_indexation_rate;
                    $t13=$applicable_income_tax_slab;
                    $t10=$period;
                    $l48=$t11/100;
                    @endphp

                    <table class="table table-bordered text-left">
                        <tbody><tr>
                            <td style="width: 60%;">
                                <strong>Particulars</strong>
                            </td>
                            <td style="width: 40%;text-align:center;">
                               <strong>54EC Bond</strong>
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>Fixed Deposit</strong>
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>Debt Fund</strong>
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>Equity Fund</strong>
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>{{$product_name}}</strong>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Capital Gain Amount
                            </td>
                            <td style="text-align:center;">
                            {{custome_money_format($t8)}}
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $s45=$t8;
                               echo custome_money_format($s45);
                               @endphp

                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $z45=$t8;
                               echo custome_money_format($z45);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $ag45=$t8;
                               echo custome_money_format($ag45);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $an45=$t8;
                               echo custome_money_format($an45);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                LTCG Tax
                            </td>
                            <td style="text-align:center;">
                                0
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $s46=$t8*($t9/100);
                               echo custome_money_format($s46);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $z46=$t8*($t9/100);
                               echo custome_money_format($z46);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $ag46=$t8*($t9/100);
                               echo custome_money_format($ag46);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $an46=$t8*($t9/100);
                               echo custome_money_format($an46);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Net Investment
                            </td>
                            <td style="text-align:center;">
                               @php 
                               $l47=$l45-$l46;
                               echo custome_money_format($l47);
                               @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $s47=$s45-$s46;
                               echo custome_money_format($s47);
                               @endphp
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $z47=$z45-$z46;
                               echo custome_money_format($z47);
                               @endphp
                            </td>
                            <?php } ?>
                             <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $ag47=$ag45-$ag46;
                               echo custome_money_format($ag47);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $an47=$an45-$an46;
                               echo custome_money_format($an47);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Assumed Return
                            </td>
                            <td style="text-align:center;">
                                {{number_format($t11,2)}} %
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $s48=$fixed_deposit_expected_return;
                               echo number_format($s48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $z48=$debt_fund_expected_return;
                               echo number_format($z48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $ag48=$equity_fund_expected_return;
                               echo number_format($ag48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                               @php 
                               $an48=$other_expected_return;
                               echo number_format($an48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Investment Period (Yrs)
                            </td>
                            <td style="text-align:center;">
                                {{$l49=$t10}}
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               {{$s49=$t10}}
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               {{$z49=$t10}}
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               {{$ag49=$t10}}
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                {{$an49=$t10}}
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Indexation Benefit
                            </td>
                            <td style="text-align:center;">
                                No
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               {{$s50=ucfirst($fixed_deposit_indexation_benefit)}}
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               {{$z50=ucfirst($debt_fund_indexation_benefit)}}
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               {{$ag50=ucfirst($equity_fund_indexation_benefit)}}
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                {{$an50=ucfirst($other_indexation_benefit)}}
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Maturity Amount
                            </td>
                            <td style="text-align:center;">
                            @php
                                $x1=pow(1+$l48,$l49);
                                $l51=$l47*$x1;
                                echo custome_money_format($l51);
                            @endphp
                            </td style="text-align:center;">
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                            @php
                                $s51=$s47*pow(1+$s48/100,$s49);
                                echo custome_money_format($s51);
                            @endphp
                            </td style="text-align:center;">
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $z51=$z47*pow(1+$z48/100,$z49);
                                echo custome_money_format($z51);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               @php
                                $ag51=$ag47*pow(1+$ag48/100,$ag49);
                                echo custome_money_format($ag51);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                               @php
                                $an51=$an47*pow(1+$an48/100,$an49);
                                echo custome_money_format($an51);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Indexation Rate
                            </td>
                            <td style="text-align:center;">
                                @php
                                $l52=$t12;
                                echo number_format($l52, 2);
                                @endphp
                                %
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $s52=$t12;
                                echo number_format($s52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $z52=$t12;
                                echo number_format($z52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $ag52=$t12;
                                echo number_format($ag52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $an52=$t12;
                                echo number_format($an52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Indexed Cost
                            </td>
                            <td style="text-align:center;">
                                @php
                                $l53=$l47;
                                echo custome_money_format($l53);
                                @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                            <?php
                            if($fixed_deposit_indexation_benefit=='yes')
                            {
                               $ss=pow(1+$s52/100,$s49);
                               $s53=$s47*$ss;
                               echo custome_money_format($s53);
                            }else{
                               $s53=$s47;
                               echo custome_money_format($s53);
                            }
                            ?>
                            </td>
                            <?php } ?>

                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                            <?php
                            if($debt_fund_indexation_benefit=='yes')
                            {
                                $zz=pow(1+$z52/100,$z49);
                                $z53=$z47*$zz;
                                echo custome_money_format($z53);
                            }else{
                                $z53=$z47;
                                echo custome_money_format($z53);
                            }
                            ?>
                            </td>
                            <?php } ?>

                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                            <?php
                            if($equity_fund_indexation_benefit=='yes')
                            {
                                $ag=pow(1+$ag52/100,$ag49);
                                $ag53=$ag47*$ag;
                                echo custome_money_format($ag53);
                            }else{
                                $ag53=$ag47;
                                echo custome_money_format($ag53);
                            }
                            ?>
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                            <?php
                            if($other_indexation_benefit=='yes')
                            {
                                $an=pow(1+$an52/100,$an49);
                                $an53=$an47*$an;
                                echo custome_money_format($an53);
                            }else{
                                $an53=$an47;
                                echo custome_money_format($an53);
                            }
                            ?>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                Taxable Income
                            </td>
                            <td style="text-align:center;">
                                @php
                                $l54=$l51-$l53;
                                echo custome_money_format($l54);
                                @endphp
                                
                            </td>
                             <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $s54=$s51-$s53;
                                echo custome_money_format($s54);
                                @endphp
                               
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $z54=$z51-$z53;
                                echo custome_money_format($z54);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $ag54=$ag51-$ag53;
                                echo custome_money_format($ag54);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $an54=$an51-$an53;
                                echo custome_money_format($an54);
                                @endphp
                            </td>
                            <?php } ?>
                        </tr>

                        <tr>
                            <td>
                                Applicable Tax Rate
                            </td>
                            <td style="text-align:center;">
                                @php
                                $l55=$t13;
                                echo number_format($l55, 2);
                                @endphp
                                %
                            </td>
                             <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $s55=$fixed_deposit_taxation_rate;
                                echo number_format($s55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $z55=$debt_fund_taxation_rate;
                                echo number_format($z55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $ag55=$equity_fund_taxation_rate;
                                echo number_format($ag55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $an55=$other_taxation_rate;
                                echo number_format($an55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                        </tr>

                        <tr>
                            <td>
                                Tax Amount
                            </td>
                            <td style="text-align:center;">
                                @php
                                $l56=$l54*($l55/100);
                                echo custome_money_format($l56);
                                @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $s56=$s54*($s55/100);
                                echo custome_money_format($s56);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $z56=$z54*($z55/100);
                                echo custome_money_format($z56);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $ag56=$ag54*($ag55/100);
                                echo custome_money_format($ag56);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $an56=$an54*($an55/100);
                                echo custome_money_format($an56);
                                @endphp
                            </td>
                            <?php } ?>
                        </tr>
                         <tr>
                            <td>
                                Post-Tax Maturity Amount
                            </td>
                            <td style="text-align:center;">
                                @php
                                $l57=$l51-$l56;
                                echo custome_money_format($l57);
                                @endphp
                                
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $s57=$s51-$s56;
                                echo custome_money_format($s57);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $z57=$z51-$z56;
                                echo custome_money_format($z57);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $ag57=$ag51-$ag56;
                                echo custome_money_format($ag57);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                @php
                                $an57=$an51-$an56;
                                echo custome_money_format($an57);
                                @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>
                                <strong>Post-Tax IRR</strong>
                            </td>
                            <td style="text-align:center;">
                                <strong>{{number_format((pow($l57/$l45,1/$l49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>{{number_format((pow($s57/$s45,1/$s49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>{{number_format((pow($z57/$z45,1/$z49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="text-align:center;">
                               <strong>{{number_format((pow($ag57/$ag45,1/$ag49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="text-align:center;">
                                <strong>{{number_format((pow($an57/$an45,1/$an49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                        </tr>
                        </tbody></table>
                   
                   

                    * For ease of calculation, It is assumed that in case of 54EC Bond & Fixed Deposit or other interest paying investment, the annual interest is re-invested at the same rate of interest and income tax is paid at the end of investment term.<br>
                    * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                    * Returns are not guaranteed. The above is for illustration purpose only.<br>
                   

                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round mt-3"><i class="fa fa-angle-left"></i> Back</a>
                    
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.CG_Bond_vs_Other_Investment_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only mt-3" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

    @include('frontend.calculators.modal')
@endsection
