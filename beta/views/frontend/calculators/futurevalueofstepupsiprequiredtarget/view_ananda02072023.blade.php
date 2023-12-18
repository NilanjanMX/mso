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
                        url: "{{ route('frontend.futureValueOfStepUpSIPRequiredTargetOutputSave') }}",
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
        nostyleshow {
            display: none;
        }
    
        main header{
            display: none;
        }
        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 30px;
        }
    
        table th,
        table td {
            text-align: center;
            border: 1px solid #b8b8b8;
            padding: 5px 20px;
            font-weight: normal;
            color: #000;
        }
    
        table {
            margin: 0;
        }
    
        table th {
            font-weight: bold;
            background: #a9f3ff;
        }
    
        .table-bordered th, .table-bordered td{
            padding: 10px;
            font-size: 18px;
        }
    
        h1 {
            font-size: 20px !important;
            color: #131f55 !important;
            margin-bottom: 0 !important;
            margin-top: 15px !important;
            width: 100%;
        }
        .page-break {
            page-break-after: always;
        }
    
    
        @page {
            margin-top: 160px
        }
    
        footer p{
            display: none;
        }
    
        p{
            text-align: left;
        }
    
        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: 42%;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
            display: none;
        }
        main{
            width: 100% !important;
        }
    </style>
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

    //rate2 = (1+Q11%)^(1/12)-1 (Q10 = senario 2)
    $number_of_months = $period*12;
    $rate1_percent = pow((1+($interest1/100)), (1/12))-1;
     //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
    $ap1 = (1+$rate1_percent)*1*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
    //(AV36/(Q13%-Q11%))*((1+Q13%)^(Q10)-(1+Q11%)^(Q10))
    $senario1_stepup_fund_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
    //Q9/AV38
    $senario1_stepup_monthly_amount = $amount / $senario1_stepup_fund_amount;
    //(AV40*12)*(((1+Q11%)^(Q10)-1)/((1+Q11%)-1))
    $senario1_total_investment_amount = ($senario1_stepup_monthly_amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1));

    //senario1_amount = (AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
    //(AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
   $senario1_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
    if (isset($interest2)){
        $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
         //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
        $ap2 = (1+$rate2_percent)*1*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
        //(AV36/(Q13%-Q11%))*((1+Q13%)^(Q10)-(1+Q11%)^(Q10))
        $senario2_stepup_fund_amount = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$period)-pow((1+$annual_increment/100),$period));
        //Q9/AV38
        $senario2_stepup_monthly_amount = $amount / $senario2_stepup_fund_amount;
        //(AV40*12)*(((1+Q11%)^(Q10)-1)/((1+Q11%)-1))
        $senario2_total_investment_amount = ($senario2_stepup_monthly_amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1));

    }

//(Q8*12)*(((1+Q9%)^(Q10)-1)/((1+Q9%)-1))
$total_investment = ($amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1))

