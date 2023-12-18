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
                        url: "{{ route('frontend.futureValueOfAnnualLumpsumInvestment_save') }}",
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
    $deferment_period = $investment_period - $payment_period;

    $totalinvestment = $amount*$payment_period;
    
    $rate1_percent  = pow((1+$interest1/100),1)-1;
    //AV21*(1+AV23)*(((1+AV23)^AV22)-1)/AV23
    $senario1_fund_amount = $amount*(1+$rate1_percent)*((pow((1+$rate1_percent),($payment_period))-1)/$rate1_percent);
    //AV33*(1+Q11%)^Q9
    $senario1_amount = $senario1_fund_amount*pow((1+$interest1/100),$deferment_period);
    $senario2_amount = 0;
        if (isset($interest2)){
        $rate2_percent  = pow((1+$interest2/100),1)-1;
        $senario2_fund_amount = $amount*(1+$rate2_percent)*((pow((1+$rate2_percent),($payment_period))-1)/$rate2_percent);
        $senario2_amount = $senario2_fund_amount*pow((1+$interest2/100),$deferment_period);
    }
@endphp
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
                        <h1 class="midheading">Annual Lumpsum Investment @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                        <div class="roundBorderHolder">
                            
                            <table class="table table-bordered text-center">
                                <tbody><tr>
                                    <td>
                                        <strong>Annual Investment</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Investment Period </strong>
                                    </td>
                                    <td>
                                        {{$investment_period?$investment_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Payment Period</strong>
                                    </td>
                                    <td>
                                        {{$payment_period?$payment_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="padding: 0">
                                        @if(isset($interest2))
                                            <table width="100%">
                                                <tbody><tr>
                                                    <td>
                                                        Scenario 1
                                                    </td>
                                                    <td>
                                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Scenario 2
                                                    </td>
                                                    <td>
                                                        {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                        @else
                                            {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                        @endif
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                            <h1 class="midheading">Expected Future Value</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    @if(isset($interest2))
                                        <tr>
                                            <td>
                                                Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </td>
                                            <td>
                                                Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                            </td>
                                            <td>
                                                <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($senario1_amount)}}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody></table>
                            </div>
                            
                            {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                           
                            @if(isset($report) && $report=='detailed')
                            <h1 class="midheading">Annual Investment & Projected Annual Investment Value</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    @if(isset($interest2))
                                        <tr>
                                            <th>Year</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            <th>Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        </tr>
                                        @php
                                            $previous_amount_int1 = $amount;
                                            $previous_amount_int2 = $amount;
                                        @endphp
    
                                        @for($i=1;$i<=$investment_period;$i++)
                                            @php
                                                if ($payment_period>=$i){
                                                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),$i)-1)/$rate1_percent);
                                                }else{
                                                    $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),1));
                                                }
                                                if ($payment_period>=$i){
                                                    $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),$i)-1)/$rate2_percent);
                                                }else{
                                                    $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),1));
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    @if($i<=$payment_period)
                                                        ₹ {{$amount?custome_money_format($amount):0}}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                                <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                            </tr>
                                        @endfor
                                    @else
                                        <tr>
                                            <th>Year</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        </tr>
                                        @php
                                            $previous_amount_int1 = $amount;
                                        @endphp
    
                                        @for($i=1;$i<=$investment_period;$i++)
                                            @php
                                                if ($payment_period>=$i){
                                                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),$i)-1)/$rate1_percent);
                                                }else{
                                                    $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),1));
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    @if($i<=$payment_period)
                                                        ₹ {{$amount?custome_money_format($amount):0}}
                                                    @else
                                                    --
                                                    @endif
                                                </td>
                                                <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                            </tr>
                                        @endfor
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="description-text">
                        @php
                            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_Annual_Lump_sum_Investment')->first();
                            if(!empty($note_data2)){
                        @endphp
                            {!!$note_data2->description!!}
                        @php } @endphp
                        Report Date : {{date('d/m/Y')}}
                        </div>
                            
                        
                        
                        @if(isset($is_graph) && $is_graph)
                        
                            <h1 class="midheading">Graphic Representation</h1>
                            <div style="text-align: center;" class="graphView">
                                <img src="{{$pie_chart2}}" class="graphViewImg">
                            </div>
                        @endif

                        
                        


                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center viewBelowBtn">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.futureValueOfAnnualLumpsumInvestment_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.futureValueOfLumpsumInvestment_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.futureValueOfAnnualLumpsumInvestment_merge_download')}}" method="get">
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

