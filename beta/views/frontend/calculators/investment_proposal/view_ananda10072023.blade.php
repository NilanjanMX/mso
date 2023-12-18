
@extends('layouts.frontend')
@section('js_after')


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

    <div class="banner styleApril">
        <div class="container">
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">                    

                    <div class="outputTableHolder">
                        <h1 class="midheading">All-in-one Investment Proposal @if(isset($client_name) && !empty($client_name)) <br> For {{$client_name?$client_name:''}}  @else  @endif</h1>
                        
                        @if($lumpsum_checkbox || $sip_checkbox || $stp_checkbox || $swp_checkbox)
                            <h1 class="midheading">Mutual Fund Schemes</h1>
                        @endif
    
                        @if($lumpsum_checkbox)
                            @if(count($lumpsum_form_list))
                                <h1 class="midheading text-left">Lumpsum Investment</h1>
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">Asset Class</th>
                                            <th style="width: 22%;">Investment Amount</th>
                                            <th style="width: 22%;">Investment Period (Yrs)</th>
                                            <th style="width: 22%;">Assumed Return</th>
                                            <th style="width: 22%;">Expected Future&nbsp;Value</th> 
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($lumpsum_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td style="">{{isset($value['asset_class'])?$value['asset_class']:""}}</td>
                                                    <td>₹&nbsp;{{isset($value['investment_amount'])?custome_money_format($value['investment_amount']):""}}</td>
                                                    <td>{{isset($value['investment_period'])?$value['investment_period']:""}}</td>
                                                    <td>{{isset($value['assumed_rate_of_return'])?number_format((float)$value['assumed_rate_of_return'], 2, '.', ''):""}} %</td>
                                                    <td>₹&nbsp;{{isset($value['actual_end_value'])?custome_money_format($value['actual_end_value']):""}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                                <h1 class="midheading text-left">Suggested Schemes</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($lumpsum_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 30%;">Scheme</th>
                                            @if($lumpsum_category_checkbox)
                                                <th style="">Category</th>
                                            @endif
                                            <th style="width: 15%;">Investment Amount</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($lumpsum_table_list as $key => $value)
                                                <tr>
                                                    @if($lumpsum_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    @if($lumpsum_category_checkbox)
                                                        <td style="">{{$value['category']}}</td>
                                                    @endif
                                                    <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($sip_checkbox)
                            @if(count($sip_form_list))
                                <h1 class="midheading text-left">SIP Investment</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">Asset Class</th>
                                            <th style="width: 15%;">SIP Amount</th>
                                            <th style="width: 22%;">Frequency</th>
                                            <th style="width: 22%;">SIP Period</th>
                                            <th style="width: 22%;">Investment Period</th>
                                            <th style="width: 22%;">Assumed Return</th>
                                            <th style="width: 22%;">Total Investment</th>
                                            <th style="width: 22%;">Expected Future&nbsp;Value</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($sip_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td style="">{{isset($value['asset_class'])?$value['asset_class']:""}}</td>
                                                    <td>₹&nbsp;{{isset($value['sip_amount'])?custome_money_format($value['sip_amount']):""}}</td>
                                                    <td>{{isset($value['frequency'])?$value['frequency']:""}}</td>
                                                    <td>{{isset($value['sip_period'])?$value['sip_period']:""}}</td>
                                                    <td>{{isset($value['investment_period'])?$value['investment_period']:""}}</td>
                                                    <td>{{isset($value['assumed_rate_of_return'])?number_format((float)$value['assumed_rate_of_return'], 2, '.', ''):""}} %</td>
                                                    <td>₹&nbsp;{{isset($value['total_investment'])?custome_money_format($value['total_investment']):""}}</td>
                                                    <td>₹&nbsp;{{isset($value['expected_future_value'])?custome_money_format($value['expected_future_value']):""}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                                <h1 class="midheading text-left">Suggested Schemes</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($sip_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 30%;">Scheme</th>
                                            @if($sip_category_checkbox)
                                                <th style="">Category</th>
                                            @endif
                                            <th style="width: 15%;">SIP Amount</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($sip_table_list as $key => $value)
                                                <tr>
                                                    @if($sip_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    @if($sip_category_checkbox)
                                                        <td style="">{{$value['category']}}</td>
                                                    @endif
                                                    <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($stp_checkbox)
                            @if(count($stp_form_list))
                                <h1 class="midheading text-left">STP Investment</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th rowspan="2">Initial Investment</th>
                                            <th>Assumed Return</th>
                                            <th rowspan="2">Transfer Mode / Frequency</th>
                                            <th rowspan="2">No. of Frequency / Investment Period</th>
                                            <th rowspan="2">STP Amount</th> 
                                            <th rowspan="2">Expected Future Value</th> 
                                          </tr>
                                          <tr>
                                            <th>From Scheme / To Scheme</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($stp_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td>₹&nbsp;{{custome_money_format($value['initial_investment_amount'])}}</td>
                                                    <td style="">{{number_format((float)$value['from_scheme'], 2, '.', '')}} % / {{number_format((float)$value['to_scheme'], 2, '.', '')}} % </td>
                                                    <td>{{$value['transfer_mode_value']}} / {{$value['frequency']}}</td>
                                                    <td>{{$value['no_of_frequency']}} / {{$value['investment_period']}} Years</td>
                                                    <td>₹&nbsp;{{custome_money_format($value['stp_amount'])}}</td>
                                                    <td>₹&nbsp;{{custome_money_format($value['expected_future_value'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
    
                            @if(count($stp_table_list))
                                <h1 class="midheading text-left">Suggested Schemes</h1>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($stp_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 22%;">From Scheme</th>
                                            <th style="width: 22%;">Initial Investment</th>
                                            <th style="width: 22%;">To Scheme</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($stp_table_list as $key => $value)
                                                <tr>
                                                    @if($stp_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    <td style="text-align: center">₹&nbsp;{{custome_money_format($value['investment'])}}</td>
                                                    <td style="">{{$value['equity_schemecode_name']}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($swp_checkbox)
                            @if(count($swp_form_list))
                                <div class="midheading text-left">SWP Investment</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">Total Investment(₹)</th>
                                            <th style="width: 22%;">Assumed Return</th>
                                            <th style="width: 22%;">SWP Frequency</th>
                                            <th style="width: 22%;">SWP Period</th>
                                            <th style="width: 22%;">SWP Amt(₹)</th>
                                            <th style="width: 22%;">Expected&nbsp;End Value(₹)</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($swp_form_list as $key => $value)
                                                
                                                <tr>
                                                    <td>₹&nbsp;{{custome_money_format($value['total_investment_amount'])}}</td>
                                                    <td>{{number_format((float)$value['assumed_rate_of_return'], 2, '.', '')}} %</td>
                                                    <td>{{$value['frequency']}}</td>
                                                    <td>
                                                        {{$value['period_year']}} Yrs 
                                                        @if($value['period_month'] || $value['period_month'] !=0)
                                                            {{$value['period_month']}} Month
                                                        @endif
                                                    </td>
                                                    <td>
                                                        ₹&nbsp;
                                                        @if($value['type_amount'] == 2)
                                                            {{custome_money_format($value['in_amount_hide'])}}
                                                        @else
                                                            {{custome_money_format($value['in_amount'])}}
                                                        @endif
                                                    </td>
                                                    <td>₹&nbsp;{{custome_money_format($value['actual_end_value'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                                <div class="midheading text-left">Suggested Schemes</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                          <tr>
                                            @if($swp_investor_checkbox)
                                                <th style="width: 15%;">Investor</th>
                                            @endif
                                            <th style="width: 30%;">Scheme</th>
                                            @if($swp_category_checkbox)
                                                <th style="">Category</th>
                                            @endif
                                            <th style="width: 15%;">Investment Amount</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($swp_table_list as $key => $value)
                                                <tr>
                                                    @if($swp_investor_checkbox)
                                                        <td style="">{{$value['investor']}}</td>
                                                    @endif
                                                    <td style="">{{$value['schemecode_name']}}</td>
                                                    @if($swp_category_checkbox)
                                                        <td style="">{{$value['category']}}</td>
                                                    @endif
                                                    <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($non_mf_product_checkbox)
                            @if(count($non_mf_product_list))
                                <div class="midheading">Other Investment Schemes</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <?php if($non_mf_product_investor_checkbox){ ?>
                                                    <th style="width: 15%;">Investor</th>
                                                <?php } ?>
                                                    <th style="">Product</th>
                                                    <th style="">Scheme / Company</th>
                                                <?php if($non_mf_product_amount_checkbox){ ?>
                                                    <th style="">Amount</th>
                                                <?php } ?>
                                                <?php if($non_mf_product_remark_checkbox){ ?>
                                                    <th style="width: 20%;">Remarks</th>
                                                <?php } ?>
                                                    <!-- <th style="">Attach Scheme Detail</th> -->
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($non_mf_product_list as $key => $value)
                                                <tr>
                                                    <?php if($non_mf_product_investor_checkbox){ ?>
                                                        <td style="width: 15%;">{{isset($value['inverstor'])?$value['inverstor']:""}}</td>
                                                    <?php } ?>
                                                        <td style="">{{$value['product_name']}}</td>
                                                        <td style="">{{$value['company']}}</td>
                                                    <?php if($non_mf_product_amount_checkbox){ ?>
                                                        <td>₹&nbsp;{{custome_money_format($value['amount'])}}</td>
                                                    <?php } ?>
                                                    <?php if($non_mf_product_remark_checkbox){ ?>
                                                        <td style="">{{$value['remark']}}</td>
                                                    <?php } ?>
                                                        <!-- <td style="">{{($value['attach'])?"Yes":"No"}}</td> -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($insurance_product_checkbox)
                            @if(count($insurance_product_list))
                                <div class="midheading">Insurance Schemes</div>
    
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                                    <th style="width: 15%;">Investor</th>
                                                <?php } ?>
                                                    <th style="width: 18%;">Product</th>
                                                    <th style="">Scheme / Company</th>
                                                    <th style="">Sum Assured</th>
                                                    <th style="">Annual Premium</th>
                                                <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                                    <th style="width: 20%;">Remarks</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @foreach($insurance_product_list as $key => $value)
                                                <tr>
                                                    <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                                        <td style="width: 15%;">{{isset($value['inverstor'])?$value['inverstor']:""}}</td>
                                                    <?php } ?>
                                                        <td style="width: 18%;">{{$value['product_type_name']}}</td>
                                                        <td style="">{{$value['company']}}</td>
                                                        <td style="text-align:center;"> ₹ {{custome_money_format($value['sum_assured'])}}</td>
                                                        <td>₹&nbsp;{{custome_money_format($value['annual_premium'])}}</td>                                        
                                                    <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                                        <td style="">{{$value['remark']}}</td>
                                                    <?php } ?>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
    
                            @endif
                        @endif
    
                        @if($comment)
                            <div class="midheading">Comment</div>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td>{{$comment}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
    
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

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput">Save & Merge with Sales Presenters</a>
                    </div>

                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
        <!--</div>-->
        
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.investment_proposal_output_pdf')}}";
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
                    <form target="_blank" action="{{route('frontend.investment_proposal_download')}}" method="get">
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

