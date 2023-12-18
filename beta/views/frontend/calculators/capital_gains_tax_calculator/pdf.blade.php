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
        $fy_index = $financial_years;
        $assumed = '';
    @endphp
    
    @if($purchased_before == 1)
        @php 
    
            $month = \Carbon\Carbon::parse($sale_date)->format('m');
            $year_long = \Carbon\Carbon::parse($sale_date)->format('Y');
            $year_short = \Carbon\Carbon::parse($sale_date)->format('y');
            
            $year_prev = $year_long - 1;
            $fi_year = ($month >= 4) ? $year_long.'-'.($year_short + 1) : $year_prev.'-'.$year_short;
            if(isset($fy_index[$fi_year])){
                $index = $fy_index[$fi_year];
            }else{
                $month1 = \Carbon\Carbon::parse($sale_date)->format('m');
                $year_long1 = \Carbon\Carbon::parse($sale_date)->format('Y');
                $year_short1 = \Carbon\Carbon::parse($sale_date)->format('y');
                $year_prev1 = $year_long1 - 1;
                $fi_year1 = ($month1 >= 4) ? $year_long1.'-'.($year_short1 + 1) : $year_prev1.'-'.$year_short1;
                
                $index1 = 1;
                $index = 1;
                if(isset($fy_index[$fi_year1])){
                    $index = $fy_index[$fi_year1];
                }
                $assumed = '';
                if(isset($cost_inflation_index) && !empty($cost_inflation_index)){
                    $assumed = 'Assumed';
                    $index = $cost_inflation_index;
                }
            }
            
            $startDate = \Carbon\Carbon::parse('01-05-2001'); 
            $endDate = \Carbon\Carbon::parse($sale_date); 
            $prop_age = $startDate->diffInYears($endDate);
            
    
            $indexed_cost_of_purchase = $index / $fy_index['2001-02'] * $fair_market_value;
            
            $total_indexed_cost = $indexed_cost_of_purchase;
            if(count($financial_year) != 0){
                if($financial_year[0] != null && $cost_of_improvement[0] != null){ 
                foreach ($cost_of_improvement as $key => $value) {
                    $indexed_cost_of_improvement[$key] = round($index / $fy_index[$financial_year[$key]] * $cost_of_improvement[$key], 0);
    
                    $total_indexed_cost += $indexed_cost_of_improvement[$key];
                }
                }
            }
            
            $total_cost_of_improvement = 0;
            if($financial_year[0] != null && $cost_of_improvement[0] != null){ 
                foreach ($financial_year as $key => $fy) {
                    $total_cost_of_improvement += $cost_of_improvement[$key];
                }
            }
    
            $net_sale_consideration = $sales_price - $sales_expenses;
            $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;
            $capital_gain_type = '';
            if($asset_type == 'movable'){
    
                if($prop_age < 3){
                    $capital_gain_type = 'Short Term';
                }else{
                    $capital_gain_type = 'Long Term';
                }
            }else{
                if($prop_age < 2){
                    $capital_gain_type = 'Short Term';
                }else{
                    $capital_gain_type = 'Long Term';
                }
            }
    
            if($capital_gain_type == 'Long Term'){
                $applicable_tax_rate = $long_term_tax;
            }else{
                $total_indexed_cost = $fair_market_value + $total_cost_of_improvement;
                $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;
    
                $applicable_tax_rate = $income_tax_slab;
            }
            
            $applicable_tax = round($taxable_capital_gain * $applicable_tax_rate / 100, 0);
            // dd($applicable_tax);
            if($capital_gain_type == 'Short Term'){
                $indexing = 0;  
            }else{
                $indexing = 1; 
            }               
        @endphp
    
    @else
        @php
            $month = \Carbon\Carbon::parse($sale_date)->format('m');
            $year_long = \Carbon\Carbon::parse($sale_date)->format('Y');
            $year_short = \Carbon\Carbon::parse($sale_date)->format('y');
            $year_prev = $year_long - 1;
            $fi_year = ($month >= 4) ? $year_long.'-'.sprintf("%02d", $year_short + 01) : $year_prev.'-'.$year_short;
            
            $month = \Carbon\Carbon::parse($purchase_date)->format('m');
            $year_long = \Carbon\Carbon::parse($purchase_date)->format('Y');
            $year_short = \Carbon\Carbon::parse($purchase_date)->format('y');
            $year_prev = $year_long - 1;
            $fi_purchase = ($month >= 4) ? $year_long.'-'.sprintf("%02d", $year_short + 01) : $year_prev.'-'.$year_short;
            
            $index1 = 1;
            $index = 1;
            if(isset($fy_index[$fi_year])){
                $index = $fy_index[$fi_year];
            }
            $assumed = '';
            if(isset($cost_inflation_index) && !empty($cost_inflation_index)){
                $index = $cost_inflation_index;
                $assumed = 'Assumed';
            }
            
            $startDate = \Carbon\Carbon::parse($purchase_date); 
            $endDate = \Carbon\Carbon::parse($sale_date); 
            $prop_age = $startDate->diffInYears($endDate);
            
            // dd($index);
            if(isset($fy_index[$fi_purchase])){
                $indexed_cost_of_purchase = round($purchase_price * $index / $fy_index[$fi_purchase], 0);
            }else{
                $indexed_cost_of_purchase = 1;
            }
            
            
            $total_indexed_cost = $indexed_cost_of_purchase;
            if(count($financial_year) != 0){
                if($financial_year[0] != null && $cost_of_improvement[0] != null){ 
                foreach ($cost_of_improvement as $key => $value) {
                    $indexed_cost_of_improvement[$key] = round($index / $fy_index[$financial_year[$key]] * $cost_of_improvement[$key], 0);
    
                    $total_indexed_cost += $indexed_cost_of_improvement[$key];
                }
                }
            }
    
            $total_cost_of_improvement = 0;
            if($financial_year[0] != null && $cost_of_improvement[0] != null){ 
                foreach ($financial_year as $key => $fy) {
                    $total_cost_of_improvement += $cost_of_improvement[$key];
                }
            }

            $net_sale_consideration = $sales_price - $sales_expenses;
            $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;
            $capital_gain_type = '';
            if($asset_type == 'movable'){
                
                if($prop_age < 3){
                    $capital_gain_type = 'Short Term';
                }else{
                    $capital_gain_type = 'Long Term';
                }
            }else{
                if($prop_age < 2){
                    $capital_gain_type = 'Short Term';
                }else{
                    $capital_gain_type = 'Long Term';
                }
            }
    
            if($capital_gain_type == 'Long Term'){
                $applicable_tax_rate = $long_term_tax;
            }else{
                $total_indexed_cost = $purchase_price + $total_cost_of_improvement;
                $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;
                
                $applicable_tax_rate = $income_tax_slab;
            }

            $applicable_tax = round($taxable_capital_gain * $applicable_tax_rate / 100, 0);
            // dd($applicable_tax);
            if($capital_gain_type == 'Short Term'){
                $indexing = 0;  
            }else{
                $indexing = 1; 
            }                   
        @endphp
    
    @endif       
    @include('frontend.calculators.common.header')
        
        <main class="mainPdf">
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Capital Gains Tax Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                            @if(!empty($asset_name))
                            <tr>
                                <td align="left">
                                    <strong> Asset Details</strong>
                                </td>
                                <td align="left">
                                    {{$asset_name}}
                                </td>
                            </tr>
                            @endif
                    @if($purchased_before == 1)
                        <tr>
                            <td align="left">
                                <strong> Fair Market Value (01.04.2001)</strong>
                            </td>
                            <td align="left">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($fair_market_value)}}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td align="left">
                                <strong> Purchase Date</strong>
                            </td>
                            <td align="left">
                                {{$purchase_date}}
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <strong>                         
                                    Cost Of Purchase
                                </strong>
                            </td>
                            <td align="left">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>                         
                                    {{custome_money_format($purchase_price)}}
                            </td>
                        </tr>
                        
                    @endif
                        @if($indexing == 1)
                        <tr>
                            <td align="left">
                                @php if($purchased_before == 1){
                                    $fi_yearrr = '2001-02';
                                }else{
                                    $fi_yearrr = $fi_purchase;
                                }
                                @endphp
                                <strong>  Cost Inflation Index For FY  {{$fi_yearrr}}</strong>
                            </td>
                            <td align="left">
                                    {{  $fy_index[$fi_yearrr] }} 
                            </td>
                        </tr>
                        @endif
                        @if(!empty($financial_year[0]))
                            @foreach($cost_of_improvement as $key=>$cost)
                                <tr>
                                    <td align="left">
                                        <strong> Cost of Improvement in FY {{ $financial_year[$key] }}</strong>
                                    </td>
                                    <td align="left">
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cost_of_improvement[$key])}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        
                        <tr>
                            <td align="left">
                                <strong>  Sale Date</strong>
                            </td>
                            <td align="left">
                                  {{  $sale_date }}
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <strong> Sales Price</strong>
                            </td>
                            <td align="left">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sales_price)}}
                            </td>
                        </tr>
                        @if($indexing == 1)
                        <tr>
                            <td align="left">
                                <strong> {{$assumed}} Cost Inflation Index For FY {{$fi_year}}</strong>
                            </td>
                            
                            <td align="left">
                                @php if(isset($cost_inflation_index) && !empty($cost_inflation_index)){
                                    $ind = $cost_inflation_index;
                                }elseif (isset($fy_index[$fi_year])) {
                                    $ind =  $fy_index[$fi_year];
                                }else{
                                    $ind = '';
                                }
                                @endphp
                                    {{$ind}} 
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td align="left">
                                <strong> Sales Expenses</strong>
                            </td>
                            <td align="left">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sales_expenses)}}
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="left">
                                <strong>  Net Sales Consideration</strong>
                            </td>
                            <td align="left">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($net_sale_consideration)}}
                            </td>
                        </tr>
                        {{-- @if($indexing == 1) --}}
                        <tr>
                            <td align="left">
                                <strong>  @if($indexing == 1) Indexed @endif Cost of Acquisition</strong>
                            </td>
                            <td align="left">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_indexed_cost)}}
                            </td>
                        </tr>
                        {{-- @endif --}}
                        <tr>
                            <td align="left">
                                <strong>  Taxable Capital Gain</strong>
                            </td>
                            <td align="left">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($taxable_capital_gain)}}
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="left">
                                <strong>   Applicable Tax</strong>
                            </td>
                            <td align="left">
                                 {{$capital_gain_type}}
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="left">
                                <strong> Applicable Tax Rate</strong>
                            </td>
                            <td align="left">
                                 {{$applicable_tax_rate}}  %
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="left">
                                <strong>   Applicable Tax</strong>
                            </td>
                            <td align="left">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 
                                @if($applicable_tax <= 0)
                                    NA
                                @else
                                    {{custome_money_format($applicable_tax)}}
                                @endif
                            </td>
                        </tr>
                    
                        </tbody>
                    </table>
                </div>
                   
                    
                    
                    @if($indexing == 1 && $financial_year[0] != null)
                    <p style="text-align:left;">Cost Inflation Index for 
                        @if($financial_year[0] != null && $cost_of_improvement[0] != null)
                            @foreach ($financial_year as $key => $fy)
                                @if($fy != null)
                                    FY {{$fy}} is {{$fy_index[$fy]}},
                                @endif
                            @endforeach
                        @endif
                    </p>
                    @endif
            </div>
            
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP_Required_For_Target_Future_Value')->first();
            @endphp
            @if(!empty($note_data1))
                {!!$note_data1->description!!}
            @endif
            Report Date : {{date('d/m/Y')}}
            
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
        @include('frontend.calculators.suggested.pdf')
    </body>
</html>