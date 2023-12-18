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
                        url: "{{ route('frontend.sipNeedBasedReadyReckonerOutputSave') }}",
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
    @php
    //(1+D34)^(1/12)-1
    $return1 = (pow((1+$interest1/100),(1/12))-1);
    // (AT35*BD35)/((1+BD35)*((1+BD35)^(AY35*12)-1))/1000
    //$price1 = ((1+$return1)*$investment*((pow((1+$return1),($period1*12))-1)/$return1));
    $price1 = ($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period1*12))-1))/1000;
    @endphp
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                <div class="outputTableHolder">

                    <h1 class="midheading">SIP Need Based Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-left">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($investment)}}
                                    </td>
                                </tr>
                            </tbody></table>
                        </div>
                            
                            <h1 class="midheading">Monthly SIP Required</h1>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered text-center">
                            <tbody>
    
                                <tr>
                                    <td>&nbsp;</td>
                                    <td colspan="5"><strong>SIP Period (Years)</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Rate of Return</strong></td>
                                    <td><strong>{{$period1?$period1:''}}</strong></td>
                                    @php if($period2!=''){ @endphp
                                    <td><strong>{{$period2?$period2:''}}</strong></td>
                                    @php } @endphp
                                    @php if($period3!=''){ @endphp
                                    <td><strong>{{$period3?$period3:''}}</strong></td>
                                    @php } @endphp
                                    @php if($period4!=''){ @endphp
                                    <td><strong>{{$period4?$period4:''}}</strong></td>
                                    @php } @endphp
                                    @php if($period5!=''){ @endphp
                                    <td><strong>{{$period5?$period5:''}}</strong></td>
                                    @php } @endphp
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                                    </td>
                                    @php if($period2!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period3!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period4!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period5!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                </tr>
                                @php if($interest2!=''){ @endphp
                                <tr>
                                    <td>
                                        <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                                    </td>
                                    @php if($period2!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period3!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period4!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period5!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                </tr>
                                @php } @endphp
                                @php if($interest3!=''){ @endphp
                                <tr>
                                    <td>
                                        <strong>{{$interest3?number_format((float)$interest3, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                                    </td>
                                    @php if($period2!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period3!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period4!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                    @php if($period5!=''){ @endphp
                                    <td>
                                        <strong>₹ {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                                    </td>
                                    @php } @endphp
                                </tr>
                                @php } @endphp
                                <tr>
                                    <td colspan="6" style="text-align: right;padding-right: 30px;"><strong>(₹ in Thousands)</strong></td>
                                </tr>
    
                            </tbody>
                        </table>
                        </div>
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                        <p class="text-left">
                            * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                            *Returns are not guaranteed. The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}
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
        var base_url = "{{route('frontend.sipNeedBasedReadyReckonerOutputPdf')}}";
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
                    <form target="_blank" action="{{route('frontend.sipNeedBasedReadyReckonerMergeDownload')}}" method="get">
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

