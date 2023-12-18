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
                        url: "{{ route('frontend.monthlyAnnuityForSIPOutputSave') }}",
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

@php
//Number of Months Q13*12
$number_of_months = $investment_period*12;
//Exp Rate of Return (SIP) (1+Q10%)^(1/12)-1
$expected_rate_of_return_sip = (1+$expected_rate_of_return1/100)**(1/12)-1;
//Exp Rate of Return (Lumpsum) (1+Q12%)^(1/12)-1
$expected_rate_of_returnlumpsum = (1+$expected_rate_of_return2/100)**(1/12)-1;
//SIP Fund Value (1+AR31)*Q9*(((1+AR31)^(AR30)-1)/AR31)
$sip_fund_value = (1+$expected_rate_of_return_sip)*$sip_amount*(((1+$expected_rate_of_return_sip)**($number_of_months)-1)/$expected_rate_of_return_sip);
//Lumpsum Fund Value Q11*(1+AR32)^AR30
$lumpsum_fund_value = $lumpsum_investment*(1+$expected_rate_of_returnlumpsum)**$number_of_months;
//Total Fund Value AR33+AR34
$total_fund_value = $sip_fund_value+$lumpsum_fund_value;
@endphp

    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    <div class="outputTableHolder">
                        <h5 class="mb-3 text-center">Future Value Of Lumpsum + SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>SIP Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($sip_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Lumpsum Investment</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($lumpsum_investment)}}
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
                                            <tr>
                                                <td>SIP</td>
                                                <td>{{$expected_rate_of_return1?number_format($expected_rate_of_return1, 2, '.', ''):0}} %</td>
                                            </tr>
                                            <tr>
                                                <td>Lumpsum</td>
                                                <td>{{$expected_rate_of_return2?number_format($expected_rate_of_return2, 2, '.', ''):0}} %</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
        
                                </tbody>
                            </table>
                            </div>
                            @if(isset($note) && $note!='')
                                <h5 class="text-center">Comments</h5>
                                <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody><tr>
                                        <td style="width: 50%;">
                                            <strong>{{$note}}</strong>
                                        </td>
                                    </tr>
                                    </tbody></table>
                                </div>
                            @endif
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align: center;">Expected Future Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>SIP Fund Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($sip_fund_value)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Lumpsum Fund Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($lumpsum_fund_value)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Total Fund Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($total_fund_value)}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                            <p style="text-align: left; margin-top: 20px;">
                                * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                                * Returns are not guaranteed. The above is for illustration purpose only.
                            </p>
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Annual Investment & Yearwise Projected Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center" style="background: #fff;">
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
        
                                    @for($i=1;$i<=$investment_period;$i++)
                                        @php
                                            //Annual Investment AU76*12+AV76
                                            if ($i==1){
                                            $annual_investment = $sip_amount*12+$lumpsum_investment;
                                            }else{
                                                $annual_investment = $sip_amount*12;
                                            }
                                            //Cumulative Investment
                                            $cumulative_investment +=$annual_investment;
                                            //SIP End Value (1+AS76)*AU76*(((1+AS76)^(AR76*12)-1)/AS76)
                                            $sip_end_value = (1+$expected_rate_of_return_sip)*$sip_amount*(((1+$expected_rate_of_return_sip)**($i*12)-1)/$expected_rate_of_return_sip);
                                            //Lumpsum End Value AV76*(1+AT76)^(AR76*12)
                                            $lumpsum_end_value = $lumpsum_investment*(1+$expected_rate_of_returnlumpsum)**($i*12);
                                        @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>₹ {{custome_money_format($annual_investment)}}</td>
                                        <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                        <td>₹ {{custome_money_format($sip_end_value)}}</td>
                                        <td>₹ {{custome_money_format($lumpsum_end_value)}}</td>
                                        <td>₹ {{custome_money_format($sip_end_value+$lumpsum_end_value)}}</td>
                                    </tr>
                                    @endfor
        
                                </tbody>
                            </table>
                            </div>
                            <br>
                            <p style="text-align: left; margin-top: 20px;">
                                *The above chart is approximate and for illustration purpose only
                            </p>
        
                            @include('frontend.calculators.suggested.output')
                        </div>
                <div class="text-center" style="padding:83px 0 20px 0;">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                        @else
                            <a href="{{route('frontend.futureValueOfLumpsumSipOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                        @endif
                        
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                    @endif

                    <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput" style="width: 320px;">Save & Merge with Sales Presenters</a>
                </div>
                </div>    
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

    <script type="text/javascript">
        var base_url = "{{route('frontend.futureValueOfLumpsumSipOutputPdfDownload')}}";
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
                    <form target="_blank" action="{{route('frontend.lumsumInvestmentRequiredForTargetFutureValueMergeDownload')}}" method="get">
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
