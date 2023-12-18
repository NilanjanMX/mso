<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result</title>
    @include('frontend.calculators.common.pdf_style')
</head>
<body class="styleApril">
        
        @include('frontend.calculators.common.header')
        
    <main class="mainPdf">
        <div style="padding: 0px 0px;">
            
            <h1 class="pdfTitie">
                Investment Proposal @if(isset($client_name) && !empty($client_name)) For {{$client_name?$client_name:''}}  @else  @endif
            </h1>
        </div>
            @php 
                $index = 0; 
            @endphp

            @if($lumpsum_checkbox || $sip_checkbox || $stp_checkbox || $swp_checkbox)
                <h1 class="pdfTitie text">Mutual Fund Schemes</h1>
            @endif

            @if($lumpsum_checkbox)
                @php  $index = $index+5;  @endphp
                @if(count($lumpsum_form_list))
                    <h1 class="pdfTitie" style="text-align:left;">Lumpsum Investment</h1>
                    <div class="roundBorderHolder">
                        <table>
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
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        <td style="">{{$value['asset_class']}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{isset($value['investment_amount'])?custome_money_format($value['investment_amount']):""}}</td>
                                        <td>{{isset($value['investment_period'])?$value['investment_period']:""}}</td>
                                        <td>{{isset($value['assumed_rate_of_return'])?number_format((float)$value['assumed_rate_of_return'], 2, '.', ''):""}} %</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{isset($value['actual_end_value'])?custome_money_format($value['actual_end_value']):""}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(count($lumpsum_table_list))
                    <h1 class="pdfTitie" style="text-align:left;">Suggested Schemes</h1>
                    <div class="roundBorderHolder">
                        <table>
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
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        @if($lumpsum_investor_checkbox)
                                            <td style="">{{$value['investor']}}</td>
                                        @endif
                                        <td>{{$value['schemecode_name']}}</td>
                                        @if($lumpsum_category_checkbox)
                                            <td>{{$value['category']}}</td>
                                        @endif
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['amount'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
        </main>
                @include('frontend.calculators.common.watermark')
                @include('frontend.calculators.common.footer')
                
            @endif
            
            
            
            @if($sip_checkbox)
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
        <main class="mainPdf">
                @php  $index = $index+5;  @endphp
                @if(count($sip_form_list))
                
                    <h1 class="pdfTitie" style="text-align:left;">SIP Investment</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Asset<br> Class</th>
                                    <th style="width: 15%;">SIP Amount</th>
                                    <th style="width: 22%;">Frequency</th>
                                    <th style="width: 22%;">SIP <br> Period</th>
                                    <th style="width: 22%;">Investment Period</th>
                                    <th style="width: 22%;">Assumed Return</th>
                                    <th style="width: 22%;">Total Investment</th>
                                    <th style="width: 22%;">Expected Future&nbsp;Value</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($sip_form_list as $key => $value)
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        <td style="">{{isset($value['asset_class'])?$value['asset_class']:""}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{isset($value['sip_amount'])?custome_money_format($value['sip_amount']):""}}</td>
                                        <td>{{isset($value['frequency'])?$value['frequency']:""}}</td>
                                        <td style="">{{isset($value['sip_period'])?$value['sip_period']:""}}</td>
                                        <td>{{isset($value['investment_period'])?$value['investment_period']:""}}</td>
                                        <td>{{isset($value['assumed_rate_of_return'])?number_format((float)$value['assumed_rate_of_return'], 2, '.', ''):""}} %</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{isset($value['total_investment'])?custome_money_format($value['total_investment']):""}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{isset($value['expected_future_value'])?custome_money_format($value['expected_future_value']):""}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(count($sip_table_list))
                    <h1 class="pdfTitie" style="text-align:left;">Suggested Schemes</h1>
                    <div class="roundBorderHolder">
                        <table>
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
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        @if($sip_investor_checkbox)
                                            <td style="">{{$value['investor']}}</td>
                                        @endif
                                        <td>{{$value['schemecode_name']}}</td>
                                        @if($sip_category_checkbox)
                                            <td>{{$value['category']}}</td>
                                        @endif
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['amount'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
        </main>
                @include('frontend.calculators.common.watermark')
                @include('frontend.calculators.common.footer')        
                
            @endif

            @if($stp_checkbox)
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
        <main class="mainPdf">
                @php  $index = $index+5;  @endphp
                @if(count($stp_form_list))
                    <h1 class="pdfTitie" style="text-align:left;">STP Investment</h1>
                    <div class="roundBorderHolder">
                        <table>
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
                                    <th style="border-right: 1px solid #458ff6;">From Scheme / To Scheme</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($stp_form_list as $key => $value)
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['initial_investment_amount'])}}</td>
                                        <td style="">{{number_format((float)$value['from_scheme'], 2, '.', '')}} % / {{number_format((float)$value['to_scheme'], 2, '.', '')}} % </td>
                                        <td>{{$value['transfer_mode']}} / {{$value['frequency']}}</td>
                                        <td>{{$value['no_of_frequency']}} / {{$value['investment_period']}} Year</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['stp_amount'])}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['expected_future_value'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(count($stp_table_list))
                    <h1 class="pdfTitie" style="text-align:left;">Suggested Schemes</h1>
                    <div class="roundBorderHolder">
                        <table>
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
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        @if($stp_investor_checkbox)
                                            <td style="">{{$value['investor']}}</td>
                                        @endif
                                        <td>{{$value['schemecode_name']}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['investment'])}}</td>
                                        <td style="">{{$value['equity_schemecode_name']}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
        </main>
                @include('frontend.calculators.common.watermark')
                @include('frontend.calculators.common.footer')                
                
            @endif


            @if($swp_checkbox)
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
        <main class="mainPdf">
                @php  $index = $index+10;  @endphp
                @if(count($swp_form_list))
                    <h1 class="pdfTitie" style="text-align:left;">SWP Investment</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 22%;">Total&nbsp;Investment(<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                    <th style="width: 19%;">Assumed Return</th>
                                    <th style="width: 19%;">SWP Frequency</th>
                                    <th style="width: 22%;">SWP Period</th>
                                    <th style="width: 22%;">SWP Amount(<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                    <th style="width: 22%;">Expected End Value(<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($swp_form_list as $key => $value)
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['total_investment_amount'])}}</td>
                                        <td>{{number_format((float)$value['assumed_rate_of_return'], 2, '.', '')}} %</td>
                                        <td>{{$value['frequency']}}</td>
                                        <td>
                                            {{$value['period_year']}} Yrs 
                                            @if($value['period_month'] || $value['period_month'] !=0)
                                                {{$value['period_month']}} Month
                                            @endif
                                        </td>
                                        <td>
                                            @if($value['type_amount'] == 2)
                                                {{custome_money_format($value['in_amount_hide'])}}
                                            @else
                                                {{custome_money_format($value['in_amount'])}}
                                            @endif
                                        </td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['actual_end_value'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(count($swp_table_list))
                    <h1 class="pdfTitie" style="text-align:left;">Suggested Schemes</h1>
                    <div class="roundBorderHolder">
                        <table>
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
                                    @php  $index = $index+1;  @endphp
                                    <tr>
                                        @if($swp_investor_checkbox)
                                            <td style="">{{$value['investor']}}</td>
                                        @endif
                                        <td>{{$value['schemecode_name']}}</td>
                                        @if($swp_category_checkbox)
                                            <td>{{$value['category']}}</td>
                                        @endif
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['amount'])}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
        </main>
                @include('frontend.calculators.common.watermark')
                @include('frontend.calculators.common.footer')          
                
            @endif

            @if($non_mf_product_checkbox)
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
        <main class="mainPdf">
                @php 
                    $index = $index+5; 
                @endphp
                @if(count($non_mf_product_list))
                    <!-- <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 20px;  padding-bottom: 14px;">Non-Mutual Fund Products</div> -->
                    <h1 class="pdfTitie">Other Investment Schemes Products</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <thead>
                                <tr>
                                    <?php if(!empty($non_mf_product_investor_checkbox)){ ?>
                                        <th style="width: 11%;">Investor</th>
                                    <?php } ?>
                                        <th style="width: 18%;">Product</th>
                                        <th style="">Scheme / Company</th>
                                    <?php if(!empty($non_mf_product_amount_checkbox)){ ?>
                                        <th style="">Amount</th>
                                    <?php } ?>
                                    <?php if(!empty($non_mf_product_remark_checkbox)){ ?>
                                        <th style="">Remarks</th>
                                    <?php } ?>
                                        <!--<th style="">Attach Scheme&nbsp;Detail</th>-->
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($non_mf_product_list as $key => $value)
                                    @php 
                                        $index = $index+1; 
                                    @endphp
                                    @if($index == 20)
                                        @php 
                                            $index = 0;
                                        @endphp
                                        </tbody>
                                        </table>
                    </div>
                                    
                                    
                                        @php
                                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','investment_proposal')->first();
                                            if(!empty($note_data2)){
                                            @endphp
                                            {!!$note_data2->description!!}
                                        @php } @endphp
                        </main>
                                        @include('frontend.calculators.common.watermark')
                                        @include('frontend.calculators.common.footer')
                                        
                                        <div class="page-break"></div>
                                        @include('frontend.calculators.common.header')
                                        <main class="mainPdf">
                                    
                                        <h1 class="pdfTitie">Other Investment Schemes Products</h1>
                                        <div class="roundBorderHolder">
                                            <table>
                                                <thead>
                                                  <tr>
                                                    <?php if(!empty($non_mf_product_investor_checkbox)){ ?>
                                                        <th style="width: 11%;">Investor</th>
                                                    <?php } ?>
                                                        <th style="width: 18%;">Product</th>
                                                        <th style="">Scheme / Company</th>
                                                    <?php if(!empty($non_mf_product_amount_checkbox)){ ?>
                                                        <th style="">Amount</th>
                                                    <?php } ?>
                                                    <?php if(!empty($non_mf_product_remark_checkbox)){ ?>
                                                        <th style="">Remarks</th>
                                                    <?php } ?>
                                                </tr>
                                                </thead>
                                                
                                                <tbody>
                                    @endif
                                    <tr>
                                        <?php if(!empty($non_mf_product_investor_checkbox)){ ?>
                                            <td style="">{{isset($value['inverstor'])?$value['inverstor']:""}}</td>
                                        <?php } ?>
                                            <td style="width: 18%;">{{$value['product_name']}}</td>
                                            <td style="min-height: 40px; text-align:left;">{{$value['company']}}</td>
                                        <?php if(!empty($non_mf_product_amount_checkbox)){ ?>
                                            <td style="min-height: 40px;"> <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['amount'])}}</td>
                                        <?php } ?>
                                        <?php if(!empty($non_mf_product_remark_checkbox)){ ?>
                                            <td style="min-height: 40px;">{{$value['remark']}}</td>
                                        <?php } ?>
                                            <!--<td style="min-height: 40px;">{{($value['attach'])?"Yes":"No"}}</td>-->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                                        </div>

                @endif
        </main>
                @include('frontend.calculators.common.watermark')
                @include('frontend.calculators.common.footer')         
                
            @endif

            @if($insurance_product_checkbox)
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
        <main class="mainPdf">
                @php 
                    $index = $index+5; 
                @endphp
                @if(count($insurance_product_list))
                    <!-- <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 20px;  padding-bottom: 14px;">Insurance Products</div> -->
                    <h1 class="pdfTitie">Insurance Schemes</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <thead>
                                <tr>
                                    <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                        <th style="width: 15%;">Insured Name</th>
                                    <?php } ?>
                                        <th style="width: 15%;">Product</th>
                                        <th style="">Scheme / Company</th>
                                        <th style="">Sum <br>Assured (<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                        <th style="">Annual <br>Premium(<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                    <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                        <th style="width:30%;">Remarks</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach($insurance_product_list as $key => $value)
                                    @php 
                                        $index = $index+1; 
                                    @endphp
                                    @if($index == 20)
                                        @php 
                                            $index = 0;
                                        @endphp
                                        </tbody>
                                        </table>
                    </div>
                                    
                                    
                                        @php
                                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','investment_proposal')->first();
                                            if(!empty($note_data2)){
                                            @endphp
                                            {!!$note_data2->description!!}
                                        @php } @endphp
                                        
                        </main>
                                        @include('frontend.calculators.common.watermark')
                                        @include('frontend.calculators.common.footer')
                                        
                                        <div class="page-break"></div>
                                        @include('frontend.calculators.common.header')
                                        <main class="mainPdf">
                                    
                                        <h1 class="pdfTitie">Insurance Schemes</h1>
                                        <div class="roundBorderHolder">
                                            <table>
                                                <thead>
                                                  <tr>
                                                    <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                                        <th style="width: 15%;">Insured Name</th>
                                                    <?php } ?>
                                                        <th style="width: 18%;">Product</th>
                                                        <th style="">Scheme / Company</th>
                                                        <th style="">Sum <br>Assured (<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                                        <th style="">Annual <br>Premium(<span class="pdfRupeeIcon">&#8377;</span>)</th>
                                                    <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                                        <th style="">Remarks</th>
                                                    <?php } ?>
                                                </tr>
                                                </thead>
                                                
                                                <tbody>
                                    @endif
                                    <tr>
                                        <?php if(!empty($insurance_product_insured_name_checkbox)){ ?>
                                            <td style="width: 15%;">{{isset($value['inverstor'])?$value['inverstor']:""}}</td>
                                        <?php } ?>
                                            <td style="width: 18%;">{{$value['product_type_name']}}</td>
                                            <td style="min-height: 40px; text-align:left;">{{$value['company']}}</td>
                                            <td style="min-height: 40px;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['sum_assured'])}}</td>
                                            <td style="min-height: 40px;"> <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($value['annual_premium'])}}</td>                                        
                                        <?php if(!empty($insurance_product_remark_checkbox)){ ?>
                                            <td style="min-height: 40px;">{{$value['remark']}}</td>
                                        <?php } ?>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                                        </div>

                @endif
            @endif
        </main>   
        
        <main class="mainPdf">
            @if($comment)
                
                <!-- <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 20px;  padding-bottom: 0px;">Comment</div> -->
                <h1 class="pdfTitie">Comment</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                            <tr>
                                <td>{{$comment}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        
        
            @php
                $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','investment_proposal')->first();
                if(!empty($note_data2)){
                @endphp
                {!!$note_data2->description!!}
            @php } @endphp
        </main>
        
        @include('frontend.calculators.common.watermark')
        @include('frontend.calculators.common.footer')
        
        
        @if($performance_of_selected_mutual_fund)
            <div class="page-break"></div>

        @include('frontend.calculators.common.header')
    <main class="mainPdf">
            <div style="padding: 0px 0px;">
                <h1 class="pdfTitie">Performance of Selected Mutual Fund Scheme</h1>
                <div class="roundBorderHolder">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 35%;">Scheme Name</th>
                                <th style="width: 25%;">Category</th>
                                <th style="width: 6%;">6 Month</th>
                                <th style="width: 6%;">1 Year</th>
                                <th style="width: 6%;">3 Year</th>
                                <th style="width: 6%;">5 Year</th>
                                <th style="width: 6%;">10 Year</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($scheme_data_list as $key => $value)
                                @php
                                    $value['6MONTHRET'] = ($value['6MONTHRET'])?number_format((float)($value['6MONTHRET']), 2, '.', ''):"-";
                                    $value['1YEARRET'] = ($value['1YEARRET'])?number_format((float)($value['1YEARRET']), 2, '.', ''):"-";
                                    $value['3YEARRET'] = ($value['3YEARRET'])?number_format((float)($value['3YEARRET']), 2, '.', ''):"-";
                                    $value['5YEARRET'] = ($value['5YEARRET'])?number_format((float)($value['5YEARRET']), 2, '.', ''):"-";
                                    $value['10YEARRET'] = ($value['10YEARRET'])?number_format((float)($value['10YEARRET']), 2, '.', ''):"-";
                                @endphp
                                <tr>
                                    <td style="width: 35%;">{{$value['S_NAME']}}</td>
                                    <td style="width: 25%;">{{$value['CATEGORY']}}</td>
                                    <td style="width: 6%;">{{$value['6MONTHRET']}}</td>
                                    <td style="width: 6%;">{{$value['1YEARRET']}}</td>
                                    <td style="width: 6%;">{{$value['3YEARRET']}}</td>
                                    <td style="width: 6%;">{{$value['5YEARRET']}}</td>
                                    <td style="width: 6%;">{{$value['10YEARRET']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')

            @php
                $note_data1 = \App\Models\Calculator_note::where('category','fund_performance')->where('calculator','All')->first();
                if(!empty($note_data1)){
                @endphp
                {!!$note_data1->description!!}
            @php } @endphp
            
               Report Date : {{date('d/m/Y')}}
    </main>
            
            @include('frontend.calculators.common.watermark')
            @include('frontend.calculators.common.footer')
        @endif
</body>
</html>