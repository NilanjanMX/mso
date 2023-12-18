<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('frontend.calculators.common.pdf_style')
</head>
<body class="styleApril">

@php
    //SIP Period (Month)
    $sip_period_months = $sip_period*12;
     //Annuity Period (Months) T9*12
    $annuity_period_months = $annuity_period*12;
    //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
    $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1 ;
    //Annuity Purchase Amount (1)  (1+AV29)*T9*(((1+AV29)^(AV28)-1)/AV29)
    $annuity_purchase_amount1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
    //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
    $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
    //PV of Balance Required (1) T15/(1+AV35)^(AV33)
    $pv_of_balance_required1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
    //Balance Available for Annuity (1) AV31-AV37
    $balance_available_for_annuity1 = $annuity_purchase_amount1 - $pv_of_balance_required1;
    //Monthly Annuity Amount (1) (AV35*AV39)/(1-(1+AV35)^(-AV33))
    $monthly_annuity_amount1 = ($distribution_monthly_return1*$balance_available_for_annuity1)/(1-(1+$distribution_monthly_return1)**(-$annuity_period_months));

    if (isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2 >0){
        //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
        $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1 ;
        //Annuity Purchase Amount (2)  (1+AV29)*T9*(((1+AV29)^(AV28)-1)/AV29)
        $annuity_purchase_amount2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
        //Distribution Monthly Return (2)
         $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
         //PV of Balance Required (1) T15/(1+AV35)^(AV33)
        $pv_of_balance_required2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
        //Balance Available for Annuity (1) AV31-AV37
        $balance_available_for_annuity2 = $annuity_purchase_amount2 - $pv_of_balance_required2;
        //Monthly Annuity Amount (2) (AV35*AV39)/(1-(1+AV35)^(-AV33))
        $monthly_annuity_amount2 = ($distribution_monthly_return2*$balance_available_for_annuity2)/(1-(1+$distribution_monthly_return2)**(-$annuity_period_months));
    }

@endphp
    
