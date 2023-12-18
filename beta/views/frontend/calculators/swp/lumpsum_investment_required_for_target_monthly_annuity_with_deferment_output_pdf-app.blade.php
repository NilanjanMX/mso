<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            font-size: 14px;
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
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin-top: 160px
        }

        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 94px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 70px;
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
        }
    </style>
</head>
<body>

@php

    //Annuity Period (Months) T9*12
    $annuity_period_months = $annuity_period*12;
    //Deferment Period (Months)
    $deferment_period_months = $deferment_period*12;
    //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
    $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
    //Lumpsum For Balance (1) T15/(1+AV31)^(AV30)
    $lumpsum_for_balance1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
    //Lumpsum For Annuity (1) (X30*(1-(1+AV31)^(-AV30)))/AV31
    $lumpsum_for_annuity1 = ($required_monthly_annuity*(1-(1+$distribution_monthly_return1)**(-$annuity_period_months)))/$distribution_monthly_return1;
    //Annuity Purchase Amount (1) AV33+AV35
    $annuity_purchase_amount1 = $lumpsum_for_balance1+$lumpsum_for_annuity1;
    //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
    $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1;
    //Lumpsum Investment Required (1) AV37/(1+AV40)^AV39
    $lumpsum_investment_required1 = $annuity_purchase_amount1/(1+$accumulation_monthly_return1)**$deferment_period_months;
     //echo $lumpsum_investment_required1; die();

    if (isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0){
        //Distribution Monthly Return (2)
         $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
         //Lumpsum For Balance (2) T15/(1+AV31)^(AV30)
        $lumpsum_for_balance2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
        //Lumpsum For Annuity (2) (X30*(1-(1+AV31)^(-AV30)))/AV31
        $lumpsum_for_annuity2 = ($required_monthly_annuity*(1-(1+$distribution_monthly_return2)**(-$annuity_period_months)))/$distribution_monthly_return2;
         //Annuity Purchase Amount (2) AV33+AV35
         $annuity_purchase_amount2 = $lumpsum_for_balance2+$lumpsum_for_annuity2;
          //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
        $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1;
        //Lumpsum Investment Required (2) AV37/(1+AV40)^AV39
        $lumpsum_investment_required2 = $annuity_purchase_amount2/(1+$accumulation_monthly_return2)**$deferment_period_months;
    }

@endphp

