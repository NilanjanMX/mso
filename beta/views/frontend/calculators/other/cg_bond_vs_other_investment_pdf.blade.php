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
                        <h1 style="background:#8edeff; color:#000;margin-bottom:30px !important;text-align:center;font-size:24px !important; padding: 10px;">Capital Gains Bond vs. Other Investment Planning @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                        <h2 style="color: #000;font-size:18px;margin-bottom:20px !important;text-align:center;">Computation of Maturity Amount and Post-Tax IRR</h2>
                    @php
                    $t8=$capital_gain_amount;
                    $t9=$ltcg_tax_rate;
                    $l45=$t8;
                    $l46=0;
                    $t11=$interest_cg_bond_scheme;
                    $t12=$expected_indexation_rate;
                    $t13=$applicable_income_tax_slab;
                    $t10=$period;
                    $l48=$t11/100;
                    @endphp

                     <table class="table table-bordered text-center">
                        <tbody><tr>
                            <td style="width: 25%; text-align:left;">
                                <strong>Particulars</strong>
                            </td>
                            <td style="width: 15%; text-align:center;">
                               <strong>54EC Bond</strong>
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td style="width: 15%; text-align:center;">
                               <strong>Fixed Deposit</strong>
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td style="width: 15%; text-align:center;">
                               <strong>Debt Fund</strong>
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td style="width: 15%; text-align:center;">
                               <strong>Equity Fund</strong>
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td style="width: 15%; text-align:center;">
                               <strong>{{$product_name}}</strong>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Capital Gain Amount
                            </td>
                            <td>
                            {{custome_money_format($t8)}}
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               @php 
                               $s45=$t8;
                               echo custome_money_format($s45);
                               @endphp

                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                               @php 
                               $z45=$t8;
                               echo custome_money_format($z45);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               @php 
                               $ag45=$t8;
                               echo custome_money_format($ag45);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                               @php 
                               $an45=$t8;
                               echo custome_money_format($an45);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                LTCG Tax
                            </td>
                            <td>
                                0
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               @php 
                               $s46=$t8*($t9/100);
                               echo custome_money_format($s46);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                               @php 
                               $z46=$t8*($t9/100);
                               echo custome_money_format($z46);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               @php 
                               $ag46=$t8*($t9/100);
                               echo custome_money_format($ag46);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                               @php 
                               $an46=$t8*($t9/100);
                               echo custome_money_format($an46);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Net Investment
                            </td>
                            <td>
                               @php 
                               $l47=$l45-$l46;
                               echo custome_money_format($l47);
                               @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               @php 
                               $s47=$s45-$s46;
                               echo custome_money_format($s47);
                               @endphp
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td>
                               @php 
                               $z47=$z45-$z46;
                               echo custome_money_format($z47);
                               @endphp
                            </td>
                            <?php } ?>
                             <?php if($equity_fund=='yes'){ ?>
                            <td>
                               @php 
                               $ag47=$ag45-$ag46;
                               echo custome_money_format($ag47);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                               @php 
                               $an47=$an45-$an46;
                               echo custome_money_format($an47);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Assumed Return
                            </td>
                            <td>
                                {{number_format($t11,2)}} %
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               @php 
                               $s48=$fixed_deposit_expected_return;
                               echo number_format($s48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                               @php 
                               $z48=$debt_fund_expected_return;
                               echo number_format($z48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               @php 
                               $ag48=$equity_fund_expected_return;
                               echo number_format($ag48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                               @php 
                               $an48=$other_expected_return;
                               echo number_format($an48, 2);
                               @endphp
                               %
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Investment Period (Yrs)
                            </td>
                            <td>
                                {{$l49=$t10}}
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               {{$s49=$t10}}
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                               {{$z49=$t10}}
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               {{$ag49=$t10}}
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                {{$an49=$t10}}
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Indexation Benefit
                            </td>
                            <td>
                                No
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               {{$s50=ucfirst($fixed_deposit_indexation_benefit)}}
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                               {{$z50=ucfirst($debt_fund_indexation_benefit)}}
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               {{$ag50=ucfirst($equity_fund_indexation_benefit)}}
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                {{$an50=ucfirst($other_indexation_benefit)}}
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Maturity Amount
                            </td>
                            <td>
                            @php
                                $x1=pow(1+$l48,$l49);
                                $l51=$l47*$x1;
                                echo custome_money_format($l51);
                            @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                            @php
                                $s51=$s47*pow(1+$s48/100,$s49);
                                echo custome_money_format($s51);
                            @endphp
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                                @php
                                $z51=$z47*pow(1+$z48/100,$z49);
                                echo custome_money_format($z51);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               @php
                                $ag51=$ag47*pow(1+$ag48/100,$ag49);
                                echo custome_money_format($ag51);
                               @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                               @php
                                $an51=$an47*pow(1+$an48/100,$an49);
                                echo custome_money_format($an51);
                               @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Indexation Rate
                            </td>
                            <td>
                                @php
                                $l52=$t12;
                                echo number_format($l52, 2);
                                @endphp
                                %
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                                @php
                                $s52=$t12;
                                echo number_format($s52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                                @php
                                $z52=$t12;
                                echo number_format($z52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                                @php
                                $ag52=$t12;
                                echo number_format($ag52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                @php
                                $an52=$t12;
                                echo number_format($an52, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Indexed Cost
                            </td>
                            <td>
                                @php
                                $l53=$l47;
                                echo custome_money_format($l53);
                                @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                            <?php
                            if($fixed_deposit_indexation_benefit=='yes')
                            {
                               $ss=pow(1+$s52/100,$s49);
                               $s53=$s47*$ss;
                               echo custome_money_format($s53);
                            }else{
                               $s53=$s47;
                               echo custome_money_format($s53);
                            }
                            ?>
                            </td>
                            <?php } ?>

                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                            <?php
                            if($debt_fund_indexation_benefit=='yes')
                            {
                                $zz=pow(1+$z52/100,$z49);
                                $z53=$z47*$zz;
                                echo custome_money_format($z53);
                            }else{
                                $z53=$z47;
                                echo custome_money_format($z53);
                            }
                            ?>
                            </td>
                            <?php } ?>

                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                            <?php
                            if($equity_fund_indexation_benefit=='yes')
                            {
                                $ag=pow(1+$ag52/100,$ag49);
                                $ag53=$ag47*$ag;
                                echo custome_money_format($ag53);
                            }else{
                                $ag53=$ag47;
                                echo custome_money_format($ag53);
                            }
                            ?>
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                            <?php
                            if($other_indexation_benefit=='yes')
                            {
                                $an=pow(1+$an52/100,$an49);
                                $an53=$an47*$an;
                                echo custome_money_format($an53);
                            }else{
                                $an53=$an47;
                                echo custome_money_format($an53);
                            }
                            ?>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                Taxable Income
                            </td>
                            <td>
                                @php
                                $l54=$l51-$l53;
                                echo custome_money_format($l54);
                                @endphp
                                
                            </td>
                             <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                                @php
                                $s54=$s51-$s53;
                                echo custome_money_format($s54);
                                @endphp
                               
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td>
                                @php
                                $z54=$z51-$z53;
                                echo custome_money_format($z54);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                                @php
                                $ag54=$ag51-$ag53;
                                echo custome_money_format($ag54);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                @php
                                $an54=$an51-$an53;
                                echo custome_money_format($an54);
                                @endphp
                            </td>
                            <?php } ?>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                                Applicable Tax Rate
                            </td>
                            <td>
                                @php
                                $l55=$t13;
                                echo number_format($l55, 2);
                                @endphp
                                %
                            </td>
                             <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                                @php
                                $s55=$fixed_deposit_taxation_rate;
                                echo number_format($s55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td>
                                @php
                                $z55=$debt_fund_taxation_rate;
                                echo number_format($z55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                                @php
                                $ag55=$equity_fund_taxation_rate;
                                echo number_format($ag55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                @php
                                $an55=$other_taxation_rate;
                                echo number_format($an55, 2);
                                @endphp
                                %
                            </td>
                            <?php } ?>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                                Tax Amount
                            </td>
                            <td>
                                @php
                                $l56=$l54*($l55/100);
                                echo custome_money_format($l56);
                                @endphp
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                                @php
                                $s56=$s54*($s55/100);
                                echo custome_money_format($s56);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                                @php
                                $z56=$z54*($z55/100);
                                echo custome_money_format($z56);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                                @php
                                $ag56=$ag54*($ag55/100);
                                echo custome_money_format($ag56);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                @php
                                $an56=$an54*($an55/100);
                                echo custome_money_format($an56);
                                @endphp
                            </td>
                            <?php } ?>
                        </tr>
                         <tr>
                            <td style="text-align:left;">
                                Post-Tax Maturity Amount
                            </td>
                            <td>
                                @php
                                $l57=$l51-$l56;
                                echo custome_money_format($l57);
                                @endphp
                                
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                                @php
                                $s57=$s51-$s56;
                                echo custome_money_format($s57);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($debt_fund=='yes'){ ?>
                            <td>
                                @php
                                $z57=$z51-$z56;
                                echo custome_money_format($z57);
                                @endphp
                               
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                                @php
                                $ag57=$ag51-$ag56;
                                echo custome_money_format($ag57);
                                @endphp
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                @php
                                $an57=$an51-$an56;
                                echo custome_money_format($an57);
                                @endphp
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Post-Tax IRR</strong>
                            </td>
                            <td>
                                <strong>{{number_format((pow($l57/$l45,1/$l49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php if($fixed_deposit=='yes'){ ?>
                            <td>
                               <strong>{{number_format((pow($s57/$s45,1/$s49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                             <?php if($debt_fund=='yes'){ ?>
                            <td>
                               <strong>{{number_format((pow($z57/$z45,1/$z49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                            <?php if($equity_fund=='yes'){ ?>
                            <td>
                               <strong>{{number_format((pow($ag57/$ag45,1/$ag49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                            <?php if($user_defined=='yes'){ ?>
                            <td>
                                <strong>{{number_format((pow($an57/$an45,1/$an49)-1)*(100), 2)}} %</strong>
                            </td>
                            <?php } ?>
                        </tr>
                        </tbody></table>
    </div>
   



        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','CG_Bond_vs_Other_Investment')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
        @php } @endphp

    @include('frontend.calculators.common.footer')

    @include('frontend.calculators.suggested.pdf')

</main>
</body>
</html>
