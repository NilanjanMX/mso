@extends('layouts.frontend')
@section('js_after')
    
    @include('frontend.calculators.common.view_style')
    
@endsection
@php
        $total_number_of_months = $sip_period*12;

        //(1+Q11%)^(1/12)-1
        $rate1_percent  = pow((1+$interest1/100),(1/12))-1;
        //Q8/((1+Q12%)^Q10)
        $senario1_fund_amount = $amount/(pow((1+$interest1/100),$deferment_period)) ;
        //(AV34*AV32)/((1+AV32)*((1+AV32)^(AV31)-1))
        $senario1_monthly_sip_amount = ($senario1_fund_amount*$rate1_percent)/((1+$rate1_percent)*(pow((1+$rate1_percent),$total_number_of_months)-1));
        //AV36*AV31
        $senario1_totalinvestment = $senario1_monthly_sip_amount*$total_number_of_months;

        $senario2_amount = 0;
         if (isset($interest2)){
            $rate2_percent  = pow((1+$interest2/100),(1/12))-1;
            //Q8/((1+Q12%)^Q10)
            $senario2_fund_amount = $amount/(pow((1+$interest2/100),$deferment_period)) ;
            //(AV34*AV32)/((1+AV32)*((1+AV32)^(AV31)-1))
            $senario2_monthly_sip_amount = ($senario2_fund_amount*$rate2_percent)/((1+$rate2_percent)*(pow((1+$rate2_percent),$total_number_of_months)-1));
            //AV36*AV31
            $senario2_totalinvestment = $senario2_monthly_sip_amount*$total_number_of_months;
            }
    @endphp
@section('content')
    
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
                <div class="col-md-10 offset-md-1 text-center">
                    
                    
                <div class="outputTableHolder">

                    <h1 class="midheading">Limited Period SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Need Based Calculator @endif</h1>
                    
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
                                    <strong>SIP Period</strong>
                                </td>
                                <td>
                                    {{$sip_period?$sip_period:0}} Years
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Deferment Period </strong>
                                </td>
                                <td>
                                    {{$deferment_period?$deferment_period:0}} Years
                                </td>
                            </tr>
    
                            </tbody></table>
                        <h1 class="midheading">Monthly SIP Required</h1>
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
                                        <strong>₹ {{custome_money_format($senario1_monthly_sip_amount)}} </strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario2_monthly_sip_amount)}} </strong>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th>
                                        @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                    </th>
                                    <td>
                                        <strong> ₹ {{custome_money_format($senario1_monthly_sip_amount)}}</strong>
                                    </td>
                                </tr>
                            @endif
                            </tbody></table>
                        </div>
    
                            @if(isset($note) && $note!='')
                            <h1 class="midheading">Comments</h1>
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
    
                        <h1 class="midheading">Total Investment</h1>
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
                                        <strong> ₹ {{custome_money_format($senario1_totalinvestment)}}</strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario2_totalinvestment)}}</strong>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th>
                                         @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario1_totalinvestment)}}</strong>
                                    </td>
                                </tr>
                            @endif
                            </tbody></table>
                        </div>
                        @if(isset($report) && $report=='detailed')
                            <h1 class="midheading">Year-Wise Projected Value</h1>
                        <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    @if(isset($interest2))
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle">Year</th>
                                            <th colspan="2">
                                                Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </th>
                                            <th colspan="2">
                                                Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Annual Investment</th>
                                            <th>Year End Value</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value</th>
                                        </tr>
                                        @php
                                            $previous_amount_int1 = $amount;
                                            $previous_amount_int2 = $amount;
                                        @endphp
                                        @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                            @php
        
                                                if ($sip_period>=$i){
                                                  //(1+BD70)*BB70*(((1+BD70)^(AZ70*12)-1)/BD70)
                                                  $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_sip_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                                }else{
                                                 //(BF70*(1+BD71)^12)
                                                  $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                                }
                                                if ($sip_period>=$i){
                                                  $previous_amount_int2 = (1+$rate2_percent)*$senario2_monthly_sip_amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                                }else{
                                                 //(BE69*(1+BC70)^12)
                                                  $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    @if($i<=$sip_period)
                                                        ₹ {{$senario1_monthly_sip_amount?custome_money_format($senario1_monthly_sip_amount*12):0}}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                                <td>
                                                    @if($i<=$sip_period)
                                                        ₹ {{$senario2_monthly_sip_amount?custome_money_format($senario2_monthly_sip_amount*12):0}}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                            </tr>
                                        @endfor
                                    @else
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle">Year</th>
                                            <th colspan="2">
                                                @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Annual Investment</th>
                                            <th>Year End Value</th>
                                        </tr>
                                        @php
                                            $previous_amount_int1 = $amount;
                                        @endphp
                                        @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                            @php
        
                                                if ($sip_period>=$i){
                                                  //(1+BD70)*BB70*(((1+BD70)^(AZ70*12)-1)/BD70)
                                                  $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_sip_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                                }else{
                                                 //(BF70*(1+BD71)^12)
                                                  $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    @if($i<=$sip_period)
                                                        ₹ {{$senario1_monthly_sip_amount?custome_money_format($senario1_monthly_sip_amount*12):0}}
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
                        
                        @if(isset($is_graph) && $is_graph)
                        <br>
                            <h1 class="midheading">Graphic Representation</h1>
                            <div style="text-align: center;" class="graphView">
                                <img src="{{$pie_chart2}}" class="graphViewImg">
                            </div>
                        @endif

                        
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
                                <a href="{{route('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')}}";
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