<main style="width: 760px; margin-left: 20px;">
    <SALESPRESENTER_BEFORE/>
    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Monthly Annuity Planning @if(isset($clientname)) For {{$clientname?$clientname:''}} @endif</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Target Monthly Annuity</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Deferment Period</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$deferment_period?$deferment_period:0}} Years
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
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balance_required)}}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    @if(!isset($accumulation_phase_interest_rate_2) || $accumulation_phase_interest_rate_2 == '')
    <div style="padding: 0 15%;">

    	<table style="background: #ffffff;">
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
    @endif

    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        <div style="padding: 0 20%;">
    @endif
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Accumulated Corpus</h1>
            <table class="table table-bordered text-center">
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
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annuity_purchase_amount1)}} </strong>
                        </td>
                        <td style="width: 50%;">
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annuity_purchase_amount2)}} </strong>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>
                            <strong>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($annuity_purchase_amount1)}}
                            </strong>
                        </td>
                    </tr>
                @endif
                </tbody></table>

            @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        </div>
    @endif

    @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2==0)
        <div style="padding: 0 20%;">
            @endif
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Lumpsum Investment Required</h1>
            <table class="table table-bordered text-center">
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
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required1)}} </strong>
                        </td>
                        <td style="width: 50%;">
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required2)}} </strong>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>
                            <strong>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($lumpsum_investment_required1)}}
                            </strong>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2==0)
        </div>
    @endif

    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumpsum_Investment_Required_For_Target_Monthly_Annuity_With_Deferment_Period')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
    @php } @endphp
    
    @include('frontend.calculators.common.footer')
    @if(isset($report) && $report=='detailed')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
            Accumulation Phase <br>Projected Annual Investment Value
        </h1>
        <table>
            <tbody>
            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                <tr>
                    <th rowspan="2" valign="middle">Year</th>
                    <th colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                    <th colspan="2">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                </tr>

                @for($i=1;$i<=$deferment_period;$i++)
                    @php
                        //Year End Value AS65*(1+AV65)^AU65
                       $year_end_value1 = $lumpsum_investment_required1*(1+$accumulation_phase_interest_rate_1/100)**$i;
                       //Year End Value AT65*(1+AV65)^AU65
                       $year_end_value2 = $lumpsum_investment_required2*(1+$accumulation_phase_interest_rate_2/100)**$i;
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        @if($i==1)
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required1)}}</td>
                        @else
                            <td> -- </td>
                        @endif
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                        @if($i==1)
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required2)}}</td>
                        @else
                            <td> -- </td>
                        @endif
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value2)}}</td>
                    </tr>

                    @if($i%25==0 && $deferment_period>25 && $deferment_period>$i)
                            </tbody>
                        </table>
                        @include('frontend.calculators.common.footer')
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        <table>
                            <tbody>
                            <tr>
                                <th rowspan="2" valign="middle">Year</th>
                                <th colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                <th colspan="2">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                            </tr>
                            <tr>
                                <th>Annual Investment</th>
                                <th>Year End Value</th>
                                <th>Annual Investment</th>
                                <th>Year End Value</th>
                            </tr>
                            @endif
                            @endfor
            @else

                <tr>
                    <th valign="middle">Year</th>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                </tr>

                @for($i=1;$i<=$deferment_period;$i++)
                    @php
                        //Year End Value AS65*(1+AV65)^AU65
                       $year_end_value1 = $lumpsum_investment_required1*(1+$accumulation_phase_interest_rate_1/100)**$i;
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        @if($i==1)
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required1)}}</td>
                        @else
                            <td> -- </td>
                        @endif

                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                    </tr>


                    @if($i%25==0 && $deferment_period>25 && $deferment_period>$i)
                                </tbody>
                            </table>
                            @include('frontend.calculators.common.footer')
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                        <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            <table>
                                <tbody>
                                <tr>
                                    <th valign="middle">Year</th>
                                    <th>Annual Investment</th>
                                    <th>Year End Value</th>
                                </tr>
            @endif

            @endfor
            @endif
            </tbody>
        </table>
        @include('frontend.calculators.common.footer')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
            Distribution Phase <br>Annual Wihdrawal & Projected Investment Value
        </h1>
        <table>
            <tbody>
            @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                <tr>
                    <th rowspan="2" valign="middle">Year</th>
                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                    <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th>Monthly Annuity</th>
                    <th>Year End Balance</th>
                    <th>Monthly Annuity</th>
                    <th>Year End Balance</th>
                </tr>

                @for($i=1;$i<=$annuity_period;$i++)
                    @php
                        //Year End Balance (AS107*(1+AU107)^(AR107*12)-(AW107*((1+AU107)^(AR107*12)-1)/AU107))
                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($required_monthly_annuity*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                        $year_end_balance2 = ($annuity_purchase_amount2*(1+$distribution_monthly_return2)**($i*12)-($required_monthly_annuity*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_balance2)}}</td>
                    </tr>

                    @if($i%25==0 && $annuity_period>25 && $annuity_period>$i)
            </tbody>
        </table>
        @include('frontend.calculators.common.footer')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <table>
            <tbody>
            <tr>
                <th rowspan="2" valign="middle">Year</th>
                <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
            </tr>
            <tr>
                <th>Monthly Annuity</th>
                <th>Year End Balance</th>
                <th>Monthly Annuity</th>
                <th>Year End Balance</th>
            </tr>
            @endif
            @endfor
            @else

                <tr>
                    <th valign="middle">Year</th>
                    <th>Monthly Annuity</th>
                    <th>Year End Balance</th>
                </tr>
                @for($i=1;$i<=$annuity_period;$i++)
                    @php
                        //Year End Balance (AS107*(1+AU107)^(AR107*12)-(AW107*((1+AU107)^(AR107*12)-1)/AU107))
                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($required_monthly_annuity*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));

                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                    </tr>


                    @if($i%25==0 && $annuity_period>25 && $annuity_period>$i)
            </tbody>
        </table>
        @include('frontend.calculators.common.footer')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <table>
            <tbody>
            <tr>
                <th valign="middle">Year</th>
                <th>Monthly Annuity</th>
                <th>Year End Balance</th>
            </tr>
            @endif

            @endfor
            @endif
            </tbody>
        </table>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumpsum_Investment_Required_For_Target_Monthly_Annuity_With_Deferment_Period')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
        @include('frontend.calculators.common.footer')

    @endif
    @include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>