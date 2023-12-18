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
                        url: "{{ route('frontend.debt_fund_trade_off_save') }}",
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

    <div class="banner">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div> --}}
        </div>
    </div>

    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h5 class="midheading">Debt Fund (Hold/Sell) Benefit Calculation @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h5>
                            
                        <div class="roundBorderHolder">
                            @if($formType == 1)
                                @php 
                                if($optionType == 'one'){
                                $densu = explode('/',$purchase);
                                //dd($densu);
                                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                                $purchaseDate = strtotime($dtNow);
                                $densu = explode('/',$redeem);
                                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                                $redeemDate = strtotime($dtNow);
                                $invest = $invest;
                                $current = $current;
                                }
                                else{
                                $densu = explode('/',$purchase1);
                                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                                $purchaseDate = strtotime($dtNow);
                                $densu = explode('/',$redeem1);
                                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                                $redeemDate = strtotime($dtNow);
                                $invest = $units * $nav;
                                $current = $units * $currentnav;
                                $redeem = $redeem1;
                                $redeem = $redeem1;
                                $purchase = $purchase1;
                                }
                                
                                $threeYears =date('m/d/Y', strtotime('+3 years +1days',$purchaseDate));
                                $allThreeYearsDateSplit = explode('/',$threeYears);
                                $mainThreeYears = $allThreeYearsDateSplit[1].'/'.$allThreeYearsDateSplit[0].'/'.$allThreeYearsDateSplit[2];
                                $dateDiff =$redeemDate- $purchaseDate;
                                $totalDays = round($dateDiff/(60 * 60 * 24));
                                $taxShortTerm = $current - $invest;
                                $taxPayable =  $taxShortTerm * $shortterm/100; 
                                $netInHand = $current - $taxPayable;
                                $postTax = pow(($netInHand/$invest),(365/$totalDays))-1;
                                $postTax = $postTax * 100;
                                $remainingDays = round((strtotime($threeYears)-$redeemDate)/(60 * 60 * 24));
                                $expectedReturn = pow((1+$expected/100),(1/365))-1;
                                $expectedValue = $current * pow((1+$expectedReturn),$remainingDays);
                                $indexedCost = $invest*pow((1+$indexation/100),$ltcg);
                                $taxValue = $expectedValue - $indexedCost;
                                $taxPayableNew = $taxValue * ($longterm / 100);
                                $netInHandNew = $expectedValue-$taxPayableNew;
                                $remainingDaysNew = round((strtotime($threeYears)-$purchaseDate) / (60 * 60 * 24));
                                //dd($remainingDaysNew);
                                $postTaxPayable = pow(($netInHandNew/$invest),(365/$remainingDaysNew))-1;
                                $postTaxPayable = $postTaxPayable * 100;
                                $irr =(pow(($netInHandNew/$netInHand),(365/$remainingDays))-1)*100;
                                @endphp
                                @if($taxation != "Long Term")<strong style="text-align:center; font-size:16px;">If investment is redeemed TODAY</strong><br/><br/>@endif
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Purchase Date</strong>
                                            </td>
                                            <td>
                                                {{$purchase}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Redemption Date</strong>
                                            </td>
                                            <td>
                                                {{$redeem}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Investment Amount</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($invest)}}
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Current Market Value</strong>
                                            </td>
                                            <td>
                                            ₹ {{custome_money_format($current)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Taxable Short Term Capital Gain</strong>
                                            </td>
                                            <td>
                                            ₹ {{custome_money_format($current-$invest)}}
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <strong>Tax Payable</strong>
                                            </td>
                                            <td>
                                            ₹ {{custome_money_format($taxPayable)}}
                                            </td>
                                        </tr>
                                        
                                        
                                        
                                        <tr>
                                            <td>
                                                <strong>Net In Hand</strong>
                                            </td>
                                            <td>
                                            ₹ {{custome_money_format($netInHand)}}
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>
                                                <strong>Post-Tax Yield</strong>
                                            </td>
                                            <td>
                                            {{sprintf('%0.2f', $postTax)}} %
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                @if($taxation != "Long Term")
                                <br/>
                                <strong style="text-align:center;font-size:16px;">If investment is redeemed on {{$mainThreeYears}}</strong><br/>
                                <strong style="text-align:center;font-size:16px;">i.e., after  {{$remainingDays}} days, it will qualify as LTCG giving you benefit of Long Term Taxation.</strong><br/><br/>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Redemption Date</strong>
                                            </td>
                                            <td>
                                                {{$mainThreeYears}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Investment Amount</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($invest)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Expected Return for Remaining Period</strong>
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f',$expected)}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Assumed Indexation Rate</strong>
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f',$indexation)}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Expected Redemption Amount</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($expectedValue)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Long Term Capital Gain</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($expectedValue - $invest)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Taxable Long Term Capital Gain</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($taxValue)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Tax Payable</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($taxPayableNew)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Net In Hand</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($expectedValue-$taxPayableNew)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Post-Tax Yield</strong>
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f', $postTaxPayable)}} %
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @endif

                                @else
                                @php
                                $noOfYears1= $invyear + $invmonth/12;
                                $noOfYears2= $after;
                                $matuityAmount = $initial * pow((1+$expected/100),$noOfYears1);
                                $matuityAmount3 = $initial * pow((1+$expected/100),$noOfYears2);
                                $capGain = $matuityAmount-$initial;
                                $capGain3 = $matuityAmount3-$initial;
                                if($noOfYears1 < $noOfYears2)
                                $indexCostInv = $initial;
                                else
                                $indexCostInv = $initial * pow((1+$expected/100),$noOfYears1);
                                
                                $indexCostInv3 = $initial * pow((1+$assumed/100),$noOfYears2);
                                $taxPayable = ($matuityAmount - $indexCostInv)*($shortterm/100);
                                $taxPayable3 = ($matuityAmount3 - $indexCostInv3)*($longterm/100);
                                $taxReturn = $capGain - $taxPayable;
                                $taxReturn3 = $capGain3 - $taxPayable3;
                                $postTax = (pow((($initial+$taxReturn)/$initial),(1/$noOfYears1))-1)*100;
                                $postTax3 = (pow((($initial+$taxReturn3)/$initial),(1/$noOfYears2))-1)*100;
                                $balYear = $noOfYears2-$noOfYears1;
                                $irrBal = pow((($initial + $taxReturn3)/($initial + $taxReturn)),(1/$balYear))-1;
                                $irrBal = $irrBal * 100;
                                $bd38 = $after * 12;
                                $bd39 = $invyear * 12 + $invmonth;
                                @endphp
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td><strong>Investment Period</strong></td>
                                            <td><strong>{{$invyear}} Yr {{$invmonth}} Months</strong></td>
                                            <td><strong>If Held For {{$after}} Years</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Initial Investment</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($initial)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($initial)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Expected Return</strong>
                                            </td>
                                            <td>
                                                {{$expected}} %
                                            </td>
                                            <td>
                                                {{$expected}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Expected Maturity Amount</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($matuityAmount)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($matuityAmount3)}}
                                            </td>
                                        </tr>
                                        
                                    <tr>
                                            <td>
                                                <strong> Capital Gain</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($capGain)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($capGain3)}}
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <strong>Assumed Indexation Rate</strong>
                                            </td>
                                            <td>
                                                @if($noOfYears1 > $noOfYears2)
                                                {{sprintf('%0.2f',$assumed)}}  %
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                            <td>
                                                
                                                {{sprintf('%0.2f',$assumed)}}  %
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong> Indexed Cost of Investment</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($indexCostInv)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($indexCostInv3)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong> Taxable Income</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($matuityAmount-$indexCostInv)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($matuityAmount3-$indexCostInv3)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Applicable Tax Rate</strong>
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f',$shortterm)}} %
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f',$longterm)}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong> Taxable Income</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($taxPayable)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($taxPayable3)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong> Post-Tax Returns (Rs)</strong>
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($capGain-$taxPayable)}}
                                            </td>
                                            <td>
                                                ₹ {{custome_money_format($capGain3-$taxPayable3)}}
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <strong> Post-Tax IRR (%)</strong>
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f', $postTax)}} %
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f', $postTax3)}} %
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                                
                                
                            @endif

                        </div>
                                
                        @if($is_note == 1 && isset($note) && !empty($note))
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
                        <p style="text-align: left">
                            @if($formType != 1)  * If the investment horizon is increased from {{$invyear}}Year {{$invmonth}} Mnths to {{$after}} Years<br>
                            effective post-tax yield for the additional {{$bd38-$bd39}} month's period of investment will be {{sprintf('%0.2f', $irrBal)}} %. 
                            @else
                            @if($taxation != "Long Term")
                            * If the redemption is made on {{$mainThreeYears}},i.e., after  {{$remainingDays}} days, <br/>
                            the effective post-tax yield for the remaining period of investment will be {{sprintf('%0.2f', $irr)}} % @endif
                            @endif
                            
                            <br/>
                            * The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}<br>
                        </p>
    
                        @include('frontend.calculators.suggested.output')
                        </div>
                    </div>
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
        var base_url = "{{route('frontend.debt_fund_trade_off_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.debt_fund_trade_off_merge_download')}}" method="get">
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

