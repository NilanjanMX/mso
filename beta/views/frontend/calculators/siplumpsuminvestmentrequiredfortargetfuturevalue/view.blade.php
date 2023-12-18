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
                        url: "{{ route('frontend.sipLumpsumInvestmentTargetFutureValueOutputSave') }}",
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
$target_amount = $target_amount;
$period = $investment_period;
$period_in_months = $period * 12;

$investment_type = $investment_type;
$investment_amount = $investment_amount;

$sip_interest_rate = $sip_interest_rate;
$monthly_sip_interest_rate = (1+$sip_interest_rate/100)**(1/12)-1;

$onetime_interest_rate = $onetime_interest_rate;
$monthly_onetime_interest_rate = (1+$onetime_interest_rate/100)**(1/12)-1;

if ($investment_type == "SIP") {
    $lumpsum_investment_amount = $investment_amount;
    $lumpsum_future_value = $investment_amount*(1+$monthly_onetime_interest_rate)**$period_in_months ;
    $required_sip_future_value = $target_amount - $lumpsum_future_value;
    $required_sip = ($required_sip_future_value * $monthly_sip_interest_rate) / ((1 + $monthly_sip_interest_rate) * (pow((1 + $monthly_sip_interest_rate), ($period_in_months)) - 1));
}
if ($investment_type == "lumpsum") {
    $sip_amount = $investment_amount;
    //(1+AR32)*Q12*(((1+AR32)^(AR31)-1)/AR32)
    $sip_future_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($period_in_months)-1)/$monthly_sip_interest_rate);
    $required_lumpsum_future_value = $target_amount - $sip_future_value;
    //AR36/(1+AR33)^AR31
    $required_onetime_investment = $required_lumpsum_future_value/(1+$monthly_onetime_interest_rate)**$period_in_months;
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

                    <h1 class="midheading">SIP + Lumpsum @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($target_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>
                                            @if ($investment_type == "SIP") Lumpsum Investment @else SIP Amount @endif</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($investment_amount)}}
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
                                    <td style="width: 50%; vertical-align: middle;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="padding: 0px;">
                                        <table style="width: 100%;">
                                            <tbody>
                                            @if ($investment_type == "lumpsum")
                                            <tr>
                                                <td>SIP</td>
                                                <td>{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                                            </tr>
                                            <tr>
                                                <td>Lumpsum</td>
                                                <td>{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                                            </tr>
                                            @else
                                                <tr>
                                                    <td>Lumpsum</td>
                                                    <td>{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                                                </tr>
                                                <tr>
                                                    <td>SIP</td>
                                                    <td>{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
        
                                </tbody>
                            </table>
                        </div>
                        
                        
                            <h1 class="midheading">
                                @if ($investment_type == "SIP") Monthly SIP Required @else Lumpsum Investment Required @endif
                            </h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        @if ($investment_type == "SIP")
                                            ₹ {{custome_money_format($required_sip)}}
                                        @else
                                            ₹ {{custome_money_format($required_onetime_investment)}}
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
                                * Returns are not guaranteed. The above is for illustration purpose only.
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
                                    <th style="vertical-align: middle;">Lumpsum Fund Value</th>
                                    <th style="vertical-align: middle;">Total Fund Value</th>
                                </tr>
                                    @php
                                        $cumulative_investment = 0;
                                    @endphp
        
        
                                     @if ($investment_type == "SIP")
                                         @for ($i = 1; $i <= $investment_period; $i++)
                                            @php
                                                $annual_investment = ($required_sip * 12);
                                                        if ($i == 1) {
                                                            $annual_investment = $lumpsum_investment_amount + ($required_sip * 12);
                                                        }
        
                                                        $cumulative_investment = $lumpsum_investment_amount + (($required_sip * 12) * $i);
        
                                                        $sip_value = (1+$monthly_sip_interest_rate)*$required_sip*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                                        $lumpsum_value = $lumpsum_investment_amount*(1+$monthly_onetime_interest_rate)**($i*12);
        
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>₹ {{custome_money_format($annual_investment)}}</td>
                                                <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                                <td>₹ {{custome_money_format($sip_value)}}</td>
                                                <td>₹ {{custome_money_format($lumpsum_value)}}</td>
                                                <td>₹ {{custome_money_format($sip_value + $lumpsum_value)}}</td>
                                            </tr>
                                        @endfor
        
                                   @else
                                         @for ($i = 1; $i <= $investment_period; $i++)
                                             @php
                                                $annual_investment = ($sip_amount * 12);
                                                            if ($i == 1) {
                                                                $annual_investment = $required_onetime_investment + ($sip_amount * 12);
                                                            }
        
                                                            $cumulative_investment = $required_onetime_investment + (($sip_amount * 12) * $i);
                                                            $sip_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                                            $lumpsum_value = $required_onetime_investment*(1+$monthly_onetime_interest_rate)**($i*12);
                                             @endphp
                                             <tr>
                                                 <td>{{$i}}</td>
                                                 <td>₹ {{custome_money_format($annual_investment)}}</td>
                                                 <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                                 <td>₹ {{custome_money_format($sip_value)}}</td>
                                                 <td>₹ {{custome_money_format($lumpsum_value)}}</td>
                                                 <td>₹ {{custome_money_format($sip_value + $lumpsum_value)}}</td>
                                             </tr>
                                         @endfor
                                   @endif
                                </tbody>
                            </table>
                            </div>
                                    

                            
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
        var base_url = "{{route('frontend.sipLumpsumInvestmentTargetFutureValueOutputPdfDownload')}}";
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
                    <form target="_blank" action="{{route('frontend.sipLumpsumInvestmentTargetFutureValueMergeDownload')}}" method="get">
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

