@extends('layouts.frontend')
@section('js_after')
<script>
$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
});
jQuery(window).on('load', function() {
var closedamt=jQuery("#closing_bal").val();
    
    jQuery.ajax({
                    url: "{{ route('frontend.swp_debt_balanc_fund_calculator_ajax') }}",
                    method: 'POST',
                    data: {
                        closedamt: closedamt
                    },
                    success: function(result){
                        jQuery("#cl_view").text(closedamt);
                }});
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
                    url: "{{ route('frontend.swp_debt_balanc_fund_calculator_save') }}",
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

    <div class="banner styleApril">
        <div class="container">
            <!--<div class="row">-->
            <!--    <div class="col-md-12 text-center">-->
            <!--        <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                	<div class="outputTableHolder">
                        <h1 class="midheading">SWP Planning @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                        @if(isset($current_age) && $current_age)
                            <input type="hidden" id="current_age" value="{{$current_age}}">
                        @else
                            <input type="hidden" id="current_age" value="">
                        @endif
                        @if(isset($clientname) && $clientname)
                            <input type="hidden" id="clientname" value="{{$clientname}}">
                        @else
                            <input type="hidden" id="clientname" value="">
                        @endif
                        <?php
                        if($annuity=='Immediate_Annuity')
                        { ?>
    
                        <?php }else{ ?>
                        
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                            <tbody>
                            <?php if(isset($current_age)){ ?>
                            <tr>
                                <td>
                                    <strong>Age</strong>
                                </td>
                                <td>
                                    {{$current_age?$current_age:0}} Years
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td>
                                    <strong>Initial Investment</strong>
                                </td>
                                <td>
                                     ₹ {{custome_money_format($initial_investment)}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Deferment Period</strong>
                                </td>
                                <td>
                                     {{$deferment_period}} Years
                                </td>
                            </tr>
                            </tbody>
                            </table>
                        </div>
                        
                        <div class="roundBorderHolder">    
                            <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>Asset Class</strong>
                                </td>
                                <td>
                                    <strong>Debt</strong>
                                </td>
                                @if($balance)
                                <td>
                                    <strong>Balance</strong>
                                </td>
                                @endif
                                @if($equity)
                                <td>
                                    <strong>Equity</strong>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td><strong>% Allocation</strong></td>
                                <td>{{number_format($debt,2)}}%</td>
                                @if($balance)
                                <td>{{number_format($balance,2)}}%</td>
                                @endif
                                @if($equity)
                                <td>{{number_format($equity,2)}}%</td>
                                @endif
                            </tr>
                            <tr>
                                <td><strong>Expected Return</strong></td>
                                <td>{{number_format($debt2,2)}}%</td>
                                @if($balance)
                                <td>{{number_format($balance2,2)}}%</td>
                                @endif
                                @if($equity)
                                <td>{{number_format($equity2,2)}}%</td>
                                @endif
                            </tr>
                            
                            </tbody>
                            </table>
                        </div>
                        <?php } ?>
    
                        <?php
                        if($annuity=='Immediate_Annuity')
                        { ?>
    
                        <?php }else{ ?>
                            <h1 class="midheading">SWP Period</h1>
                        <?php } ?>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-left">
                                    <tbody>
                                    <?php if(isset($current_age)){ ?>
                                    <?php
                                    if($annuity=='Immediate_Annuity')
                                    { ?>
                                    <tr>
                                        <td style="width: 60%;">
                                            <strong>Age</strong>
                                        </td>
                                        <td style="width: 40%;text-align:center;">
                                            {{$current_age?$current_age:0}} Years
                                        </td>
                                    </tr>
                                    <?php }else{ ?>
                                    <tr>
                                        <td>
                                            <strong>Age</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$current_age?$current_age+$deferment_period+1:0}} Years
                                        </td>
                                    </tr>
                                    <?php }} ?>
                                    <?php 
                                    if($annuity=='Immediate_Annuity')
                                    { ?>
                                    <tr>
                                        <td>
                                            <strong>Initial Investment</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            ₹ {{custome_money_format($initial_investment)}}
                                        </td>
                                    </tr>
                                    <?php }else{ ?>
                                    <tr>
                                        <td>
                                            <strong>Accumulated Fund Value</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            ₹ {{custome_money_format($fund_value)}}
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                            <strong>% Investment in Debt Fund</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$debt_fund?number_format($debt_fund,2):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>% Investment in Balance Fund</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$balance_fund?$balance_fund:0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Expected Return (Debt Fund)</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$expected_return_debt_fund?number_format($expected_return_debt_fund,2):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Expected Return (Balance Fund)</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$expected_return_balance_fund?number_format($expected_return_balance_fund,2):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Annual Withrawal</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$annual_withdrawal_precent_investment?number_format($annual_withdrawal_precent_investment,2):0}} %
                                        </td>
                                    </tr>
                                   <tr>
                                        <td>
                                            <strong>SWP Period</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$swp_period?$swp_period:0}} Years
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>
                                            <strong>Rebalancing Period</strong>
                                        </td>
                                        <td style="text-align:center;">
                                            {{$periodic_rebalance_period?$periodic_rebalance_period:0}} Years
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
    
                        <h1 class="midheading">Expected Portfolio Return</h1>
                        <h6 class="outputSingleBox">{{$exp_portfolio_return?$exp_portfolio_return:0}}%</h6>
    
                        <h1 class="midheading">{{$withdrawal?$withdrawal:0}} SWP Amount</h1>
                        <h6 class="outputSingleBox">₹ {{$withdrawal_amount?custome_money_format($withdrawal_amount):0}}</h6>
                        @php
                            if($withdrawal=='Monthly')
                            {
                                $mo=12;
                            }elseif($withdrawal=='Quarterly')
                            {
                                $mo=4;
                            }elseif($withdrawal=='Half-Yearly')
                            {
                                $mo=2;
                            }elseif($withdrawal=='Yearly')
                            {
                                $mo=1;
                            }
                        @endphp
                        <h1 class="midheading">Total Annuity Received</h1>
                        <h6 class="outputSingleBox">₹ {{$withdrawal_amount?custome_money_format($withdrawal_amount*$swp_period*$mo):0}}</h6>
    
                        <h1 class="midheading">Closing Fund Value</h1>
                        <h6 class="outputSingleBox">₹ <span id="cl_view"></span></h6>
                        
                        <p class="text-left">
                        * It is assumed that SWP amount is received on the last day of the month.<br>
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        * Returns are not guaranteed. The above is for illustration purpose only.
                        </p>
                        <br>
    
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                        <h1 class="midheading">Projected Annual Cash Flow & Fund Value</h1>
                        @php
                        if(isset($deferment_period)){ $deferment_period=$deferment_period; }else{ $deferment_period=0; }
                        @endphp
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;"><strong><?php if(isset($current_age)){ $age=$current_age; ?>Age<?php }else{ $age=0; ?>Year<?php } ?></strong></th>
                                    <th colspan="2"><strong>Debt Fund</strong></th>
                                    <th colspan="2"><strong>Balance Fund</strong></th>
                                    <th rowspan="2" style="vertical-align: middle;"><strong>Annual Withdrawal</strong></th>
                                    <th rowspan="2" style="vertical-align: middle;"><strong>End of Year Fund Value</strong></th>
                                    <th rowspan="2" style="vertical-align: middle;"><strong>Transfer From Balance To Debt Fund</strong></th>
                                </tr>
                                <tr>
                                    <th><strong>Beginning of Year</strong></th>
                                    <th><strong>End of Year</strong></th>
                                    <th><strong>Beginning of Year</strong></th>
                                    <th><strong>End of Year</strong></th>
                                </tr>
        
                            <?php for($i=1;$i<=$swp_period;$i++){ ?>
        
                            @php
                            if($annuity=='Immediate_Annuity')
                            {
                                $t9=$initial_investment;
                            }else{
                                $t9=$fund_value;
                            }
                            
                            $j14=$debt_fund;
                            $be14=$t9*$j14/100;
                            $t10=$expected_return_debt_fund/100;
                            $t11=$expected_return_balance_fund/100;
                            $g20=$withdrawal_amount;
                            $be15=$t9-$be14;
                            $t23=$periodic_rebalance_period;
        
                            $at73=pow((1+$t10),(1/$year_value))-1;
                            $au73=$g20;
                            $av73=($au73*(1-pow(1+$at73,-$year_value)))/$at73;
                            if($i==1)
                            {
                                $as73=$be14;
                            }else{
                                $as73=(($as73-$av73)*pow((1+$at73),$year_value))+$be73;
                            }
                            
                            if($i==1)
                            {
                                $ax73=$be15;
                            }else{
                                $ax73=$az73-$be73;
                            }
                           
        
        
                            $ay73=pow((1+$t11),(1/$year_value))-1;
                            $az73=$ax73*pow(1+$ay73,$year_value);
                            $bd73=$be15;
        
                            if($i%$periodic_rebalance_period==0 && $i!=1)
                            {
                                $be73=$az73-$bd73;
                            }else{
                                $be73=0;
                            }
                            
                            $aw73=(($as73-$av73)*pow((1+$at73),$year_value))+$be73;
                            $ba73=$az73-$be73;
                            @endphp
                                <tr>
                                    <td>{{$i+$age+$deferment_period}}</td>
                                    <td>
                                    {{custome_money_format($as73)}}</td>
                                    <td>
                                    @php
                                    $j73=$aw73;
                                    @endphp
                                    {{custome_money_format($aw73)}}
                                    </td>
                                    <td>
                                    {{custome_money_format($ax73)}}
                                    </td>
                                    <td>
                                    @php
                                    $t73=$ba73;
                                    @endphp
                                    {{custome_money_format($ba73)}}
                                    </td>
                                    <td>
                                    {{custome_money_format($au73*$year_value)}}
                                    </td>
                                    <td>
                                    {{$closing_bal=custome_money_format($j73+$t73)}}
                                    </td>
                                    <td>{{custome_money_format($be73)}}</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            </table>
                        </div>
                        <p class="text-left">*The above is for illustration purpose only. Actual figures may vary depending on market.</p>
                        <input type="hidden" name="closing_bal" value="{{$closing_bal}}" id="closing_bal">
                        @include('frontend.calculators.suggested.output')
                    </div>
                    
                    <div class="text-center viewBelowBtn">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.swp_debt_balanc_fund_calculator_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.swp_debt_balanc_fund_calculator_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.swp_debt_balanc_fund_calculator_merge_download')}}" method="get">
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
