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
                        url: "{{ route('frontend.sipStpRequiredForTargetFutureValue_save') }}",
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

    <div class="banner">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div> --}}
        </div>
    </div>
    @php

        $target_amount = $target_amount;
        $period = $investment_period;
        $amount = $amount;
        $sip_or_stp = $sip_or_stp;
        $sip_interest_rate = $sip_interest_rate;
        $debt_interest = $debt_interest;
        $equity_interest = $equity_interest;
        $monthly_transfer_mode = $monthly_transfer_mode;

        //Numbers of month
        $number_of_months = $investment_period*12;
        //Monthly Debt return (1+T11%)^(1/12)-1
        $monthly_debit_return = (1+$debt_interest/100)**(1/12)-1;
         //Monthly Equity return (1+T12%)^(1/12)-1
        $monthly_equity_return = pow((1+$equity_interest/100),(1/12))-1;
        //Exp rate of return (1+Q16%)^(1/12)-1;
        $rate_of_return = (1+$sip_interest_rate/100)**(1/12)-1;


        if ($sip_or_stp == "sip") {
                //Monthly Appreciation T8*AT41
                $monthly_appreciation = $amount*$monthly_debit_return;
                $future_value_of_debt_fund = $amount;
                //AR37*(((1+AR36)^(AR33)-1)/AR36)
                $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($number_of_months)-1)/$monthly_equity_return);
                $total_stp_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                $balance_required = $target_amount - $total_stp_value;

                $monthly_sip_required = ($balance_required*$rate_of_return)/((1+$rate_of_return)*((1+$rate_of_return)**($number_of_months)-1));
            }else{
                $assumed_initial_investment = 1;
                  //Monthly Appreciation T8*AT41
                $monthly_appreciation = $assumed_initial_investment*$monthly_debit_return;

                 $sip_value = (1+$rate_of_return)*$amount*(((1+$rate_of_return)**($number_of_months)-1)/$rate_of_return);
                 $balance_required = $target_amount - $sip_value;


                 $future_value_of_debt_fund = $assumed_initial_investment;
                 $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($number_of_months)-1)/$monthly_equity_return);
                $total_stp_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                $required_stp_amount = $balance_required / $total_stp_value;
        }

    @endphp
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h1 class="midheading">SIP + STP @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
                        <div class="roundBorderHolder">

                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($target_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{$investment_period?$investment_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>
                                            @if ($sip_or_stp == "sip") STP Investment @else SIP Amount @endif
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                @if ($sip_or_stp == "sip")
                                    <tr>
                                        <td style="width: 50%; vertical-align: middle;">
                                            <strong>Assumed Rate of Return</strong>
                                        </td>
                                        <td style="padding: 0px;">
                                            <table style="width: 100%;">
                                                <tbody>

                                                <tr>
                                                    <td>Debt</td>
                                                    <td>{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                                                </tr>
                                                <tr>
                                                    <td>Equity</td>
                                                    <td>{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Assumed Return on SIP</strong>
                                        </td>
                                        <td>
                                            {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Assumed Return on SIP</strong>
                                        </td>
                                        <td>
                                            {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; vertical-align: middle;">
                                            <strong>Assumed Rate of Return on STP</strong>
                                        </td>
                                        <td style="padding: 0px;">
                                            <table style="width: 100%;">
                                                <tbody>

                                                <tr>
                                                    <td>Debt</td>
                                                    <td>{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                                                </tr>
                                                <tr>
                                                    <td>Equity</td>
                                                    <td>{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                @endif

                                </tbody>
                            </table>
                        </div>

                            <h1 class="midheading">
                                @if ($sip_or_stp == "sip")
                                    Monthly SIP Required
                                @else
                                    STP Investment Required
                                @endif
                            </h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    <tr>
                                        <td>
                                            @if ($sip_or_stp == "sip")
                                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_required)}}
                                            @else
                                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_stp_amount)}}
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                            
                            <p class="text-left">
                                * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                                * Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}
                            </p>

                            <h1 class="midheading">Annual Investment & Yearwise Projected Value</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    <tr>
                                        <th style="vertical-align: middle;">Year</th>
                                        <th style="vertical-align: middle;">Annual Investment</th>
                                        <th style="vertical-align: middle;">Cumulative Investment</th>
                                        <th style="vertical-align: middle;">SIP Fund Value</th>
                                        <th style="vertical-align: middle;">STP Fund Value</th>
                                        <th style="vertical-align: middle;">Total Fund Value</th>
                                    </tr>
                                    @php
                                        $cumulative_investment = 0;
                                    @endphp
                                    @for ($i = 1; $i <= $investment_period; $i++)
                                        @if ($sip_or_stp == "sip")
                                            @php
                                                $annual_investment = ($monthly_sip_required * 12);
                                                    if ($i == 1) {
                                                        $annual_investment = $amount + ($monthly_sip_required * 12);
                                                    }
    
                                                    $cumulative_investment = $amount + (($monthly_sip_required * 12) * $i);
    
                                                $sip_value = (1+$rate_of_return)*$monthly_sip_required*(((1+$rate_of_return)**($i*12)-1)/$rate_of_return);
    
                                                $future_value_of_debt_fund = $amount;
                                                //Future Value of Equity Fund AR37*(((1+AR35)^(AR32)-1)/AR35)
                                                $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($i*12)-1)/$monthly_equity_return);
    
                                                //STP Fund Value =AR38+AR39
                                                $stp_value = $future_value_of_debt_fund+$future_value_of_equity_fund;
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stp_value)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value + $stp_value)}}</td>
                                            </tr>
    
                                        @else
                                            @php
                                                $annual_investment = ($amount * 12);
                                                if ($i == 1) {
                                                    $annual_investment = $required_stp_amount + ($amount * 12);
                                                }
                                                $cumulative_investment = $required_stp_amount + (($amount * 12) * $i);
                                                $sip_value = (1+$rate_of_return)*$amount*(((1+$rate_of_return)**($i*12)-1)/$rate_of_return);
                                                $stp_value = $required_stp_amount + ($required_stp_amount*$monthly_debit_return)*(((1+$monthly_equity_return)**($i*12)-1)/$monthly_equity_return);
    
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stp_value)}}</td>
                                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value + $stp_value)}}</td>
                                            </tr>
                                        @endif
    
                                    @endfor
    
    
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-left">
                                *The above chart is approximate and for illustration purpose only
                            </p>
                        

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
        var base_url = "{{route('frontend.sipStpRequiredForTargetFutureValue_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.sipStpRequiredForTargetFutureValue_merge_download')}}" method="get">
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

