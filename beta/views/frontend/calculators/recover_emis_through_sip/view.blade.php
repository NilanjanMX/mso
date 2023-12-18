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
                        url: "{{ route('frontend.recover_emis_through_sip_save') }}",
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
<!-- <div class="banner bannerForAll container" style="padding-bottom: 0; margin-bottom: 15px;">
        <div id="main-banner" class="owl-carousel owl-theme">
            <div class="item">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="py-4">Premium Calculators</h2>
                        <p>Lörem ipsum rutavdrag bespepp. Danyre gereras, sar rugbyförälder, ären. Multitirade pabel men spökgarn medan nåfus kreddig. Decill eus. Osm kromera, diadunade intrarade. 
                        </p>
                        <a href="" class="createtempbtn" style=" margin-right: 22px; "><button class="btn banner-btn mt-3">Sample Reports</button></a>
                        <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">How to Use</button></a>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/pcalculatorbanner.png" alt="" /></div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="banner styleApril">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div> --}}
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h1 class="midheading">Lumpsum Investment Ready Reckoner @if(isset($clientname)) <br/> For {{$clientname?$clientname:''}} @endif</h1>
                        

                        @if($enter_loan_details == 1)
                            @php 
                                $totalInterest = (($amount * $interest) / 100) * $period;
                                $ins = ($interest/100)/12;
                                $monthlyEmi = ($amount * $ins)/(1-pow((1+$ins),(-($period * 12))));
                                
                                $totalMonths = $period * 12;
                                $totalInterest = ($monthlyEmi * $totalMonths)-$amount;
                                $totalReplayment = $totalInterest + $amount;
                                $monthlyReturnOnSip = 0;
                                $monthlySipRequired = 0;
                                
                                if($expected_interest){
                                    $monthlyReturnOnSip = pow((1+($expected_interest/100)),(1/12))-1;
                                    $monthlySipRequired = ($totalReplayment * $monthlyReturnOnSip)/((1+$monthlyReturnOnSip)*(pow((1+$monthlyReturnOnSip),($totalMonths))-1));
                                }                        
                            @endphp
                            <div class="roundBorderHolder">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Loan Amount</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($amount)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Rate of Interest</strong>
                                            </td>
                                            <td>
                                                {{$interest}}  %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Loan Tenure</strong>
                                            </td>
                                            <td>
                                                {{$period}}  Years
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h1 class="midheading">Monthly EMI</h1>

                            <h5 class="outputSingleBox">
                                    ₹ {{custome_money_format($monthlyEmi)}}
                            </h5>
                            <br>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left;">Principal Repayment</td>
                                            <td>₹ {{custome_money_format($totalReplayment - $totalInterest)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Interest Repayment</td>
                                            <td>₹ {{custome_money_format($totalInterest)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Total Repayment</td>
                                            <td>₹ {{custome_money_format($totalReplayment)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h1 class="midheading">
                                Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %</h1>
                            <h5 class="outputSingleBox">
                                ₹  @if($monthlySipRequired)
                                        {{custome_money_format(round($monthlySipRequired))}}
                                    @else  
                                        N/A
                                    @endif
                            </h5>

                            <h6 class="mb-3 text-center">If you do an SIP for ₹ {{ custome_money_format($monthlySipRequired) }} ,ie., {{number_format((float)($monthlySipRequired/$monthlyEmi) * 100, 2, '.', '')}} % of the EMI amount, you will recover the full amount of EMI paid by you.</h6>
                        @else  
                            @php 
                                $monthlyEmi = $amount;
                
                                $totalMonths = $period * 12;
                                $totalReplayment = $monthlyEmi * $totalMonths;
                                
                                $monthlyReturnOnSip = 0;
                                $monthlySipRequired = 0;
                                if(!empty($expected_interest)){
                                    $monthlyReturnOnSip = pow((1+($expected_interest/100)),(1/12))-1;
                                    $monthlySipRequired = ($totalReplayment * $monthlyReturnOnSip)/((1+$monthlyReturnOnSip)*(pow((1+$monthlyReturnOnSip),($totalMonths))-1));
                                }
                            @endphp
                            <div class="roundBorderHolder">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Monthly EMI</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($monthlyEmi)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Loan Tenure</strong>
                                            </td>
                                            <td>
                                                {{$period}}  Years
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h1 class="midheading">Total Repayment</h1>

                            <h5 class="outputSingleBox">
                                    ₹ {{custome_money_format($totalReplayment)}}
                            </h5>
                            <br>

                            <h1 class="midheading">
                                Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %</h1>
                            <h5 class="outputSingleBox">
                                ₹  @if($monthlySipRequired)
                                        {{custome_money_format(round($monthlySipRequired))}}
                                    @else  
                                        N/A
                                    @endif
                            </h5>

                            <h6 class="mb-3 text-center">If you do an SIP for ₹ {{ custome_money_format($monthlySipRequired) }} ,ie., {{ number_format((($monthlySipRequired/$monthlyEmi) * 100),2)}} % of the EMI amount, you will recover the full amount of EMI paid by you.</h6>
                        @endif
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
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
        var base_url = "{{route('frontend.recover_emis_through_sip_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.recover_emis_through_sip__merge_download')}}" method="get">
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

