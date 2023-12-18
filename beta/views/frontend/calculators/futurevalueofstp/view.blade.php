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
                        url: "{{ route('frontend.futureValueOfSTPOutputSave') }}",
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
//Numbers of month
$number_of_months = $investment_period*12;
//Monthly Debt return (1+T11%)^(1/12)-1
$monthly_debit_return = pow((1+$debt_fund/100),(1/12))-1;
 //Monthly Equity return (1+T12%)^(1/12)-1
$monthly_equity_return = pow((1+$equity_fund/100),(1/12))-1;
$future_value_of_debt_fund = 0;
$future_value_of_equity_fund = 0;
$total_fund_value = 0;
$irr = 0;
if($monthly_transfer_mode=='CA'){
     //Monthly Appreciation T8*AT41
    $monthly_appreciation = $initial_investment*$monthly_debit_return;
    //Future Value of Debt Fund
    $future_value_of_debt_fund = $initial_investment;
    //Future Value of Equity Fund  AT43*(((1+AT42)^(AT40)-1)/AT42)
    $future_value_of_equity_fund = $monthly_appreciation*((pow((1+$monthly_equity_return),$number_of_months)-1)/$monthly_equity_return);
    //Total Fund Value AT44+AT45
    $total_fund_value = $future_value_of_debt_fund+$future_value_of_equity_fund;
    //IRR (AT46/T8)^(1/T9)-1
    $irr = pow(($total_fund_value/$initial_investment),(1/$investment_period))-1;
}else{
    if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP'){
        //Monthly Switch T8*AC15%
        $monthly_switch = $initial_investment*($fixed_percent/100);
    }else{
         //Monthly Switch T8*AC15%
        $monthly_switch = $fixed_amount;
    }

        $debt_transfer_amount = $monthly_switch;
        $debt_switch_amount = $monthly_switch;
        $equity_transfer_amount = $monthly_switch;
        $equity_switch_amount = $monthly_switch;
        $debt_balance = 0;
        $equity_eom_value = 0;
        $total_fund_value = 0;
        $irr = 0;

            for ($j=1;$j<=$number_of_months;$j++){
                    if ($j==1){
                         $debt_bom_value = $initial_investment;
                    }else{
                        $debt_bom_value = $debt_balance;
                    }

                    // AS80+AS80*AT80
                    $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debit_return;
                    if ($debt_eom_value>=$debt_transfer_amount){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount>0){
                        $debt_switch_amount = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance = $debt_eom_value - $debt_switch_amount;
               //Equity Calculation
                if ($j==1){
                     $equity_bom_value = 0;
                }else{
                    $equity_bom_value = $equity_eom_value;
                }
            // BA80+BA80*BC80+BB80
              $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return+$debt_switch_amount;
            //Total Value AW80+BD80
            $total_value = $debt_balance + $equity_eom_value;
            //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
            $irr = (pow(((1+($total_value-$initial_investment)/$initial_investment)),(12/$j))-1);
            }

            $future_value_of_debt_fund = $debt_balance;
            $future_value_of_equity_fund = $equity_eom_value;
            $total_fund_value = $future_value_of_debt_fund + $future_value_of_equity_fund;
}
@endphp
    <div class="banner styleApril">
        <div class="container">
            <!-- @ include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Calculators Cum Client Proposals</h2>
                </div>
            </div> -->
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                <div class="outputTableHolder">

                    <h1 class="midheading">STP Investment @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
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
                                        <strong>Monthly Transfer Mode </strong>
                                    </td>
                                    <td>
                                        @if($monthly_transfer_mode=='CA')
                                            Capital Appreciation
                                        @else
                                            @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
                                                {{$fixed_percent?number_format($fixed_percent, 2, '.', ''):0}} % of Initial Investment
                                            @else
                                                ₹ {{custome_money_format($fixed_amount)}}
                                             @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Period  </strong>
                                    </td>
                                    <td>
                                        {{$investment_period?$investment_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle;">
                                        <strong>Assumed Rate of Return  </strong>
                                    </td>
                                    <td rowspan="2" style="padding: 0">
                                        <table width="100%">
                                            <tr>
                                                <td>Debt Fund</td>
                                                <td>
                                                    {{$debt_fund?number_format($debt_fund, 2, '.', ''):0}} %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Equity Fund</td>
                                                <td>
                                                    {{$equity_fund?number_format($equity_fund, 2, '.', ''):0}} %
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                            {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                            <h1 class="midheading">Projected Future Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>Debt Fund Value</td>
                                    <td>
                                        ₹ {{custome_money_format($future_value_of_debt_fund)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Equity Fund Value</td>
                                    <td>
                                        ₹ {{custome_money_format($future_value_of_equity_fund)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Fund Value</td>
                                    <td>
                                        ₹ {{custome_money_format($total_fund_value)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Annualised Returns</td>
                                    <td>
                                        {{$irr?number_format($irr*100, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                            <p class="text-left">
                                * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                                * Returns are not guaranteed. The above is for illustration purpose only.
                            </p>
                            @if(isset($report) && $report=='detailed')
                            <h1 class="midheading">Projected Annual Investment Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle;">Year</th>
                                    <th>Debt Fund Value <br>at the beginning <br>of year</th>
                                    <th>Transfer to<br> Equity every<br> year</th>
                                    <th>Equity Fund Value<br> at the beginning<br> of year</th>
                                    <th>Equity Fund Value<br> at the end of year</th>
                                    <th>Total Value at the<br> end of year<br> (Debt+Equity)</th>
                                    <th style="width: 72px; vertical-align: middle;">IRR</th>
                                </tr>
                                @if($monthly_transfer_mode=='CA')
                                        @for($i=1;$i<=$investment_period;$i++)
                                            @php
                                            if ($i==1){
                                                    $equity_fund_value_at_the_begining_of_year = 0;
                                                }else{
                                                    $equity_fund_value_at_the_begining_of_year = $equity_fund_value_at_the_end_of_year;
                                                }
                                            //Equity Fund Value at the end of year AU79*(((1+AW79)^(AR79*12)-1)/AW79)
                                            $equity_fund_value_at_the_end_of_year = $monthly_appreciation*((pow((1+$monthly_equity_return),($i*12))-1)/$monthly_equity_return);
                                            //AT79+AX79
                                            $total_value_at_the_end_of_the_year = $initial_investment+$equity_fund_value_at_the_end_of_year;
                                            //IRR (AY79/AS79)^(1/AR79)-1
                                            $irr = (pow(($total_value_at_the_end_of_the_year/$initial_investment),(1/$i))-1)*100;
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    ₹ {{$initial_investment?custome_money_format($initial_investment):0}}
                                                </td>
                                                <td>
                                                    ₹ {{$monthly_appreciation?custome_money_format($monthly_appreciation*12):0}}
                                                </td>
                                                <td>
                                                    ₹ {{$equity_fund_value_at_the_begining_of_year?custome_money_format($equity_fund_value_at_the_begining_of_year):0}}
                                                </td>
                                                <td>
                                                    ₹ {{$equity_fund_value_at_the_end_of_year?custome_money_format($equity_fund_value_at_the_end_of_year):0}}
                                                </td>
                                                <td>
                                                    ₹ {{$total_value_at_the_end_of_the_year?custome_money_format($total_value_at_the_end_of_the_year):0}}
                                                </td>
                                                <td>
                                                    {{$irr?number_format((float)$irr, 2, '.', ''):0}} %
                                                </td>
                                            </tr>
                                         @endfor
        
                                @else
                                    @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP' || isset($fixed_transfer_mode) && $fixed_transfer_mode=='FA')
                                            @php
                                                $i = 1;
                                                $debt_transfer_amount = $monthly_switch;
                                                $debt_switch_amount = $monthly_switch;
        
                                                $equity_transfer_amount = $monthly_switch;
                                                $equity_switch_amount = $monthly_switch;
                                                $ech_counter = 13;
                                                $tmp_dbt_fnd_bgyear = 0;
                                                $tmp_transfer_to_equity_every_year = 0;
                                                $tmp_equity_fund_value_beg_of_year = 0;
                                            @endphp
        
                                            @for ($j=1;$j<=$number_of_months;$j++)
                                                @php
                                                    if ($j==1){
                                                         $debt_bom_value = $initial_investment;
                                                    }else{
                                                        $debt_bom_value = $debt_balance;
                                                    }
        
                                                    if (($ech_counter-12)==$j){
                                                            if ($j==1){
                                                                $tmp_dbt_fnd_bgyear = $initial_investment;
                                                                $tmp_equity_fund_value_beg_of_year = 0;
                                                            }else{
                                                                $tmp_dbt_fnd_bgyear = $debt_balance;
                                                                $tmp_equity_fund_value_beg_of_year = $equity_eom_value;
                                                            }
                                                        }
        
                                                    // AS80+AS80*AT80
                                                    $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debit_return;
        
                                                    if ($debt_eom_value>=$debt_transfer_amount){
                                                        $debt_monthly_transfer = $monthly_switch;
                                                    }else{
                                                        $debt_monthly_transfer = 0;
                                                    }
        
                                                    if ($debt_switch_amount>0){
                                                        $debt_switch_amount = $debt_monthly_transfer;
                                                    }else{
                                                        $debt_switch_amount =0;
                                                    }
                                                    //Balance AU80-AV80
                                                    $debt_balance = $debt_eom_value - $debt_switch_amount;
        
                                               //Equity Calculation
                                                if ($j==1){
                                                     $equity_bom_value = 0;
                                                }else{
                                                    $equity_bom_value = $equity_eom_value;
                                                }
                                            // BA80+BA80*BC80+BB80
                                              $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return+$debt_switch_amount;
                                            //Total Value AW80+BD80
                                            $total_value = $debt_balance + $equity_eom_value;
                                            //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                                            $irr = (pow(((1+($total_value-$initial_investment)/$initial_investment)),(12/$j))-1)*100;
        
                                                $tmp_transfer_to_equity_every_year = $tmp_transfer_to_equity_every_year + $debt_switch_amount;
                                                @endphp
        
                                                @if($j%12==0)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>
                                                            ₹ {{custome_money_format($tmp_dbt_fnd_bgyear)}}
                                                        </td>
                                                        <td>
                                                            ₹ {{custome_money_format($tmp_transfer_to_equity_every_year)}}
                                                        </td>
                                                        <td>
                                                            ₹ {{custome_money_format($tmp_equity_fund_value_beg_of_year)}}
                                                        </td>
                                                        <td>
                                                            ₹ {{custome_money_format($equity_eom_value)}}
                                                        </td>
                                                        <td>
                                                            ₹ {{custome_money_format($debt_balance+$equity_eom_value)}}
                                                        </td>
                                                        <td>
                                                            {{$irr?number_format((float)$irr, 2, '.', ''):0}} %
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $ech_counter = $ech_counter+12;
                                                        $tmp_transfer_to_equity_every_year = 0;
        
                                                        $i++;
                                                    @endphp
                                                @endif
        
                                             @endfor
                                        @endif
        
                                     @endif
        
        
                                </tbody>
                            </table>
                            </div>
                            
                            <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                                @endif
                            @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center viewBelowBtn">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.futureValueOfSTPOutputDownloadPDF')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.futureValueOfSTPOutputDownloadPDF')}}";
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
                    <form target="_blank" action="{{route('frontend.futureValueOfSTPMergeDownload')}}" method="get">
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