@include('frontend.calculators.common.header')
<main class="mainPdf">
    
    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">Monthly SWP Calculation @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody>
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Monthly SIP Amount</strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>SIP Period</strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        {{$sip_period?$sip_period:0}} Years
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Annuity Period</strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        {{$annuity_period?$annuity_period:0}} Years
                    </td>
                </tr>
                @if(isset($balance_required) && $balance_required>0)
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Balance Required</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($balance_required)}}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')

    @if(!isset($accumulation_phase_interest_rate_2) || $accumulation_phase_interest_rate_2 == '')
    <div style="padding: 0 0%;">
        <div class="roundBorderHolder withBluebarMrgn">
        	<table>
        		<tr>
        			<td style="width: 50%; text-align: left;">
        				<strong>Assumed Rate of Return</strong>
        			</td>
        			<td style="width: 50%; padding: 0;">
        				<table>
        					<tr>
    	    					<td style="padding-left: 20px; text-align:left;">Accumulation Phase</td>
    	    					<td style="padding: 6px 4px;text-align:right;">
    	    						{{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
    	    					</td>
    	    				</tr>
    	    				<tr>
    	    					<td style="padding-left: 20px; text-align:left;">Distribution Phase</td>
    	    					<td style="padding: 6px 4px; text-align:right;">
    	    						{{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
    	    					</td>
    	    				</tr>
        				</table>
        				
        			</td>
        		</tr>
        	</table>
        </div>

    </div>
    @endif
    
    @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2==0)
        <div style="padding: 0 0%;">
            @endif
            <h1 class="pdfTitie">Accumulated Corpus</h1>
            <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                        <tr>
                            <th style="width: 50%;">
                                Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
                            </th>
                            <th style="width: 50%;">
                                Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount1)}} </strong>
                            </td>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount2)}} </strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <strong>
                                    <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($annuity_purchase_amount1)}}
                                </strong>
                            </td>
                        </tr>
                    @endif
                    </tbody></table>
            </div>

            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2==0)
        </div>
    @endif

    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        <div style="padding: 0 0%;">
            @endif
            <h1 class="pdfTitie">Monthly Annuity Amount</h1>
            <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                        <tr>
                            <th style="width: 50%;">
                                Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                            </th>
                            <th style="width: 50%;">
                                Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}} </strong>
                            </td>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}} </strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <strong>
                                    <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($monthly_annuity_amount1)}}
                                </strong>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        </div>
    @endif

    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Monthly_Annuity_For_SIP')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
    @php } @endphp
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
    @if(isset($report) && $report=='detailed')
        <div class="page-break"></div>
                
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
        <h1 class="bluebar" style="background:{{$city_color}}">
            Accumulation Phase <br>Projected Annual Investment Value
        </h1>
        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
            <table>
                <tbody>
                @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                    <tr>
                        <th style="background:{{$address_color_background}}">Year</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                    </tr>
                    @for($i=1;$i<=$sip_period;$i++)
                        @php
                            //Year End Value (1+AV64)*AT64*(((1+AV64)^(AU64*12)-1)/AV64)
                           $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                           //Year End Value AT65*(1+AV65)^AU65
                           $year_end_value2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount*12)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value2)}}</td>
                        </tr>
    
                        @if($i%25==0 && $sip_period>25 && $sip_period>$i)
                </tbody>
            </table>
        </div>
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
        <div class="page-break"></div>
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
        <div class="roundBorderHolder withBluebar withBluebarMrgn">
            <table>
                <tbody>
                <tr>
                    <th style="background:{{$address_color_background}}">Year</th>
                    <th style="background:{{$address_color_background}}">Annual Investment</th>
                    <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                    <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                </tr>
                @endif
                @endfor
                @else
                    <tr>
                        <th style="background:{{$address_color_background}}">Year</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                    </tr>
    
                    @for($i=1;$i<=$sip_period;$i++)
                        @php
                            //Year End Value (1+AV64)*AT64*(((1+AV64)^(AU64*12)-1)/AV64)
                           $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount*12)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                        </tr>
    
    
                        @if($i%25==0 && $sip_period>25 && $sip_period>$i)
                </tbody>
            </table>
        </div>
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
        <div class="page-break"></div>
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
        <div class="roundBorderHolder withBluebar withBluebarMrgn">
            <table>
                <tbody>
                <tr>
                    <th style="background:{{$address_color_background}}">Year</th>
                    <th style="background:{{$address_color_background}}">Annual Investment</th>
                    <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                </tr>
                @endif
    
                @endfor
                @endif
                </tbody>
            </table>
        </div>
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
        <div class="page-break"></div>
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
        <h1 class="bluebar" style="background:{{$city_color}}">
            Distribution Phase <br>Annual Wihdrawal & Projected Investment Value
        </h1>
        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
            <table>
                <tbody>
                @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                    <tr>
                        <th style="background:{{$address_color_background}}" rowspan="2" valign="middle">Year</th>
                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                    </tr>
                    <tr>
                        <th style="background:{{$address_color_background}}">Monthly Annuity</th>
                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                        <th style="background:{{$address_color_background}}">Monthly Annuity</th>
                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                    </tr>
    
                    @for($i=1;$i<=$annuity_period;$i++)
                        @php
                            //Year End Balance (AS106*(1+AU106)^(AR106*12)-(AW106*((1+AU106)^(AR106*12)-1)/AU106))
                            $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                            $year_end_balance2 = ($annuity_purchase_amount2*(1+$distribution_monthly_return2)**($i*12)-($monthly_annuity_amount2*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));
    
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance2)}}</td>
                        </tr>
    
                        @if($i%25==0 && $annuity_period>25 && $annuity_period>$i)
                </tbody>
            </table>
        </div>
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
        <div class="page-break"></div>
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
        <div class="roundBorderHolder withBluebar withBluebarMrgn">
            <table>
                <tbody>
                <tr>
                    <th style="background:{{$address_color_background}}" rowspan="2" valign="middle">Year</th>
                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th style="background:{{$address_color_background}}">Monthly Annuity</th>
                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                    <th style="background:{{$address_color_background}}">Monthly Annuity</th>
                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                </tr>
                @endif
                @endfor
                @else
                    <tr>
                        <th style="background:{{$address_color_background}}" valign="middle">Year</th>
                        <th style="background:{{$address_color_background}}">Monthly Annuity</th>
                        <th style="background:{{$address_color_background}}">Year End Balance @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                    </tr>
                    @for($i=1;$i<=$annuity_period;$i++)
                        @php
                            //Year End Balance (AS106*(1+AU106)^(AR106*12)-(AW106*((1+AU106)^(AR106*12)-1)/AU106))
                            $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
    
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                        </tr>
    
    
                        @if($i%25==0 && $annuity_period>25 && $annuity_period>$i)
                </tbody>
            </table>
        </div>
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
        <div class="page-break"></div>
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
                                            
        <div class="roundBorderHolder withBluebar withBluebarMrgn">
            <table>
                <tbody>
    
                <tr>
                    <th style="background:{{$address_color_background}}" valign="middle">Year</th>
                    <th style="background:{{$address_color_background}}">Monthly Annuity</th>
                    <th style="background:{{$address_color_background}}">Year End Balance @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                </tr>
                @endif
    
                @endfor
                @endif
                </tbody>
            </table>
        </div>
    
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Monthly_Annuity_For_SIP')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif

    @endif
    @include('frontend.calculators.suggested.pdf')

</body>
</html>