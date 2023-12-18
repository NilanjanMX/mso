<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Result</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        @include('frontend.calculators.common.pdf_style')
    </head>
    <body class="styleApril">
          
    @include('frontend.calculators.common.header')
        
        <main style="width: 806px;">
            <div style="padding: 0 0%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Lumpsum Investment @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <strong>Initial Investment</strong>
                                </td>
                                <td style="width: 50%;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <strong>Time Period</strong>
                                </td>
                                <td style="width: 50%;">
                                    {{$period?$period:0}} Years
                                </td>
                            </tr>
                            @if(!isset($interest2))
                                <tr>
                                    <td style="left;Width:50%;">
                                        <strong>Assumed Rate of Return </strong>
                                    </td>
                                    <td style="width:50%;">
                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td style="width: 50%;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="padding: 0;width: 50%;">
                                        @if(isset($interest2))
                                            <table width="100%" style="margin: 0">
                                                <tbody>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        Scenario 1
                                                    </td>
                                                    <td style="width: 50%;">
                                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        Scenario 2
                                                    </td>
                                                    <td style="width: 50%;">
                                                        {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                    
                                        @else
                                            @ {{$interest1?number_format($interest1, 2, '.', ''):0}}% : <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{number_format(($amount*pow((1+($interest1/100)), $period)))}}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                    
                        </tbody>
                    </table>

                </div>
            </div>
            
            @if(!isset($interest2))
                <div style="padding: 0 20%;">
            @endif
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Expected Future Value</h1>
            <table class="table table-bordered text-center">
                <tbody>
                @if(isset($interest2))
                    <tr>
                        <th style="width: 50%;">
                            Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                        </th>
                        <th style="width: 50%;">
                            Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount*pow((1+($interest1/100)), $period)))}} </strong>
                        </td>
                        <td style="width: 50%;">
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount*pow((1+($interest2/100)), $period)))}} </strong>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>
                            <strong>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format(($amount*pow((1+($interest1/100)), $period)))}}
                            </strong>
                        </td>
                    </tr>
                @endif
                </tbody></table>

            @if(!isset($interest2))
                </div>
            @endif
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
                    <h1 style="background-color: #131f55;color:#fff !important;font-size:16px;padding:5px;text-align:center;">Projected Annual Investment Value</h1>
                <table>
                    <tbody>
                    @if(isset($interest2))
                        <tr>
                            <th>Year</th>
                            <th>Annual Investment</th>
                            <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                            <th>Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                        </tr>
                        @php
                            $previous_amount_int1 = $amount;
                            $previous_amount_int2 = $amount;
                        @endphp

                        @for($i=1;$i<=$period;$i++)
                            @php
                                $previous_amount_int1 = $previous_amount_int1+ ($previous_amount_int1* $interest1/100);
                                $previous_amount_int2 = $previous_amount_int2+ ($previous_amount_int2* $interest2/100);
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    @if($i==1)
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                            </tr>


                            @if($i%25==0 && $period>25 && $period>$i)
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
                                            <th>Year</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            <th>Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        </tr>
                            @endif


                        @endfor
                    @else
                        <tr>
                            <th>Year</th>
                            <th>Annual Investment</th>
                            <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                        </tr>
                        @php
                            $previous_amount_int1 = $amount;
                        @endphp

                        @for($i=1;$i<=$period;$i++)
                            @php
                                $previous_amount_int1 = $previous_amount_int1+ ($previous_amount_int1* $interest1/100);
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    @if($i==1)
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                                    @else
                                        --
                                    @endif
                                </td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                            </tr>

                            @if($i%25==0 && $period>25 && $period>$i)
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
                                            <th>Year</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        </tr>
                            @endif

                        @endfor
                    @endif
                    </tbody>
                </table>
                
                    
            @endif
            @if($is_note)
                <div style="padding: 0 0%;">
                    <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Comment</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <tbody>
                                <tr>
                                    <td>{{$note}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            

            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_Lump_sum_Investment')->first();
                if(!empty($note_data1)){
            @endphp
                {!!$note_data1->description!!}
            @php } @endphp
            Report Date : {{date('d/m/Y')}}
            
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
            @include('frontend.calculators.suggested.pdf')
        </main>
    </body>
</html>