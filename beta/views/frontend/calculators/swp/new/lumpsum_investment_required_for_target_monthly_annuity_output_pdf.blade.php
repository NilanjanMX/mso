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
        table.small-font td{
            font-size: 12px;
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

@php
        //Annuity Period (Months) T9*12
        $annuity_period_months = $period*12;
        //Monthly Rate of Return (1)  (1+T11%)^(1/12)-1
        if($deferment=='yes')
        {

        if($include_inflation=='yes')
        {
            $av33=$deferment_period*12;
            $monthly_rate_of_return1 = pow((1+$distribution_phase_interest_rate_1/100),(1/12))-1; 
            $av35=pow((1+$expected_inflation_rate/100),(1/12))-1;
            $lfb1=$balance_required/(1+$monthly_rate_of_return1)**$annuity_period_months; 
            $lfa1=$initial_investment*((1-((1+$av35)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av35)); 
            $av39=$lfb1+$lfa1;
        }else{

            $av33=$deferment_period*12;
            $monthly_rate_of_return1 = pow((1+$distribution_phase_interest_rate_1/100),(1/12))-1; 
            $av35=pow((1+$accumulation_phase_interest_rate_1/100),(1/12))-1;
            $lfb1=$balance_required/(1+$monthly_rate_of_return1)**$annuity_period_months; 
            $lfa1=($initial_investment*(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months)))/$monthly_rate_of_return1; 
            $av39=$lfb1+$lfa1;

        }
            
            if (isset($distribution_phase_interest_rate_2)){

            if($include_inflation=='yes')
            {
                $av36=pow((1+$expected_inflation_rate/100),(1/12))-1; 
                $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
                $lfb2=$balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months; 
                //$lfa2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2; 
                $lfa2=$initial_investment*((1-((1+$av36)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av35));
                //echo $lfa2; die; 
                $av40=$lfb2+$lfa2; 
            }else{

                $av36=pow((1+$accumulation_phase_interest_rate_2/100),(1/12))-1; 
                $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
                $lfb2=$balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months; 
                $lfa2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2; 
                $av40=$lfb2+$lfa2; 
                
            }

            }
        }else{
            $monthly_rate_of_return1 = pow((1+$interest1/100),(1/12))-1 ;
        }



        if (isset($expected_inflation_rate)){
           $av36_inf=pow((1+$expected_inflation_rate/100),(1/12))-1;
        }

        
        //PV of Balance Required (1)  T13/(1+AV27)^(AV26)
        $pv_of_balance_required1 = $balance_required/(1+$monthly_rate_of_return1)**($annuity_period_months);
        //Balance Available for Annuity (1) T8-AV29
        $balance_available_for_annuity1 = $initial_investment-$pv_of_balance_required1;
        //Monthly SWP Amount (1) (AV27*AV31)/(1-(1+AV27)^(-AV26))
        if($deferment=='yes')
        {
            $av43=$av39-$pv_of_balance_required1; 

            if (isset($expected_inflation_rate)){
                if($distribution_phase_interest_rate_1==$expected_inflation_rate)
                {
                    $monthly_annuity_amount1 = $av43*(1+$monthly_rate_of_return1);
                }else{
                    $monthly_annuity_amount1 = $av43/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av36_inf));
                }
            }else{
                $monthly_annuity_amount1 = ($monthly_rate_of_return1*$av43)/(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months));
            }
            
        }else{
            if (isset($expected_inflation_rate)){
                if($interest1==$expected_inflation_rate)
                {
                    $monthly_annuity_amount1 = $balance_available_for_annuity1*(1+$monthly_rate_of_return1);
                }else{
                    $monthly_annuity_amount1 = $balance_available_for_annuity1/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av36_inf));
                }
                $mir=(1+($expected_inflation_rate/100))**(1/12)-1;
                $lumpsum_for_annuity1=$initial_investment*((1-((1+$mir)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$mir)); 
                $lumpsum_investment_required1=$pv_of_balance_required1+$lumpsum_for_annuity1; 
            }else{
                $monthly_annuity_amount1 = ($monthly_rate_of_return1*$balance_available_for_annuity1)/(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months));

                $lumpsum_for_annuity1=($initial_investment*(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months)))/$monthly_rate_of_return1;
                $lumpsum_investment_required1=$pv_of_balance_required1+$lumpsum_for_annuity1;
            }
        }
        if($deferment=='yes')
        {
            if (isset($distribution_phase_interest_rate_2)){
            //Monthly Rate of Return (2)  (1+T12%)^(1/12)-1
            $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
            //PV of Balance Required (2)  T13/(1+AV27)^(AV26)
            $pv_of_balance_required2 = $balance_required/(1+$monthly_rate_of_return2)**($annuity_period_months);
             //Balance Available for Annuity (2) T8-AV30
            $balance_available_for_annuity2 = $initial_investment-$pv_of_balance_required2;
            //Monthly SWP Amount (2) (AV28*AV32)/(1-(1+AV28)^(-AV26))

            $lfb2=$balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months; 
            $lfa2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2; 
            $av44=$lfb2+$lfa2;
            
            //$av44=$av40-$pv_of_balance_required2;

             if (isset($expected_inflation_rate)){
                 if($distribution_phase_interest_rate_2==$expected_inflation_rate)
                 {
                    $monthly_annuity_amount2 = $av44*(1+$monthly_rate_of_return2);
                 }else{
                    $monthly_annuity_amount2 = $av44/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av36_inf));
                 }
             }else{
                $monthly_annuity_amount2 = ($monthly_rate_of_return2*$av44)/(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months));
                
             }
        
            }
        }else{
            if (isset($interest2)){
            //Monthly Rate of Return (2)  (1+T12%)^(1/12)-1
            $monthly_rate_of_return2 = pow((1+$interest2/100),(1/12))-1 ;
            //PV of Balance Required (2)  T13/(1+AV27)^(AV26)
            $pv_of_balance_required2 = $balance_required/(1+$monthly_rate_of_return2)**($annuity_period_months);
             //Balance Available for Annuity (2) T8-AV30
            $balance_available_for_annuity2 = $initial_investment-$pv_of_balance_required2;
            //Monthly SWP Amount (2) (AV28*AV32)/(1-(1+AV28)^(-AV26))
                if (isset($expected_inflation_rate)){
                    if($interest1==$expected_inflation_rate)
                    {
                        $monthly_annuity_amount2 = $balance_available_for_annuity2*(1+$monthly_rate_of_return2);
                    }else{
                        $monthly_annuity_amount2 = $balance_available_for_annuity2/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av36_inf));
                    }
                    $mir=(1+($expected_inflation_rate/100))**(1/12)-1;
                    $lumpsum_for_annuity2=$initial_investment*((1-((1+$mir)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$mir)); 
                    $lumpsum_investment_required2=$pv_of_balance_required2+$lumpsum_for_annuity2;
                }else{
                    $monthly_annuity_amount2 = ($monthly_rate_of_return2*$balance_available_for_annuity2)/(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months));
                    $lumpsum_for_annuity2=($initial_investment*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2;
                    $lumpsum_investment_required2=$pv_of_balance_required2+$lumpsum_for_annuity2;
                }
            }
        }
        //echo $monthly_rate_of_return1; 
        //echo $monthly_rate_of_return2; 
        //echo $pv_of_balance_required1;
        //echo $pv_of_balance_required2;
        //echo $lumpsum_investment_required2; die;
        //echo $av43_new=$av39/(1+$av35)**$av33; die;
        //echo $av43_new2=$av44/(1+$av36)**$av33; die;
        

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
        <h1 style="background:#8edeff; color:#000;margin-bottom:30px !important;text-align:center;font-size:24px !important; padding: 10px;">Monthly SWP Calculation @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
        @php
                    if(isset($current_age) && $current_age>0)
                    {
                        $count_sec=$current_age;
                    }else{
                        $count_sec=0;
                    }

                    if(isset($deferment_period) && $deferment_period>0)
                    {
                        $dif_sec=$deferment_period;
                    }else{
                        $dif_sec=0;
                    }

                    @endphp

                    <table>
                        <tbody>
                        @if(isset($current_age) && $current_age>0)
                        <tr>
                            <td style="text-align: left;">
                                <strong>Current Age</strong>
                            </td>
                            <td colspan="2">
                                {{$current_age}} Years
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td style="text-align: left;">
                                <strong>Target Monthly SWP</strong>
                            </td>
                            <td colspan="2">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>
                        @if($deferment=='yes')
                            <tr>
                                <td style="text-align: left;">
                                    <strong>Deferment Period</strong>
                                </td>
                                <td colspan="2">
                                    {{$deferment_period?$deferment_period:0}} Years
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="text-align: left;">
                                <strong>SWP Period</strong>
                            </td>
                            <td colspan="2">
                                {{$period?$period:0}} Years
                            </td>
                        </tr>
                        <!-- @if($deferment=='no')
                        <tr>
                            <td style="vertical-align: middle;">
                                <strong>Expected Rate of Return</strong>
                            </td>
                            <td style="padding: 0;">
                                @if(isset($interest2))
                                    <table width="100%">
                                        <tbody><tr>
                                            <td>
                                                Scenario 1
                                            </td>
                                            <td>
                                                {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Scenario 2
                                            </td>
                                            <td>
                                                {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                @else
                                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                @endif
                            </td>
                        </tr>
                        @endif -->

                        @if(isset($expected_inflation_rate) && $expected_inflation_rate>0)
                            <tr>
                                <td style="text-align: left;">
                                    <strong>Expected Inflation Rate</strong>
                                </td>
                                <td colspan="2">
                                   {{$expected_inflation_rate?number_format($expected_inflation_rate, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            
                        @endif
                        
                        @if(isset($balance_required))
                            <tr>
                                <td style="text-align: left;">
                                    <strong>Balance Required</strong>
                                </td>
                                <td colspan="2">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balance_required)}}
                                </td>
                            </tr>
                        @endif
                        </tbody></table>
                    

                    @if($deferment=='yes')
                    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Accumulated Corpus Required @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif</h1>


                    
                    @if(isset($distribution_phase_interest_rate_2))
                    
                     <table>
                        <tbody>
                        
                            <tr>
                                <th>
                                    <strong> Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                </th>
                                @if(isset($distribution_phase_interest_rate_2))
                                <th>
                                   <strong> Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                </th>
                                @endif
                            </tr>
                            <tr>
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av39)}}</strong>
                                </td>
                                @if(isset($accumulation_phase_interest_rate_2))
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av40)}} </strong>
                                </td>
                                @endif
                            </tr>
                        
                        </tbody></table>
                        @else
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av39)}}</h1>
                        @endif

                    @endif

                    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Lumpsum Investment Required @if($deferment=='yes') @if(!isset($accumulation_phase_interest_rate_2)) @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif </h1>

                    @if($deferment=='no')
                    @if(isset($interest2))
                    <table>
                        <tbody>
                        
                            <tr>
                                <th>
                                    <strong> Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % </strong>
                                </th>
                                <th>
                                    <strong> Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} % </strong>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required1)}} </strong>
                                </td>
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required2)}} </strong>
                                </td>
                            </tr>
                        
                        </tbody></table>

                    @else

                    
                    <h1 style="color: #000;border:1px black solid;margin-top: 20px !important;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required1)}}</h1>
                   

                    @endif
                    @else
                    @if(isset($accumulation_phase_interest_rate_2))
                    <table>
                        <tbody>
                        
                            <tr>
                                <th>
                                    <strong> Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </strong>
                                </th>
                                @if(isset($distribution_phase_interest_rate_2))
                                <th>
                                    <strong> Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </strong>
                                </th>
                                @endif
                            </tr>
                            <tr>
                                @if($include_inflation=='yes')
                                @php
                                //$av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                $av35=(1+($accumulation_phase_interest_rate_1/100))**(1/12)-1;
                                $av43_new=$av39/(1+$av35)**$av33;
                                @endphp
                                
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new)}}</strong>
                                </td>
                                @else
                                @php
                                $av43_new=$av39/(1+$av35)**$av33;
                                @endphp
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new)}}</strong>
                                </td>
                                @endif
                                @if(isset($distribution_phase_interest_rate_2))
                                @if($include_inflation=='yes')
                                @php
                                //$av43_new2=($monthly_annuity_amount2*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                $av45=(1+($accumulation_phase_interest_rate_2/100))**(1/12)-1;
                                $av43_new2=$av40/(1+$av45)**$av33;
                                @endphp
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new2)}}</strong>
                                </td>
                                @else
                                @php
                                $av43_new2=$av44/(1+$av36)**$av33;
                                @endphp
                                <td>
                                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new2)}} </strong>
                                </td>
                                @endif
                                @endif
                            </tr>
                        
                        </tbody></table>
                    @else

                    @if($include_inflation=='yes')
                    @php
                    //$av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                    $av35=(1+($accumulation_phase_interest_rate_1/100))**(1/12)-1;
                    $av43_new=$av39/(1+$av35)**$av33;
                    @endphp
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new)}}</h1>
                    @else
                    @php
                    $av43_new=$av39/(1+$av35)**$av33;
                    @endphp
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new)}}</h1>
                    @endif

                    @endif
                    @endif

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Total Withdrawal</h1>

                        
                    <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                        @php
                            $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                            $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                        @endphp 
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av50_new)}}</h1>
                    <?php } elseif ($deferment == 'yes' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                        @php
                            $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                            $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                        @endphp 
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av50_new)}}</h1>
                    <?php } elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment * $annuity_period_months)}}</h1>
                    <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment * $annuity_period_months)}}</h1>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                        @php
                            $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                            $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                        @endphp 
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av50_new)}}</h1>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                        @php
                            $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                            $av50_new=($initial_investment*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                        @endphp 
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av50_new)}}</h1>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment * $annuity_period_months)}}</h1>
                    <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                        <h1 style="color: #000;border:1px black solid;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment * $annuity_period_months)}}</h1>
                    <?php } ?>

                    </div>

                    <p>* It is assumed that SWP amount is received on the last day of each month starting from the 1st month. Mutual fund investments are subject to marker risks, read all scheme related documents carefully. Returns are not guaranteed. The above is for illustration purpose only.</p>
                    @include('frontend.calculators.common.footer')
                    <div class="page-break"></div>
                    @if(isset($report) && $report=='detailed')
                    @if($deferment=='yes')

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

                    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Accumulation Phase <br> Projected Annual Investment Value</h1>
                     <table style="background: #fff;">
                        <tbody>
                            <tr>
                                    <th>@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th>Annual Investment</th>
                                    <th>Year End Value @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>

                                    @if(isset($accumulation_phase_interest_rate_2))
                                    <th>Annual Investment</th>
                                    <th>Year End Value @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                            </tr>
                            @for($i=1;$i<=$deferment_period;$i++)
                            @php
                                $av43_new=$av39/(1+$av35)**$av33;
                                $yev1=$av43_new*(1+$accumulation_phase_interest_rate_1/100)**$i;
                                if(isset($accumulation_phase_interest_rate_2))
                                {
                                    $yev2=$av43_new2*(1+$accumulation_phase_interest_rate_2/100)**$i;
                                }
                            @endphp
                            <tr>
                                    <td>{{$count_sec+$i}} </td>
                                    <td> @if($i==1) <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new)}} @else -- @endif</td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($yev1)}}</td>
                                    @if(isset($accumulation_phase_interest_rate_2))
                                    <td> @if($i==1) <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av43_new2)}} @else -- @endif</td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($yev2)}}</td>
                                    @endif
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
                                    <th>@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th>Annual Investment</th>
                                    <th>Year End Value @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>

                                    @if(isset($accumulation_phase_interest_rate_2))
                                    <th>Annual Investment</th>
                                    <th>Year End Value @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                            </tr>
                            @endif
                            @endfor
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
                     
                    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Distribution Phase <br> Monthly Withdrawal & Projected Investment Value</h1>
                    @if(isset($expected_inflation_rate))
                    <table style="background: #fff;">
                    <tbody>
                    <tr>
                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                            <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                            @if(isset($distribution_phase_interest_rate_2))
                            <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                            @endif
                    </tr>
                    <tr>
                        <th>Monthly SWP</th>
                        <th>Year End Balance</th>
                        @if(isset($distribution_phase_interest_rate_2))
                        <th>Monthly SWP</th>
                        <th>Year End Balance</th>
                        @endif
                    </tr>
                            @php
                                $ff1=0;
                                $ff2=0;
                                $z=1;
                                for($i=1;$i<=$period*12;$i++)
                                {
                                if($i==1)
                                {
                                    $as63=$av39;
                                }else{
                                    $as63=$ax63;
                                }
                                
                                $at63=$monthly_rate_of_return1;
                                $au63=$as63+$as63*$at63;
                                $av63=$av36_inf;
                                if($i==1)
                                {
                                    $aw63=$initial_investment;
                                }else{
                                    $aw63=$aw63+$aw63*$av63;
                                }
                                $ax63=$au63-$aw63;
                                $ff1+=$aw63;

                                if(isset($distribution_phase_interest_rate_2))
                                {
                                    if($i==1)
                                    {
                                        $az63=$av40;
                                    }else{
                                        $az63=$be63;
                                    }
                                    
                                    $ba63=$monthly_rate_of_return2;
                                    $bb63=$az63+$az63*$ba63;
                                    $bc63=$av36_inf;
                                    if($i==1)
                                    {
                                        $bd63=$initial_investment;
                                    }else{
                                        $bd63=$bd63+$bd63*$bc63;
                                    }
                                    $be63=$bb63-$bd63;
                                    $ff2+=$bd63;
                                }
                                
                                
                                if($i%12==0)
                                {
                                $ff1r=$ff1/12;
                                if(isset($distribution_phase_interest_rate_2))
                                {
                                    $ff2r=$ff2/12;
                                }
                                @endphp
                                    <tr>
                                    <td>{{$count_sec+$dif_sec+$z}}</td>
                                    @if($include_taxation=='yes' && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                    @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ff1r)}}</td>
                                    @endif
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($ax63)}}</td>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    @if($include_taxation=='yes' && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                    @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ff2r)}}</td>
                                    @endif
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($be63)}}</td>
                                    @endif
                                    </tr>
                                    @if($z%25==0 && $period>25 && $period>$z)
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
                                        <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    @endif
                                </tr>
                                @endif
                                @php
                                $z++;
                                $ff1=0;
                                if(isset($distribution_phase_interest_rate_2))
                                {
                                    $ff2=0;
                                }
                                }
                                }
                            @endphp
                    </tbody>
                    </table>
                    
                    @else

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

                        <table style="background: #fff;">
                            <tbody>
                            @if(isset($distribution_phase_interest_rate_2))
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>

                                @for($i=1;$i<=$period;$i++)
                                    @php
                                       //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                        $year_end_value_senario_1 = ($av39*(1+$monthly_rate_of_return1)**($i*12)-($initial_investment*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                        //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                        $year_end_value_senario_2 = ($av40*(1+$monthly_rate_of_return2)**($i*12)-($initial_investment*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                    @endphp
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$i}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_2)}}</td>
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>
                                @endif
                                @endfor
                            @else
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                   
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    
                                </tr>

                                @for($i=1;$i<=$period;$i++)
                                    @php
                                       //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                        $year_end_value_senario_1 = ($av39*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                        //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                       
                                    @endphp
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$i}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                        
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th>Monthly SWP</th>
                                        <th>Year End Balance</th>
                                    </tr>
                                    @endif;
                                @endfor
                                <!-- <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Year</th>
                                    @if($deferment=='no')
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @else
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>wwwwww Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>

                                @for($i=1;$i<=$period;$i++)
                                    @php
                                        //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                         $year_end_value_senario_1 = ($initial_investment*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));

                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                    </tr>
                                @endfor -->
                            @endif
                            </tbody>
                        </table>

                        

                        @endif
                        <!-- xxxxxxxxxxxx -->
                    @else
                        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Monthly Withdrawal & Projected Investment Value</h1>


                    @if(isset($expected_inflation_rate))
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
                    <table style="background: #fff;">
                    <tbody>
                    <tr>
                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                            <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                            @if(isset($interest2))
                            <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                            @endif
                    </tr>
                    <tr>
                        <th>Monthly SWP</th>
                        <th>Year End Balance</th>
                        @if(isset($interest2))
                        <th>Monthly SWP</th>
                        <th>Year End Balance</th>
                        @endif
                    </tr>
                            @php
                                $ff1=0;
                                $ff2=0;
                                $z=1;
                                for($i=1;$i<=$period*12;$i++)
                                {
                                if($i==1)
                                {
                                    $as63=$initial_investment;
                                }else{
                                    $as63=$ax63;
                                }
                                
                                $at63=$monthly_rate_of_return1;
                                if($i==1)
                                {
                                $au63=$lumpsum_investment_required1+$lumpsum_investment_required1*$at63;
                                }else{
                                $au63=$as63+$as63*$at63;
                                }
                                $av63=$av36_inf;
                                if($i==1)
                                {
                                    $aw63=$initial_investment;
                                }else{
                                    $aw63=$aw63+$aw63*$av63;
                                }
                                $ax63=$au63-$aw63;
                                $ff1+=$aw63;
                                if(isset($interest2))
                                {
                                    if($i==1)
                                    {
                                        $az63=$initial_investment;
                                    }else{
                                        $az63=$be63;
                                    }
                                    
                                    $ba63=$monthly_rate_of_return2;

                                    //$bb63=$az63+$az63*$ba63;
                                    
                                    if($i==1)
                                    {
                                    $bb63=$lumpsum_investment_required2+$lumpsum_investment_required2*$ba63;
                                    }else{
                                    $bb63=$az63+$az63*$ba63;
                                    }

                                    $bc63=$av36_inf;
                                    if($i==1)
                                    {
                                        $bd63=$initial_investment;
                                    }else{
                                        $bd63=$bd63+$bd63*$bc63;
                                    }
                                    $be63=$bb63-$bd63;
                                    $ff2+=$bd63;
                                }
                                
                                
                                if($i%12==0)
                                {
                                $ff1r=$ff1/12;
                                if(isset($interest2))
                                {
                                    $ff2r=$ff2/12;
                                }
                                @endphp

                                    <tr>
                                    <td>{{$count_sec+$dif_sec+$z}}</td>
                                    
                                    @if($include_taxation=='yes'  && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                    @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($ff1r)}}</td>
                                    @endif
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($ax63)}}</td>
                                    @if(isset($interest2))
                                    @if($include_taxation=='yes'  && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                    @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($ff2r)}}</td>
                                    @endif
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($be63)}}</td>
                                    @endif
                                    </tr>
                                    @if($z%25==0 && $period>25 && $period>$z)
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
                                    <table style="background: #fff;">
                                    <tbody>
                                    <tr>
                                            <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            @if(isset($interest2))
                                            <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            @endif
                                    </tr>
                                    <tr>
                                        <th>Monthly SWP</th>
                                        <th>Year End Balance</th>
                                        @if(isset($interest2))
                                        <th>Monthly SWP</th>
                                        <th>Year End Balance</th>
                                        @endif
                                    </tr>

                            @endif
                                @php
                                $z++;
                                $ff1=0;
                                if(isset($interest2))
                                {
                                    $ff2=0;
                                }
                                }
                                }
                            @endphp
                    </tbody>
                    </table>
                    @else

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

                        <table style="background: #fff;">
                            <tbody>
                            @if(isset($interest2))
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>

                                @for($i=1;$i<=$period;$i++)
                                    @php
                                       //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                        $year_end_value_senario_1 = ($lumpsum_investment_required1*(1+$monthly_rate_of_return1)**($i*12)-($initial_investment*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                        //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                        $year_end_value_senario_2 = ($lumpsum_investment_required2*(1+$monthly_rate_of_return2)**($i*12)-($initial_investment*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                       
                                    @endphp

                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$i}}</td>

                                        @if($include_taxation=='yes'  && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                        @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <!-- {{custome_money_format($lumpsum_investment_required1)}} -->{{custome_money_format($initial_investment)}}</td>
                                        @endif

                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                        @if($include_taxation=='yes'  && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                        @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <!-- {{custome_money_format($lumpsum_investment_required2)}} -->
                                        {{custome_money_format($initial_investment)}}</td>
                                        @endif
                                        <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_2)}}</td>
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>
                                @endif
                                @endfor
                            @else
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    @if($deferment=='no')
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @else
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Monthly SWP</th>
                                    <th>Year End Balance</th>
                                </tr>
                                @for($i=1;$i<=$period;$i++)
                                    @php
                                        //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                         $year_end_value_senario_1 = ($lumpsum_investment_required1*(1+$monthly_rate_of_return1)**($i*12)-($initial_investment*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));

                                    @endphp
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$i}}</td>
                                        @if($include_taxation=='yes'  && $include_inflation=='no')
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                                        @else
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <!-- {{custome_money_format($lumpsum_investment_required1)}} -->{{custome_money_format($initial_investment)}}</td>
                                        @endif
                                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    @if($deferment=='no')
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @else
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @endif
                                    </tr>
                                    <tr>
                                        <th>Monthly SWP</th>
                                        <th>Year End Balance</th>
                                    </tr>
                                    @endif
                                @endfor
                            @endif
                            </tbody>
                        </table>

                    @endif
                     @endif

                     <!-- ************************* -->

                        @if($include_taxation=='yes' && $deferment=='no')

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

                        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Annual Tax & Post-Tax Withdrawal</h1>
                        <table class="small-font">
                            <tbody>
                             <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @if(isset($interest2))
                                    <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($interest2))
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                            @if($include_inflation=='yes')

                             @php
                                $yr=1;
                                $l147=0;
                                $q147=0;
                                $g103=0;

                                $w103=0;
                                $ab107=0;
                                $ag107=0;
                                for($i=1;$i<=$period*12;$i++)
                                {

                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                if($i==1)
                                {
                                    $ax=$initial_investment;
                                }else{
                                    $ax=$ax+$ax*$av41_inf;
                                }

                                $av37=(1+($interest1/100))**(1/12)-1;
                                $az=$ax/(1+$av37)**$i;

                                $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);

                                if($yr<=$for_period_upto)
                                {
                                    $bd63=$ax-$az;
                                }else{
                                    $bd63=$ax-$bc63;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                }

                                $bh63=$ax-$bg63;

                                $g103+=$ax;
                                $l147+=$bg63;
                                $q147+=$bh63;
                                
                               //--------ggggggggg

                                if(isset($interest2))
                                {

                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                if($i==1)
                                {
                                    $bo=$initial_investment;
                                }else{
                                    $bo=$bo+$bo*$av41_inf;
                                }

                                $w103+=$bo;

                                $bl63=(1+($interest2/100))**(1/12)-1;
                                $bq63=$bo/(1+$bl63)**$i;

                                $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);

                                if($yr<=$for_period_upto)
                                {
                                    $bu63=$bo-$bq63;
                                }else{
                                    $bu63=$bo-$bt63;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                }

                                $ab107+=$bx63;
                                $by63=$bo-$bx63;
                                $ag107+=$by63;

                                }

                                if($i%12==0)
                                {
                            @endphp
                            <!-- nnnnnnnnnnnnnn -->
                            <tr>
                                <td>{{$count_sec+$dif_sec+$yr}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($g103)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($l147)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($q147)}}</td>
                                @if(isset($interest2))
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($w103)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                @endif
                            </tr>
                             @if($yr%25==0 && $period>25 && $period>$yr)
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @if(isset($interest2))
                                    <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($interest2))
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                            @endif
                            @php
                                    $g103=0;
                                    $q147=0;
                                    $l147=0;
                                    
                                    $w103=0;
                                    $ag107=0;
                                    $ab107=0;
                                    $yr++;
                                }
                                }
                            @endphp

                            @else
                            @php
                                $yr=1;
                                $l147=0;
                                $q147=0;
                                $g103=0;

                                $w103=0;
                                $ab107=0;
                                $ag107=0;
                                for($i=1;$i<=$period*12;$i++)
                                {
                                //zzzzzzzzzzzz
                                
                                $ax=$initial_investment;
                                

                                $av37=(1+($interest1/100))**(1/12)-1;
                                $az=$ax/(1+$av37)**$i;

                                $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);

                                if($yr<=$for_period_upto)
                                {
                                    $bd63=$ax-$az;
                                }else{
                                    $bd63=$ax-$bc63;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                }

                                $bh63=$ax-$bg63;

                                $g103+=$ax;
                                $l147+=$bg63;
                                $q147+=$bh63;
                                
                               //--------

                                if(isset($interest2))
                                {

                               
                                $bo=$initial_investment;
                               

                                $w103+=$bo;

                                $bl63=(1+($interest2/100))**(1/12)-1;
                                $bq63=$bo/(1+$bl63)**$i;

                                $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);

                                if($yr<=$for_period_upto)
                                {
                                    $bu63=$bo-$bq63;
                                }else{
                                    $bu63=$bo-$bt63;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                }

                                $ab107+=$bx63;
                                $by63=$bo-$bx63;
                                $ag107+=$by63;

                                }

                                if($i%12==0)
                                {
                            @endphp
                            <!-- nnnnnnnnnnnnnn -->
                            <tr>
                                <td>{{$count_sec+$dif_sec+$yr}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment*12)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($l147)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($q147)}}</td>
                                @if(isset($interest2))
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment*12)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                @endif
                            </tr>
                            @if($yr%25==0 && $period>25 && $period>$yr)
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @if(isset($interest2))
                                    <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($interest2))
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                            @endif
                            @php
                                    $g103=0;
                                    $q147=0;
                                    $l147=0;
                                    
                                    $w103=0;
                                    $ag107=0;
                                    $ab107=0;
                                    $yr++;
                                }
                                }
                            @endphp

                            @endif

                            </tbody>
                        </table>

                        @endif

                        @if($include_taxation=='yes' && $deferment=='yes')

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

                        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Annual Tax & Post-Tax Withdrawal</h1>
                        <table class="small-font">
                            <tbody>
                             <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th colspan="3">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                             @if($include_inflation=='yes')

                                @php
                                $yr=1;
                                $l147=0;
                                $q147=0;
                                $g103=0;

                                $w103=0;
                                $ab107=0;
                                $ag107=0;
                                for($i=1;$i<=$period*12;$i++)
                                {

                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                if($i==1)
                                {
                                    $ax=$initial_investment;
                                }else{
                                    $ax=$ax+$ax*$av41_inf;
                                }
                                //echo $ax.'---';

                                $av37=(1+($distribution_phase_interest_rate_1/100))**(1/12)-1;
                                $az=$ax/(1+$av37)**$i;

                                $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);

                                if($yr<=$for_period_upto)
                                {
                                    $bd63=$ax-$az;
                                }else{
                                    $bd63=$ax-$bc63;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                }

                                $bh63=$ax-$bg63;

                                $g103+=$ax;
                                $l147+=$bg63;
                                $q147+=$bh63;
                                
                               //--------

                                if(isset($distribution_phase_interest_rate_2))
                                {

                                $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                if($i==1)
                                {
                                    $bo=$initial_investment;
                                }else{
                                    $bo=$bo+$bo*$av41_inf;
                                }

                                $w103+=$bo;

                                $bl63=(1+($distribution_phase_interest_rate_2/100))**(1/12)-1;
                                $bq63=$bo/(1+$bl63)**$i;

                                $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);

                                if($yr<=$for_period_upto)
                                {
                                    $bu63=$bo-$bq63;
                                }else{
                                    $bu63=$bo-$bt63;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                }

                                $ab107+=$bx63;
                                $by63=$bo-$bx63;
                                $ag107+=$by63;

                                }

                                if($i%12==0)
                                {
                            @endphp
                            <!-- nnnnnnnnnnnnnn -->
                            <tr>
                                <td>{{$count_sec+$dif_sec+$yr}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($g103)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($l147)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($q147)}}</td>
                                @if(isset($distribution_phase_interest_rate_2))
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($w103)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                @endif
                            </tr>
                            @if($yr%25==0 && $period>25 && $period>$yr)
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
                            <table class="small-font">
                            <tbody>
                              <tr>
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th colspan="3">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                            @endif
                            @php
                                    $g103=0;
                                    $q147=0;
                                    $l147=0;
                                    
                                    $w103=0;
                                    $ag107=0;
                                    $ab107=0;
                                    $yr++;
                                }
                                }
                            @endphp
                            @else
                            @php
                                $yr=1;
                                $l147=0;
                                $q147=0;
                                $ab107=0;
                                $ag107=0;
                                for($i=1;$i<=$period*12;$i++)
                                {
                                $av37=(1+($distribution_phase_interest_rate_1/100))**(1/12)-1;
                                $ay107=$monthly_annuity_amount1/(1+$av37)**$i;
                                $bb107=$ay107*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
                                if($yr<=$for_period_upto)
                                {
                                    $bc=$monthly_annuity_amount1-$ay107;
                                }else{
                                    $bc=$monthly_annuity_amount1-$bb107;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bf=$bc*($applicable_short_term_tax_rate/100);
                                }else{
                                    $bf=$bc*($applicable_long_term_tax_rate/100);
                                }

                                $l147+=$bf;
                                $bg107=$monthly_annuity_amount1-$bf;
                                $q147+=$bg107;
                                
                                if(isset($distribution_phase_interest_rate_2))
                                {
                                $bm107=$monthly_annuity_amount2;
                                $bk107=(1+($distribution_phase_interest_rate_2/100))**(1/12)-1;
                                $bo107=$monthly_annuity_amount2/(1+$bk107)**$i;
                                $br107=$bo107*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
                                
                                if($yr<=$for_period_upto)
                                {
                                    $bs107=$bm107-$bo107;
                                }else{
                                    $bs107=$bm107-$br107;
                                }

                                if($yr<=$for_period_upto)
                                {
                                    $bv107=$bs107*$applicable_short_term_tax_rate/100;
                                }else{
                                    $bv107=$bs107*$applicable_long_term_tax_rate/100;
                                }
                                $ab107+=$bv107;
                                $bw107=$bm107-$bv107;
                                $ag107+=$bw107;
                                }
                                if($i%12==0)
                                {
                            @endphp
                            <tr>
                                <td>{{$count_sec+$dif_sec+$yr}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_annuity_amount1*12)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($l147)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($q147)}}</td>
                                @if(isset($distribution_phase_interest_rate_2))
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_annuity_amount2*12)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                @endif
                            </tr>
                            @if($yr%25==0 && $period>25 && $period>$yr)
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
                                    <th rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th colspan="3">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th colspan="3">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th>Annual Withdrawal</th>
                                    <th style="width: 70px;">Tax Payable</th>
                                    <th>Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                            @endif
                            @php
                                    $ag107=0;
                                    $ab107=0;
                                    $q147=0;
                                    $l147=0;
                                    $yr++;
                                }
                                }
                            @endphp
                            @endif
                            </tbody>
                        </table>

                        @endif
        <p style="text-align: left; margin-top: 5px;">*The above chart is approximate and for illustration purpose only</p>
        @include('frontend.calculators.common.footer')

    @endif
    @include('frontend.calculators.suggested.pdf')
</main>
</body>
</html>