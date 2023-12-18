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
                        url: "{{ route('frontend.stpRequiredForTargetFutureValue_save') }}",
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
@php
    //Numbers of month
    $number_of_months = $investment_period*12;
    //Monthly Debt return (1+T11%)^(1/12)-1
    $monthly_debit_return = pow((1+$debt_fund/100),(1/12))-1;
        //Monthly Equity return (1+T12%)^(1/12)-1
    $monthly_equity_return = pow((1+$equity_fund/100),(1/12))-1;
    //Assumed Initial Investment
    $assumed_initial_investment = 1;


    $future_value_of_debt_fund = 0;
    $future_value_of_equity_fund = 0;
    $debt_fund_value = 0;
    $equity_fund_value = 0;
    $total_fund_value = 0;

    $monthly_appreciation2 = 0;
    $assumed_initial_investment_required = 0;
    $irr = 0;

    $initial_investment = 0;

    if($monthly_transfer_mode=='CA'){
            //Monthly Appreciation T8*AT41
        $monthly_appreciation = $assumed_initial_investment*$monthly_debit_return;

        //Future Value of Debt Fund
        $future_value_of_debt_fund = $assumed_initial_investment;
        //Future Value of Equity Fund  AT44*(((1+AT43)^(AT41)-1)/AT43)
        $future_value_of_equity_fund = $monthly_appreciation*((pow((1+$monthly_equity_return),$number_of_months)-1)/$monthly_equity_return);
        //Total Fund Value AT44+AT45
        $total_fund_value = $future_value_of_debt_fund+$future_value_of_equity_fund;

        //Initial Investment Required T8/AT47
        $assumed_initial_investment_required = $target_amount/$total_fund_value;
        //Debt Fund Value
        $debt_fund_value = $assumed_initial_investment_required;
        //Monthly Appreciation AT48*AT42
        $monthly_appreciation2 = $assumed_initial_investment_required*$monthly_debit_return;
        //Equity Fund Value AT49*(((1+AT43)^(AT41)-1)/AT43)
        $equity_fund_value = $monthly_appreciation2*((pow((1+$monthly_equity_return),$number_of_months)-1)/$monthly_equity_return);

        //Total Fund Value
        $total_fund_value = $debt_fund_value + $equity_fund_value;
        //IRR (AT52/AT48)^(1/T9)-1
        $irr = pow(($total_fund_value/$assumed_initial_investment_required),(1/$investment_period))-1;
    }else{

        if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP'){
            //Monthly Switch T8*AC15%
            $monthly_switch = $assumed_initial_investment*($fixed_percent/100);
        }else{
                //Monthly Switch T8*AC15%
            $monthly_switch = $fixed_amount;
        }


       //
        $debt_transfer_amount = $monthly_switch;
        $debt_switch_amount = $monthly_switch;
        $equity_transfer_amount = $monthly_switch;
        $equity_switch_amount = $monthly_switch;

    for ($j=1;$j<=$number_of_months;$j++){

                if ($j==1){
                    $debt_bom_value = $assumed_initial_investment;
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
        $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
        }

        $future_value_of_debt_fund = $debt_balance;
        $future_value_of_equity_fund = $equity_eom_value;
        $total_fund_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

        $initial_investment = $target_amount/$total_fund_value;
        $assumed_initial_investment_required = $initial_investment;
        //monthly Switch AY41*AC15%
        $monthly_switch = $initial_investment*($fixed_percent/100);


        /////////////////////////////////////////////////////////////////

        $debt_transfer_amount = $monthly_switch;
        $debt_switch_amount = $monthly_switch;

        $equity_transfer_amount = $monthly_switch;
        $equity_switch_amount = $monthly_switch;


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
        $debt_fund_value = $future_value_of_debt_fund;
        $equity_fund_value = $future_value_of_equity_fund;
   }
@endphp

    <div class="banner styleApril">
        <div class="container">
        @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">STP Required For Target Future Value</h2>
                </div>
            </div>
        </div>
    </div>
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.stpRequiredForTargetFutureValue_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @endif
                    
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
                            <a href="{{route('frontend.stpRequiredForTargetFutureValue_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h1 class="midheading">STP Required For Target Future Value Comparison @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}} @else  @endif</h1>
                        
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($target_amount)}}
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
                                    <td rowspan="2" style="padding: 0;">
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
                            <h1 class="midheading">Initial Investment Required</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        ₹ {{custome_money_format($assumed_initial_investment_required)}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        
                            <h1 class="midheading">Projected Future Value</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>Debt Fund Value</td>
                                    <td>
                                        ₹ {{custome_money_format($debt_fund_value)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Equity Fund Value</td>
                                    <td>
                                        ₹ {{custome_money_format($equity_fund_value)}}
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
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                            <!--<p style="text-align: left">-->
                            <!--    * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>-->
                            <!--    * Returns are not guaranteed. The above is for illustration purpose only.-->
                            <!--</p>-->
                            
                        <div class="description-text">
                            @php
                            $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
                                if(!empty($note_data1)){
                            @endphp
                                {!!$note_data1->description!!}
                            @php } @endphp
                            Report Date : {{date('d/m/Y')}}
                        </div>
                            
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
                                    <th style="width: 72px;vertical-align: middle;">IRR</th>
                                </tr>
                                @if($monthly_transfer_mode=='CA')
                                    @for($j=1;$j<=$investment_period;$j++)
                                        @php
                                            if ($j==1){
                                                $equity_fund_value_begining_of_year = 0;
                                                }else{
                                                $equity_fund_value_begining_of_year = $equity_fund_eoy;
                                                }
                                            //Equity Fund EOY AU78*(((1+AW78)^(AR78*12)-1)/AW78)
                                            $equity_fund_eoy = $monthly_appreciation2*((pow((1+$monthly_equity_return),($j*12))-1)/$monthly_equity_return);
                                            $total_value_eoy = $assumed_initial_investment_required + $equity_fund_eoy;
                                            //IRR
                                            $irr = (pow(($total_value_eoy/$assumed_initial_investment_required),(1/$j))-1)*100;
                                        @endphp
        
                                        <tr>
                                            <td> {{$j}}</td>
                                            <td>₹ {{custome_money_format($assumed_initial_investment_required)}}</td>
                                            <td>₹ {{custome_money_format($monthly_appreciation2*12)}}</td>
                                            <td>₹ {{custome_money_format($equity_fund_value_begining_of_year)}}</td>
                                            <td>₹ {{custome_money_format($equity_fund_eoy)}}</td>
                                            <td>₹ {{custome_money_format($total_value_eoy)}}</td>
                                            <td>{{$irr?number_format((float)$irr, 2, '.', ''):0}} %</td>
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
                                
                            @endif
                        <!--<p style="text-align: left">-->
                        <!--    *Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}-->
                        <!--</p>-->
                        <div class="description-text">
                            @php
                                $note_data1 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','STP_Required_For_Target_Future_Value')->first();
                            @endphp
                            @if(!empty($note_data1))
                                {!!$note_data1->description!!}
                            @endif
                            Report Date : {{date('d/m/Y')}}
                        </div>
                        
                        

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="midheading" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.stpRequiredForTargetFutureValue_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @endif
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
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.stpRequiredForTargetFutureValue_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.stpRequiredForTargetFutureValue_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
