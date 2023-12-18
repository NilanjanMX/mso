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
                        url: "{{ route('frontend.insuranceTermCover_save') }}",
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
        //Annual Investment V7-V10
        $annual_investment = $insurance_policy_annual_premium - $equivalent_insurance_term_policy_premium;
        //Monthly SIP Amount AU30/12
        $monthly_sip_amount = $annual_investment/12;
         //Number of Months R9*12
        $number_of_months = $policy_term*12;
          //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return = (1+$rate_of_return_investments/100)**(1/12)-1;
        //Total Fund Value (Investment) (1+AU33)*AU31*(((1+AU33)^(AU32)-1)/AU33)
        $total_fund_value_investment = (1+$rate_of_return)*$monthly_sip_amount*(((1+$rate_of_return)**($number_of_months)-1)/$rate_of_return);
        //Total Fund Value (Insurance) (1+V12%)*(V7)*(((1+V12%)^(V9)-1)/V12%)
        $total_fund_value_insurance = (1+$rate_of_return_insurance/100)*($insurance_policy_annual_premium)*(((1+$rate_of_return_insurance/100)**($policy_term)-1)/($rate_of_return_insurance/100));
        //echo $total_fund_value_insurance; die();

    @endphp
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h1 class="midheading">Insurance vs. Term Cover With Annual SIP Comparison @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}} @else  @endif</h1>
                            
                            

                                <h1 class="midheading">
                                    Insurance
                                </h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Annual Premium</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($insurance_policy_annual_premium)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Sum Assured / Death Benefit</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($sum_assured)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Policy Term</strong>
                                        </td>
                                        <td>
                                            {{$policy_term?$policy_term:0}} Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Assumed Rate Of Return</strong>
                                        </td>
                                        <td>
                                            {{$rate_of_return_insurance?number_format($rate_of_return_insurance, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Expected Maturity Value</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($total_fund_value_insurance)}}
                                        </td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>
                                <h1 class="midheading">
                                    Term Cover + Monthly SIP
                                </h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Sum Assured / Death Benefit</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($sum_assured)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Term Policy Premium</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($equivalent_insurance_term_policy_premium)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Monthly SIP Amount</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($monthly_sip_amount)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Total Annual Outlay</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($insurance_policy_annual_premium)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Time Period</strong>
                                        </td>
                                        <td>
                                            {{$policy_term?$policy_term:0}} Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Assumed Rate Of Return</strong>
                                        </td>
                                        <td>
                                            {{$rate_of_return_investments?number_format($rate_of_return_investments, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Expected Fund Value</strong>
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($total_fund_value_investment)}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                                
                            {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                            <p class="text-left">
                            * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        *Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}
                        </p>
    
                            @include('frontend.calculators.suggested.output')
                        </div>
                    </div>
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
        var base_url = "{{route('frontend.insuranceTermCover_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.insuranceTermCover_merge_download')}}" method="get">
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

