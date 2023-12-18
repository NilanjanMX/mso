@extends('layouts.frontend')
@section('js_after')
    
    
    <style>
        
    </style>

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
    
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    
                    
                <div class="outputTableHolder">

                    <h1 class="midheading">Portfolio Projection Report @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h1>
                    
                    @if($enter_loan_details == 1)
                        @php 
                            $clienPortValue = $amount;
                            $lumpsumInvest = $lumpsum;
                            $currentMonthlySip = $sip;
                            $expectedRateOfReturn = $rate;
                            $tenure = $period;
                            $totalMonths = $tenure * 12;
                            $returnAnual = $expectedRateOfReturn / 100;
                            $returnMonthly = pow((1+$returnAnual),(1/12))-1;
                            $currentPort = $clienPortValue * pow((1+$returnAnual),$tenure);
                            $anualLumpsum = (1+$returnAnual) * $lumpsumInvest * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                            $sipFv = (1+$returnMonthly) * $currentMonthlySip * ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                            
                                                   
                        @endphp
                        <div class="roundBorderHolder">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Current Portfolio Value</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($clienPortValue)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Lumpsum Investment Every year</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($lumpsumInvest)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Monthly SIP</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($currentMonthlySip)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td>
                                        {{$expectedRateOfReturn}}  %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{$tenure}}  Years
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

                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Expected Portfolio Value</h5>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered" >
                            <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Portfolio</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($currentPort)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Annual Lumpsum</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($anualLumpsum)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>SIP</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($sipFv)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($sipFv + $currentPort + $anualLumpsum)}}
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </div>

                        @if(isset($report) && $report=='detailed')
                       
                            <h5 class="text-center" style="margin-bottom:20px;">Annual Investment & Expected Fund Value</h5>
                            <div class="roundBorderHolder">
                         <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                                <tr>
                                        <th>Year</th>
                                        <th>Annual Investment</th>
                                        <th>Cumulative Investment</th>
                                        <th>Expected Fund Value</th>
                                </tr>
                                @php 
                                    $annual_investment_total = 0;
                                    $expected_fund_value = 0;
                                @endphp
                                @for($i=1;$i<=$period;$i++)
                                    @php
                                        if($i == 1){
                                            $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                        }else{
                                            $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                        }
                                        $annual_investment_total = $annual_investment_total + $annual_investment;

                                        $eoy_value  = $clienPortValue * pow((1+$expectedRateOfReturn/100),$i);

                                        $eoy_value1 = (1+$expectedRateOfReturn/100)*$lumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
                                        $rateofreturn = pow((1+$expectedRateOfReturn/100),(1/12))-1;
                                        $eoy_value2 = (1+$rateofreturn)*$currentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
                                    @endphp
                                <tr>
                                        <td>{{$i}} </td>
                                        <td>₹ {{custome_money_format($annual_investment)}}</td>
                                        <td>₹ {{custome_money_format($annual_investment_total)}}</td>
                                        <td>₹ {{custome_money_format($eoy_value + $eoy_value1 +$eoy_value2)}}</td>
                                </tr>
                                @endfor
                            </tbody>
                         </table>
                            </div>
                        @endif
                    @else
                        @php
                            $clienPortValue = $amount;
                            $lumpsumInvest = $ilumpsum;
                            $inclumpsumInvest = $lumpsum;
                            $icurrentMonthlySip = $isip;
                            $currentMonthlySip = $sip;
                            $expectedRateOfReturn = $rate;
                            $tenure = $period;
                            $totalMonths = $tenure * 12;
                            $returnAnual = $expectedRateOfReturn / 100;
                            $returnMonthly = pow((1+$returnAnual),(1/12))-1;
                            $currentPort = $clienPortValue * pow((1+$returnAnual),$tenure);
                            $anualLumpsum = (1+$returnAnual) * $lumpsumInvest * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                            $sipFv = (1+$returnMonthly) * $currentMonthlySip * ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                            $IncAnualLumpsum = (1+$returnAnual) * ($lumpsumInvest+$inclumpsumInvest) * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                            $incSipFv = (1+$returnMonthly) * ($currentMonthlySip + $icurrentMonthlySip)* ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                        @endphp
                        <div class="roundBorderHolder">
                        <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Current Portfolio Value</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($clienPortValue)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Lumpsum Investment Every Year</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($lumpsumInvest)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Increase in Annual Lumpsum</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($inclumpsumInvest)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Monthly SIP</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($currentMonthlySip)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Addition in SIP(New)</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($icurrentMonthlySip)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td>
                                        {{$expectedRateOfReturn}}  %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{$tenure}}  Years
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

                        <h5 class="mb-3 mt-1 text-center">Expected Portfolio Value</h5>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                            <thead>
                                <th></th>
                                <th>Current Scenario</th>
                                <th>Incremental Scenario</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Portfolio</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($currentPort)}}
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($currentPort)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Annual Lumpsum</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($anualLumpsum)}}
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($IncAnualLumpsum)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>SIP</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($sipFv)}}
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($incSipFv)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($sipFv + $currentPort + $anualLumpsum)}}
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($incSipFv + $currentPort + $IncAnualLumpsum)}}
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </div>

                        @if(isset($report) && $report=='detailed')
                       
                            <h5 class="text-center">Annual Investment & Expected Fund Value</h5>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center" style="background: #fff;">
                                <tbody>
                                    <tr>
                                        <th rowspan="2">Year</th>
                                        <th colspan="3">Current Scenario</th>
                                        <th colspan="3">Incremental Scenario</th>
                                    </tr>
                                    <tr>
                                        <th>Annual Investment</th>
                                        <th>Cumulative Investment</th>
                                        <th>Expected Fund Value</th>
                                        <th>Annual Investment</th>
                                        <th>Cumulative Investment</th>
                                        <th>Expected Fund Value</th>
                                    </tr>
                                    @php 
                                        $annual_investment_total = 0;
                                        $ic_annual_investment_total = 0;
                                    @endphp
                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            if($i == 1){
                                                $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                                $ic_annual_investment =  $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                            }else{
                                                $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                                $ic_annual_investment = $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                            }
                                            $annual_investment_total = $annual_investment_total + $annual_investment;
                                            $ic_annual_investment_total = $ic_annual_investment_total + $ic_annual_investment;

                                            $rateofreturn = pow((1+$expectedRateOfReturn/100),(1/12))-1;

                                            $eoy_value  = $clienPortValue * pow((1+$expectedRateOfReturn/100),$i);

                                            $eoy_value1 = (1+$expectedRateOfReturn/100)*$lumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
                                            
                                            $eoy_value2 = (1+$rateofreturn)*$currentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);

                                            $eoy_value3 = (1+$expectedRateOfReturn/100)*$inclumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));

                                            $eoy_value4 = (1+$rateofreturn)*$icurrentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
                                        @endphp
                                    <tr>
                                            <td>{{$i}} </td>
                                            <td>₹ {{custome_money_format($annual_investment)}}</td>
                                            <td>₹ {{custome_money_format($annual_investment_total)}}</td>
                                            <td>₹ {{custome_money_format($eoy_value + $eoy_value1 +$eoy_value2)}}</td>
                                            <td>₹ {{custome_money_format($ic_annual_investment)}}</td>
                                            <td>₹ {{custome_money_format($ic_annual_investment_total)}}</td>
                                            <td>₹ {{custome_money_format($eoy_value + $eoy_value1 + $eoy_value2 + $eoy_value3 +$eoy_value4)}}</td>
                                    </tr>
                                    @endfor
                                </tbody>
                             </table>
                            </div>
                        @endif
                    @endif
                        {{-- @if($is_graph)
                            <h1 class="midheading">Graphic Representation</h1>
                            <div style="text-align: center;" class="graphView">
                                <img src="{{$pie_chart2}}" style="width: 800px">
                            </div>
                        @endif --}}

                        
                        <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    {{-- @endif --}}
                    @include('frontend.calculators.suggested.output')

                    </div>
                

                    <div class="text-center" style="padding:83px 0 20px 0;">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.portfolio_projection_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.portfolio_projection_output_pdf')}}";
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