@endphp
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    
                    
                <div class="outputTableHolder">

                    <h1 class="midheading">Step-Up SIP Required For Target Future Value @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>SIP Period  </strong>
                                    </td>
                                    <td>
                                        {{$period?$period:0}} Years
                                    </td>
                                </tr>
                                @if(isset($annual_increment) && $annual_increment!='')
                                    <tr>
                                        <td>
                                            <strong> Step-Up % Every Year  </strong>
                                        </td>
                                        <td>
                                            {{$annual_increment?number_format($annual_increment, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                @endif
                                @if(!isset($interest2))
                                    <tr>
                                        <td >
                                            <strong>Assumed Rate of Return </strong>
                                        </td>
                                        <td>
                                            {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                @endif
        
                                </tbody></table>
                                </div>
                                @if(isset($is_note))
                                <h1 class="midheading">Comments</h1>
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <tbody>
                                            <tr>
                                                <td>{{$note}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
        
                                <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Monthly SIP Required</h1>
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
                                                <strong>₹ {{custome_money_format($senario1_stepup_monthly_amount)}} </strong>
                                            </td>
                                            <td>
                                                <strong>₹ {{custome_money_format($senario2_stepup_monthly_amount)}} </strong>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($senario1_stepup_monthly_amount)}}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                </div>
        
                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Total Investment</h1>
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
                                            <strong>₹ {{custome_money_format($senario1_total_investment_amount)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{custome_money_format($senario2_total_investment_amount)}} </strong>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                        </td>
                                        <td>
                                            ₹ {{custome_money_format($senario1_total_investment_amount)}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            </div>
        
                            @if(isset($report) && $report=='detailed')
                            <h5 class="text-center">
                                Year-Wise Projected Value</h5>
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center" style="background: #fff;">
                                        <tbody>
                                        @if(isset($interest2))
                                            <tr>
                                                <th rowspan="2" style="vertical-align: middle;">Year</th>
                                                <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            </tr>
                                            <tr>
                                                <th>Monthly Investment</th>
                                                <th>Annual Investment</th>
                                                <th>Year End Value</th>
                                                <th>Monthly Investment</th>
                                                <th>Annual Investment</th>
                                                <th>Year End Value</th>
                                            </tr>
                                            @php
                                                $previous_amount_int1 = $senario1_stepup_monthly_amount;
                                                $previous_amount_int2 = $senario2_stepup_monthly_amount;
                                                $change_amount = $senario1_stepup_monthly_amount;
                                                $change_amount2 = $senario2_stepup_monthly_amount;
                                            @endphp
        
                                            @for($i=1;$i<=$period;$i++)
                                                @php
                                                    //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                                    $ap1 = (1+$rate1_percent)*$senario1_stepup_monthly_amount*((pow((1+$rate1_percent),12)-1)/$rate1_percent);
                                                    //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                                    $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$i)-pow((1+$annual_increment/100),$i));
                                                    //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                                    if ($i==1){
                                                        $change_amount = $senario1_stepup_monthly_amount;
                                                    }else{
                                                        $change_amount = $change_amount+($change_amount*$annual_increment/100);
                                                    }
        
                                                //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                                    $ap2 = (1+$rate2_percent)*$senario2_stepup_monthly_amount*((pow((1+$rate2_percent),12)-1)/$rate2_percent);
                                                    //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                                    $previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$i)-pow((1+$annual_increment/100),$i));
                                                    //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                                    if ($i==1){
                                                        $change_amount2 = $senario2_stepup_monthly_amount;
                                                    }else{
                                                        $change_amount2 = $change_amount2+($change_amount2*$annual_increment/100);
                                                    }
        
                                                @endphp
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>
                                                        ₹ {{$change_amount?custome_money_format($change_amount):0}}
                                                    </td>
                                                    <td>
                                                        ₹ {{$change_amount?custome_money_format($change_amount*12):0}}
                                                    </td>
                                                    <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                                    <td>
                                                        ₹ {{$change_amount2?custome_money_format($change_amount2):0}}
                                                    </td>
                                                    <td>
                                                        ₹ {{$change_amount2?custome_money_format($change_amount2*12):0}}
                                                    </td>
                                                    <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr>
                                                <th rowspan="2">Year</th>
                                                <th colspan="3"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            </tr>
                                            <tr>
                                                <th>Monthly Investment</th>
                                                <th>Annual Investment</th>
                                                <th>Year End Value</th>
                                            </tr>
                                            @php
                                                $previous_amount_int1 = $senario1_stepup_monthly_amount;
                                                $change_amount = $senario1_stepup_monthly_amount;
                                            @endphp
        
                                            @for($i=1;$i<=$period;$i++)
                                                @php
                                                    //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                                    $ap1 = (1+$rate1_percent)*$senario1_stepup_monthly_amount*((pow((1+$rate1_percent),12)-1)/$rate1_percent);
                                                    //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                                    $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$i)-pow((1+$annual_increment/100),$i));
                                                    //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                                    if ($i==1){
                                                        $change_amount = $senario1_stepup_monthly_amount;
                                                    }else{
                                                        $change_amount = $change_amount+($change_amount*$annual_increment/100);
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>
                                                        ₹ {{$change_amount?custome_money_format($change_amount):0}}
                                                    </td>
                                                    <td>
                                                        ₹ {{$change_amount?custome_money_format($change_amount*12):0}}
                                                    </td>
                                                    <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
        
                                                </tr>
                                            @endfor
                                        @endif
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                    <br/>

                            
                            <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                            @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
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
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')}}";
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

