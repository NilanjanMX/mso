@extends('layouts.frontend')
@section('js_after')
    <script>
        jQuery(document).ready(function(){
            jQuery('#save_cal_btn').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var title = jQuery('#save_title').val();
                if(title.trim()==''){
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').addClass('alert-danger');
                    jQuery('#save_cal_msg').html('Please Enter Desired Download File Name');
                }else{
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').html('');
                    jQuery('#save_title').val('');
                    jQuery.ajax({
                        url: "{{ route('frontend.capital_gains_tax_calculator_save') }}",
                        method: 'get',
                        data: {
                            title: title
                        },
                        success: function(result){
                            jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                            jQuery('#save_cal_msg').addClass('alert-success');
                            jQuery('#save_cal_msg').html('Data Successfully Saved');
                            setTimeout(function () {
                                $('#saveOutput').modal('toggle');
                                jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                                jQuery('#save_cal_msg').html('');
                            },500);
                            jQuery('.save_only').hide();
                            jQuery('.view_save_only').show();
                        }});
                }

            });
        });
    </script>
    <style>
        
    </style>
@endsection
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="banner styleApril">
        <div class="container">
        @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Capital Gains Tax Calculation</h2>
                </div>
            </div>
        </div>
    </div>
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.capital_gains_tax_calculator_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @endif
                    
                    @if($permission['is_save'])
                        @if($edit_id)
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                        @else
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                        @endif
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.capital_gains_tax_calculator_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h1 class="midheading">Capital Gains Tax Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                        
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
                                            // $index1 = 1;
                                            // if(isset($fy_index[$fi_purchase])){
                                            //     $index1 = $fy_index[$fi_purchase];
                                            // }
                                            // $index = $cost_inflation_index / $index1 * 100;
                                            $index = $cost_inflation_index;
                                            $assumed = 'Assumed';
                                        }
                                    }
                                    

                                    $startDate = \Carbon\Carbon::parse('01-05-2001'); 
                                    $endDate = \Carbon\Carbon::parse($sale_date); 
                                    $prop_age = $startDate->diffInYears($endDate);
                                    
                                    if(isset($fy_index['2001-02'])){
                                        $indexed_cost_of_purchase = $index / $fy_index['2001-02'] * $fair_market_value;
                                    }
                                    $indexed_cost_of_improvement = array();
                                    $total_cost_of_improvement = 0;
                                    // dd($financial_year);
                                    if($financial_year[0] != null && $cost_of_improvement[0] != null){ 
                                        foreach ($financial_year as $key => $fy) {
                                            // dd($cost_of_improvement[$key]);
                                            if($fy != null){
                                                $indexed_cost_of_improvement[] = round($index / $fy_index[$fy] * $cost_of_improvement[$key], 0);
                                            }
                                            $total_cost_of_improvement += $cost_of_improvement[$key];
                                        }
                                    }
                                    // $total_cost_of_improvement += $fair_market_value;
                                    $total_indexed_cost = $indexed_cost_of_purchase;
                                    foreach ($indexed_cost_of_improvement as $key => $value) {
                                        $total_indexed_cost += $value;
                                    }
                                    
                                    // $total_indexed_cost = $indexed_cost_of_purchase + $indexed_cost_of_improvement1 + $indexed_cost_of_improvement2 + $indexed_cost_of_improvement3;
                                    $net_sale_consideration = $sales_price - $sales_expenses;
                                    $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;
                                    $capital_gain_type = '';
                                    // dd($net_sale_consideration);
                                    if($asset_type == 'movable'){
                                        // $taxable_capital_gain = $net_sale_consideration - $fair_market_value + $total_cost_of_improvement;

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
                                        // $taxable_capital_gain = $net_sale_consideration - $fair_market_value + $total_cost_of_improvement;
                                        
                                        $applicable_tax_rate = $income_tax_slab;
                                    }
                                    // dd($total_indexed_cost);
                                    // if(empty($income_tax_slab)){
                                    //     if($taxable_capital_gain <= 0 || $taxable_capital_gain == '0'){
                                    //         $applicable_tax_rate = 0;
                                    //     }
                                    // }
                                    
                                    $applicable_tax = round($taxable_capital_gain * $applicable_tax_rate / 100, 0);
                                    // dd($taxable_capital_gain);
                                    if($capital_gain_type == 'Short Term'){
                                        $indexing = 0;  
                                    }else{
                                        $indexing = 1; 
                                    }                
                                @endphp
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered">
                                        <tbody>
                                            @if(!empty($asset_name))
                                            <tr>
                                                <td>
                                                    <strong>  Asset Details</strong>
                                                </td>
                                                <td>
                                                    {{$asset_name}}
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                    <strong> Fair Market Value (01.04.2001)</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($fair_market_value)}}
                                                </td>
                                            </tr>
                                            @if($indexing == 1)
                                            <tr>
                                                <td>
                                                    <strong>  Cost Inflation Index For FY 2001-02</strong>
                                                </td>
                                                @php 
                                                    $fi_yearrr = '2001-02';
                                                    $cost = $fy_index[$fi_yearrr];
                                                
                                                @endphp
                                                <td>
                                                    {{  $cost }} 
                                                </td>
                                            </tr>
                                            @endif
                                            @if($financial_year[0] != null && $cost_of_improvement[0] != null)
                                                @foreach ($financial_year as $key => $fy)
                                                    @if($fy != null)
                                                        <tr>
                                                            <td>
                                                                <strong> Cost of Improvement in FY {{ $fy }}</strong>
                                                            </td>
                                                            <td>
                                                                ₹ {{custome_money_format($cost_of_improvement[$key])}}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td>
                                                    <strong>  Sale Date</strong>
                                                </td>
                                                <td>
                                                    {{  $sale_date }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong> Sales Price</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($sales_price)}}
                                                </td>
                                            </tr>
                                            @if($indexing == 1)
                                            @php if($purchased_before == 1){
                                                $fi_yearrr = '2001-02';
                                            }else{
                                                $fi_yearrr = $fi_purchase;
                                            }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{$assumed}} Cost Inflation Index For FY {{$fi_year}}</strong>
                                                </td>
                                                <td>
                                                    {{$index}} 
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                    <strong> Sales Expenses</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($sales_expenses)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>  Net Sales Consideration</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($net_sale_consideration)}}
                                                </td>
                                            </tr>
                                            {{-- @if($indexing == 1) --}}
                                            <tr>
                                                <td>
                                                    <strong>  @if($indexing == 1) Indexed @endif Cost of Acquisition</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($total_indexed_cost)}}
                                                </td>
                                            </tr>
                                            {{-- @endif --}}
                                            <tr>
                                                <td>
                                                    <strong>  Taxable Capital Gain</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($taxable_capital_gain)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>   Applicable Tax</strong>
                                                </td>
                                                <td>
                                                    {{$capital_gain_type}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong> Applicable Tax Rate</strong>
                                                </td>
                                                <td>
                                                    {{$applicable_tax_rate}}  %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>   Applicable Tax</strong>
                                                </td>
                                                <td>
                                                    @if($applicable_tax <= 0)
                                                        NA
                                                    @else
                                                        ₹ {{custome_money_format($applicable_tax)}}
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                        
                                    </table>
                                </div>
                                

                                
                            @else
                                @php
                                    $month = \Carbon\Carbon::parse($sale_date)->format('m');
                                    $year_long = \Carbon\Carbon::parse($sale_date)->format('Y');
                                    $year_short = \Carbon\Carbon::parse($sale_date)->format('y');
                                    $year_prev = $year_long - 1;
                                    $fi_year = ($month >= 4) ? $year_long.'-'.($year_short + 1) : $year_prev.'-'.$year_short;
                                    
                                    $month = \Carbon\Carbon::parse($purchase_date)->format('m');
                                    $year_long = \Carbon\Carbon::parse($purchase_date)->format('Y');
                                    $year_short = \Carbon\Carbon::parse($purchase_date)->format('y');
                                    $year_prev = $year_long - 1;
                                    $fi_purchase = ($month >= 4) ? $year_long.'-'.sprintf("%02d", $year_short + 01) : $year_prev.'-'.$year_short;
                                    // $year_short = sprintf("%02d", $year_short);
                                    // dd($fi_purchase);
                                    $index = 1;
                                    if(isset($fy_index[$fi_year])){
                                        $index = $fy_index[$fi_year];
                                    }
                                    $assumed = '';
                                    if(isset($cost_inflation_index) && !empty($cost_inflation_index)){
                                        // $index1 = 1;
                                        // if(isset($fy_index[$fi_purchase])){
                                        //     $index1 = $fy_index[$fi_purchase];
                                        // }
                                        // $index = $cost_inflation_index / $index1 * 100;
                                        $index = $cost_inflation_index;
                                        $assumed = 'Assumed';
                                    }

                                    $startDate = \Carbon\Carbon::parse($purchase_date); 
                                    $endDate = \Carbon\Carbon::parse($sale_date); 
                                    $prop_age = $startDate->diffInYears($endDate);
                                    // dd($startDate);
                                    
                                    $indexed_cost_of_purchase = 0;
                                    if(isset($fy_index[$fi_purchase])){
                                        $indexed_cost_of_purchase = $index / $fy_index[$fi_purchase] * $purchase_price;
                                    }
                                    $indexed_cost_of_improvement = array();
                                    $total_cost_of_improvement = 0;

                                    // dd($indexed_cost_of_purchase);
                                    if($financial_year[0] != null && $cost_of_improvement[0] != null){ 
                                        foreach ($financial_year as $key => $fy) {
                                            // dd($fy);
                                            if($fy != null){
                                                $indexed_cost_of_improvement[] = round($index / $fy_index[$fy] * $cost_of_improvement[$key], 0);
                                            }
                                            $total_cost_of_improvement += $cost_of_improvement[$key];

                                        }
                                    }
                                    
                                    // dd($indexed_cost_of_purchase);
                                    $total_indexed_cost = $indexed_cost_of_purchase;
                                    foreach ($indexed_cost_of_improvement as $key => $value) {
                                        $total_indexed_cost += $value;
                                    }
                                    
                                    $net_sale_consideration = $sales_price - $sales_expenses;
                                    $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;
                                    $capital_gain_type = '';
                                    if($asset_type == 'movable'){
                                        // $taxable_capital_gain = $net_sale_consideration - $fair_market_value + $total_cost_of_improvement;

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
                                    // dd($taxable_capital_gain);
                                    $applicable_tax_rate = 0;
                                    if($capital_gain_type == 'Long Term'){
                                        $applicable_tax_rate = $long_term_tax;
                                    }else{
                                        $total_indexed_cost = $purchase_price + $total_cost_of_improvement;
                                        $taxable_capital_gain = $net_sale_consideration - $total_indexed_cost;

                                        $applicable_tax_rate = $income_tax_slab;
                                    }
                                    // if(empty($income_tax_slab)){
                                    //     if($taxable_capital_gain <= 0 || $taxable_capital_gain == '0'){
                                    //         $applicable_tax_rate = 0;
                                    //     }
                                    // }

                                    $applicable_tax = round($taxable_capital_gain * $applicable_tax_rate / 100, 0);
                                    // dd($long_term_tax);
                                    if($capital_gain_type == 'Short Term'){
                                        $indexing = 0;  
                                    }else{
                                        $indexing = 1; 
                                    }
                                @endphp
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered">
                                        <tbody>
                                            @if(!empty($asset_name))
                                            <tr>
                                                <td>
                                                    <strong>  Asset Details</strong>
                                                </td>
                                                <td>
                                                    {{$asset_name}}
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                    <strong>  Purchase Date</strong>
                                                </td>
                                                <td>
                                                    {{$purchase_date}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>  Cost Of Purchase</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($purchase_price)}}
                                                </td>
                                            </tr>
                                            {{-- @dd($fy_index); --}}
                                            @if($indexing == 1)
                                            <tr>
                                                <td>
                                                    <strong>  Cost Inflation Index For FY {{$fi_purchase}}</strong>
                                                </td>
                                                @php if($purchased_before == 1){
                                                    $fi_yearrr = '2001-02';
                                                    $cost = $index;
                                                }else{
                                                    $fi_yearrr = $fi_purchase;
                                                    $cost = $fy_index[$fi_yearrr];
                                                }
                                                @endphp
                                                <td>
                                                    {{  $cost }} 
                                                    {{-- @if(isset($fy_index[$fi_purchase]))
                                                    {{  $fy_index[$fi_purchase] }} 
                                                    @endif --}}
                                                </td>
                                            </tr>
                                            @endif
                                            
                                            @if($financial_year[0] != null && $cost_of_improvement[0] != null)
                                                @foreach ($financial_year as $key => $fy)
                                                    @if($fy != null)
                                                        <tr>
                                                            <td>
                                                                <strong> Cost of Improvement in FY {{ $fy }}</strong>
                                                            </td>
                                                            <td>
                                                                ₹ {{custome_money_format($cost_of_improvement[$key])}}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td>
                                                    <strong>  Sale Date</strong>
                                                </td>
                                                <td>
                                                    {{  $sale_date }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong> Sales Price</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($sales_price)}}
                                                </td>
                                            </tr>
                                            @if($indexing == 1)
                                            <tr>
                                                <td>
                                                    <strong>{{$assumed}} Cost Inflation Index For FY {{$fi_year}}</strong>
                                                    
                                                </td>
                                                <td>
                                                    {{$index}} 
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>
                                                    <strong> Sales Expenses</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($sales_expenses)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>  Net Sales Consideration</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($net_sale_consideration)}}
                                                </td>
                                            </tr>
                                            {{-- @if($indexing == 1) --}}
                                            <tr>
                                                <td>
                                                    <strong>    @if($indexing == 1) Indexed @endif  Cost of Acquisition</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($total_indexed_cost)}}
                                                </td>
                                            </tr>
                                            {{-- @endif --}}
                                            <tr>
                                                <td>
                                                    <strong>  Taxable Capital Gain</strong>
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($taxable_capital_gain)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>   Applicable Tax</strong>
                                                </td>
                                                <td>
                                                    {{$capital_gain_type}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong> Applicable Tax Rate</strong>
                                                </td>
                                                <td>
                                                    {{$applicable_tax_rate}}  %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>   Applicable Tax</strong>
                                                </td>
                                                <td>
                                                    @if($applicable_tax <= 0)
                                                        NA
                                                    @else
                                                        ₹ {{custome_money_format($applicable_tax)}}
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>

                                
                            @endif

                            @if($indexing == 1 && $financial_year[0] != null)
                            <p class="text-left">Cost Inflation Index for
                                @if($financial_year[0] != null && $cost_of_improvement[0] != null)
                                    @foreach ($financial_year as $key => $fy)
                                        @if($fy != null)
                                            FY {{$fy}} is {{$fy_index[$fy]}},
                                        @endif
                                    @endforeach
                                @endif
                            </p>
                            @endif
                            
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        <!--<p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>-->
                        <div class="description-text">
                        @php
                            $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP_Required_For_Target_Future_Value')->first();
                        @endphp
                        @if(!empty($note_data1))
                            {!!$note_data1->description!!}
                        @endif
                        Report Date : {{date('d/m/Y')}}
                        </div>

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.capital_gains_tax_calculator_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @endif
                        @if($permission['is_save'])
                            @if($edit_id)
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                            @else
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                            @endif
                        @else
                            <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                        @endif
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.capital_gains_tax_calculator_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.capital_gains_tax_calculator_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
