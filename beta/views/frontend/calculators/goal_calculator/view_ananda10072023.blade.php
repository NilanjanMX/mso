
@extends('layouts.frontend')
@section('js_after')
    <script>
        
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
                    
                        <h5 class="mb-3">Goal Planning Calculation @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h5>
                        
                        @foreach($list as $key => $value)

                            <h5 class="mb-3"> {{$value['purpose_of_investment']}} </h5>

                            @if($value['cost_type'] == 1)
                                @php 
                                    $bg51 = $value['period'] * 12;
                                    $bg52 = $value['amount'];                                
                                @endphp
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Fund Required</strong>
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($value['amount'])}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Time Period</strong>
                                            </td>
                                            <td>
                                                 {{$value['period']}}  Years
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Assumed Rate of Return</h5>
                                <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;">
                                                <strong>Debt</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>Hybrid</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>Equity</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if($value['aror_debt'])
                                                    {{number_format((float)$value['aror_debt'], 2, '.', '')}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['aror_hybrid'])
                                                    {{number_format((float)$value['aror_hybrid'], 2, '.', '')}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['aror_equity'])
                                                    {{number_format((float)$value['aror_equity'], 2, '.', '')}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Investment Options</h5>
                                <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Investment Option</strong>
                                            </td>
                                            <td>
                                                <strong>Asset Allocation</strong>
                                            </td>
                                            <td>
                                                <strong>Amount</strong>
                                            </td>
                                        </tr>
                                        @if($value['lumpsum_investment_mode'])
                                            @php 
                                                $bg53 = $value['aror_debt']*$value['lumpsum_debt']/100+$value['aror_hybrid']*$value['lumpsum_hybrid']/100+$value['aror_equity']*$value['lumpsum_equity']/100;
                                                $bg54 = $bg52/pow((1+$bg53/100),$value['period']);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum Investment</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_debt'] && $value['lumpsum_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_hybrid'] && $value['lumpsum_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_equity'] && $value['lumpsum_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg54)}}
                                                </td>
                                            </tr>
                                        @endif

                                        @if($value['monthly_sip_investment_mode'])
                                            @php 
                                                $bg55 = pow((1+($value['aror_debt']*$value['monthly_sip_debt']/100+$value['aror_hybrid']*$value['monthly_sip_hybrid']/100+$value['aror_equity']*$value['monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg56 = ($bg52*$bg55)/(pow((1+$bg55),($bg51))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP</strong>
                                                </td>
                                                <td>
                                                    @if($value['monthly_sip_debt'] && $value['monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['monthly_sip_hybrid'] && $value['monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['monthly_sip_equity'] && $value['monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg56)}}
                                                </td>
                                            </tr>
                                        @endif

                                        @if($value['limited_period_monthly_investment_mode'])
                                            
                                            @if($value['limited_period_monthly_sip_period_1'] && $value['limited_period_monthly_sip_period_1'] != "0")
                                                @php
                                                    $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                    $bg58 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_1'])*12));
                                                    $bg59 = ($bg58*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_1'])*12))-1);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_1']}} Years</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg59)}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($value['limited_period_monthly_sip_period_2'] && $value['limited_period_monthly_sip_period_2'] != "0")
                                                @php
                                                    $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                    $bg60 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_2'])*12));
                                                    $bg61 = ($bg60*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_2'])*12))-1);

                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_2']}} Years</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg61)}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($value['limited_period_monthly_sip_period_3'] && $value['limited_period_monthly_sip_period_3'] != "0")
                                                @php

                                                    $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;

                                                    $bg62 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_3'])*12));
                                                    $bg63 = ($bg62*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_3'])*12))-1);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_3']}} Years</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg63)}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif

                                        @if($value['lumpsum_monthly_sip_investment_mode'])
                                            
                                            @if($value['lumpsum_monthly_sip'] == 1)
                                                @php 
                                                    $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                    $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                    $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                    $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                    $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                    $bg69 = $bg52-$bg68;
                                                    $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Lumpsum  + Monthly SIP</strong>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                         
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Lumpsum Investment</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($value['lumpsum_monthly_sip_lumpsum_amount'])}} 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg70)}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($value['lumpsum_monthly_sip'] == 2)
                                                @php 
                                                    $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                    $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                    $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                    $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);
                                                    $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                    $bg69 = $bg52-$bg68;
                                                    $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                    $bg71 = (1+$bg66)*$value['lumpsum_monthly_sip_amount']*((pow((1+$bg66),($bg51))-1)/$bg66);
                                                    $bg72 = $bg52-$bg71;
                                                    $bg73 = $bg72/pow((1+$bg64/100),$value['period']);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Lumpsum  + Monthly SIP</strong>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                         
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Lumpsum Investment</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg73)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($value['lumpsum_monthly_sip_amount'])}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        
                                    </tbody>
                                </table>
                            @else
                                @php 
                                    $bg51 = $value['period'] * 12;
                                    $bg52 = $value['amount']*pow((1+$value['inflation']/100),$value['period']);                                
                                @endphp
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Fund Required (Current Cost)</strong>
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($value['amount'])}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Assumed Inflation Rate</strong>
                                            </td>
                                            <td>
                                                {{number_format((float)$value['inflation'], 2, '.', '')}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Future Cost of Fund Required</strong>
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($bg52)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Time Period</strong>
                                            </td>
                                            <td>
                                                 {{$value['period']}}  Years
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Assumed Rate of Return</h5>
                                <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;">
                                                <strong>Debt</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>Hybrid</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>Equity</strong>
                                            </td>
                                        </tr>
                                         <tr>
                                            <td>
                                                @if($value['aror_debt'])
                                                    {{number_format((float)$value['aror_debt'], 2, '.', '')}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['aror_hybrid'])
                                                    {{number_format((float)$value['aror_hybrid'], 2, '.', '')}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['aror_equity'])
                                                    {{number_format((float)$value['aror_equity'], 2, '.', '')}} %
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Investment Options</h5>
                                <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Investment Option</strong>
                                            </td>
                                            <td>
                                                <strong>Asset Allocation</strong>
                                            </td>
                                            <td>
                                                <strong>Amount</strong>
                                            </td>
                                        </tr>
                                        @if($value['lumpsum_investment_mode'])
                                            @php 
                                                $bg53 = $value['aror_debt']*$value['lumpsum_debt']/100+$value['aror_hybrid']*$value['lumpsum_hybrid']/100+$value['aror_equity']*$value['lumpsum_equity']/100;
                                                $bg54 = $bg52/pow((1+$bg53/100),$value['period']);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum Investment</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_debt'] && $value['lumpsum_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_hybrid'] && $value['lumpsum_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_equity'] && $value['lumpsum_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg54)}}
                                                </td>
                                            </tr>
                                        @endif

                                        @if($value['monthly_sip_investment_mode'])
                                            @php 
                                                $bg55 = pow((1+($value['aror_debt']*$value['monthly_sip_debt']/100+$value['aror_hybrid']*$value['monthly_sip_hybrid']/100+$value['aror_equity']*$value['monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg56 = ($bg52*$bg55)/(pow((1+$bg55),($bg51))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP</strong>
                                                </td>
                                                <td>
                                                    @if($value['monthly_sip_debt'] && $value['monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['monthly_sip_hybrid'] && $value['monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['monthly_sip_equity'] && $value['monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg56)}}
                                                </td>
                                            </tr>
                                        @endif

                                        @if($value['limited_period_monthly_investment_mode'])
                                            @php 
                                                
                                            @endphp

                                            @if($value['limited_period_monthly_sip_period_1'] && $value['limited_period_monthly_sip_period_1'] != "0")
                                                @php
                                                    $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                    $bg58 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_1'])*12));
                                                    $bg59 = ($bg58*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_1'])*12))-1);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_1']}} Years</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg59)}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($value['limited_period_monthly_sip_period_2'] && $value['limited_period_monthly_sip_period_2'] != "0")
                                                @php
                                                    $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                    $bg60 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_2'])*12));
                                                    $bg61 = ($bg60*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_2'])*12))-1);
                                                @endphp

                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_2']}} Years</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg61)}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($value['limited_period_monthly_sip_period_3'] && $value['limited_period_monthly_sip_period_3'] != "0")
                                                @php
                                                    $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                    $bg62 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_3'])*12));
                                                    $bg63 = ($bg62*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_3'])*12))-1);
                                                @endphp

                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_3']}} Years</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg63)}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif

                                        @if($value['lumpsum_monthly_sip_investment_mode'])
                                            
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum  + Monthly SIP</strong>
                                                </td>
                                                <td></td>
                                                <td>
                                                     
                                                </td>
                                            </tr>
                                            @if($value['lumpsum_monthly_sip'] == 1)
                                                @php 
                                                    $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                    $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                    $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                    $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                    $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                    $bg69 = $bg52-$bg68;
                                                    $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Lumpsum Investment</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($value['lumpsum_monthly_sip_lumpsum_amount'])}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg70)}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($value['lumpsum_monthly_sip'] == 2)
                                                @php 
                                                    $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                    $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                    $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                    $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                    $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                    $bg69 = $bg52-$bg68;
                                                    $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                    $bg71 = (1+$bg66)*$value['lumpsum_monthly_sip_amount']*((pow((1+$bg66),($bg51))-1)/$bg66);
                                                    $bg72 = $bg52-$bg71;
                                                    $bg73 = $bg72/pow((1+$bg64/100),$value['period']);
                                                @endphp
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Lumpsum Investment</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($bg73)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <strong>Monthly SIP</strong>
                                                    </td>
                                                    <td>
                                                        @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                            <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                            <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                        @endif
                                                        @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                            <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                         ₹ {{custome_money_format($value['lumpsum_monthly_sip_amount'])}}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            @endif

                        @endforeach
                        
                        <p>*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

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
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
        
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.goal_calculator_output_pdf')}}";
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

