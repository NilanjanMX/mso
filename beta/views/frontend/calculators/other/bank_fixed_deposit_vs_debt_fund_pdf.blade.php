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
            padding: 5px 8px;
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
            padding: 5px 8px;
            font-size: 16px;
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
            /*position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 50px;*/
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
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

                <div style="padding: 0 5%;">

                <h1 style="background:#8edeff; color:#000;margin-bottom:30px !important;text-align:center;font-size:24px !important; padding: 10px;">Bank FD vs Debt Mutual Fund Comparison @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>

                        

                         <table class="table table-bordered text-center">
                        <tbody>
                         <tr>
                            <th style="height:45px;">
                                <strong>Particulars</strong>
                            </th>
                            <th style="height:45px;">
                                <strong>Fixed Deposit</strong>
                            </th>
                            <th style="theight:45px;">
                                <strong>Debt Mutual Fund</strong>
                            </th>
                         </tr>

                        <tr>
                            <td style="text-align:left;">
                               Initial Investment
                            </td>
                            <td style="text-align:right;">
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}
                            </td>
                            <td style="text-align:right;">
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                               Investment Period
                            </td>
                            <td style="text-align:right;">
                               {{$period}} Years
                            </td>
                            <td style="text-align:right;">
                               {{$period}} Years
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                               Assumed Return
                            </td>
                            <td style="text-align:right;">
                               {{number_format($fixed_deposit,2)}} %
                            </td>
                            <td style="text-align:right;">
                               {{number_format($debt_fund,2)}} %
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                               Maturity / Redemption Amount
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u36=$initial_investment*pow((1+($fixed_deposit/100)),$period);
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($u36)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad36=$initial_investment*pow((1+($debt_fund/100)),$period);
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ad36)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Interest Income / Capital Gain
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u37=$u36-$initial_investment;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($u37)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad37=$ad36-$initial_investment;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ad37)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Assumed Indexation Rate
                            </td>
                            <td style="text-align:right;">
                               NA
                            </td>
                            <td style="text-align:right;">
                               {{number_format($assumed_inflation_rate_for_indexation,2)}}%
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                             Indexed Cost of Investment
                            </td>
                            <td style="text-align:right;">
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               if($period<$for_period_upto)
                               {
                                  $ad39=$initial_investment;
                               }else{
                                  $ad39=$initial_investment*pow((1+($assumed_inflation_rate_for_indexation/100)),$period);
                               }
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ad39)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Taxable Income
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u40=$u36-$initial_investment;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($u40)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad40=$ad36-$ad39;
                               @endphp
                               
                               @if($ad40 > 0)
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ad40)}}
                               @else
                                -
                               @endif
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Applicable Tax Rate
                            </td>
                            <td style="text-align:right;">
                               {{number_format($applicable_short_term_tax_rate,2)}}%
                            </td>
                            <td style="text-align:right;">
                               @php
                               if($period<$from_the_year-1)
                               {
                                  $ad41=$applicable_short_term_tax_rate;
                               }else{
                                  $ad41=$applicable_long_term_tax_rate;
                               }
                               @endphp
                               {{number_format($ad41,2)}}%
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Tax Payable
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u42=$u40*$applicable_short_term_tax_rate/100;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($u42)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad42=$ad40*$ad41/100;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ad42)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Post-Tax Returns (Rs)
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u43=$u37-$u42;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($u43)}}
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad43=$ad37-$ad42;
                               @endphp
                               <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ad43)}}
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                              Post-Tax IRR (%)
                            </td>
                            <td style="text-align:right;">
                               @php
                               $u44=(pow((($initial_investment+$u43)/$initial_investment),(1/$period))-1)*100;
                          
                               @endphp
                               {{number_format($u44,2)}}%
                            </td>
                            <td style="text-align:right;">
                               @php
                               $ad44=(pow((($initial_investment+$ad43)/$initial_investment),(1/$period))-1)*100;
                               @endphp
                               {{number_format($ad44,2)}}%
                            </td>
                        </tr>
                       
                    
                        </tbody>
                    </table>

                   

                    @php
                    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Bank_Fixed_Deposit_vs_Debt_Mutual_Fund')->first();
                    if(!empty($note_data1)){
                    @endphp
                    {!!$note_data1->description!!}
                    @php } @endphp

                    </div>

                    <?php if(isset($benefit)){ 
                      $page_data = \App\Models\Calculator_note::where('category','debt_funds')->first();
                      if(!empty($page_data)){
                    ?>
                     @include('frontend.calculators.common.footer')
                    <div style="page-break-after: always;"></div>

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

                <div style="padding: 0 5%;">

                    <h1 style="color: #000;font-size:16px;margin-bottom:10px !important;text-align:center;">{{$page_data->name}}</h1>
                    
                    {!!$page_data->description!!}
                </div>

                    <?php }} ?>
   

    @include('frontend.calculators.common.footer')

    @include('frontend.calculators.suggested.pdf')

</main>
</body>
</html>
