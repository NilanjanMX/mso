<style>
    
</style>

    @if(isset($suggest) && session()->has('suggested_scheme_list'))
        <div class="page-break"></div>
        @include('frontend.calculators.common.header')
        <main style="width: 806px;">
            @php

                $suggested_performance = session()->get('suggested_performance');
                $suggested_scheme_list = session()->get('suggested_scheme_list');
                $calculator_duration = session()->get('calculator_duration');
                
                $scheme_cnt = 1;
                $commodity_cnt = 0;
                $debt_cnt = 0;
                $equity_cnt = 0;
                $hybrid_cnt = 0;
                $other_cnt = 0;
                foreach($suggested_scheme_list as $suggested_scheme){
                    $suggested_scheme = (array) $suggested_scheme;
                    if ($suggested_scheme['ASSET_TYPE']=='Commodity'){
                        $commodity_cnt++;
                    }
                    if ($suggested_scheme['ASSET_TYPE']=='Debt'){
                        $debt_cnt++;
                    }
                    if ($suggested_scheme['ASSET_TYPE']=='Equity'){
                        $equity_cnt++;
                    }
                    if ($suggested_scheme['ASSET_TYPE']=='Hybrid'){
                        $hybrid_cnt++;
                    }
                    if ($suggested_scheme['ASSET_TYPE']=='Other'){
                        $other_cnt++;
                    }
                }
                /*if (Auth::check()){
                        $user = Auth::user();
                        $name = ($user['name']!='')?$user['name']:'Advisor Name';
                        $company_name = ($user['company_name']!='')?$user['company_name']:'Advisor Company Name';
                        $phone_no = ($user['phone_no']!='')?$user['phone_no']:'+91 988XXXXX27';
                        $company_logo = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                        if (session()->has('membership_type') && session()->get('membership_type')==1){
                            $watermark = 0;
                        }else{
                            $watermark = 1;
                        }

                    }*/
            @endphp
            

            <div class="roundBorderHolder margintop30px">
            <table class="suggested-sceme">
                <tbody>
                <tr>
                    @if($suggested_performance=='with_performance')
                    <td class="nobordernopadding" colspan="{{count($calculator_duration)+3}}">
                    @else
                    <td class="nobordernopadding" colspan="3">
                    @endif
                        <div class="tableh1heading" style="background:{{$city_color}} !important;">Suggested Schemes For Investment</div>
                    </td>
                </tr>

                @if($debt_cnt>0)
                    @php
                        $scheme_cnt=1;
                    @endphp
                    @if($suggested_performance=='with_performance')
                        <tr>
                            <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">DEBT</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                            <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                            <th rowspan="2" style="vertical-align: middle;">Category</th>
                            <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center">Past Performance</th>
                        </tr>
                        <tr>
                            @if(in_array("AUM", $calculator_duration))
                                <th style="width: 50px;">AUM <br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</th>
                            @endif
                            @if(in_array("PERCHANGE", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Day</th>
                            @endif
                            @if(in_array("1WEEKRET", $calculator_duration))
                                <th style="width: 50px;">7&nbsp;Day</th>
                            @endif
                            @if(in_array("1MONTHRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Mth</th>
                            @endif
                            @if(in_array("3MONTHRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Mth</th>
                            @endif
                            @if(in_array("6MONTHRET", $calculator_duration))
                                <th style="width: 50px;">6&nbsp;Mth</th>
                            @endif
                            @if(in_array("1YEARRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Yr</th>
                            @endif
                            @if(in_array("3YEARRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Yr</th>
                            @endif
                            @if(in_array("5YEARRET", $calculator_duration))
                                <th style="width: 50px;">5&nbsp;Yr</th>
                            @endif
                            @if(in_array("10YEARRET", $calculator_duration))
                                <th style="width: 50px;">10&nbsp;Yr</th>
                            @endif
                            @if(in_array("INCRET", $calculator_duration))
                                <th style="width: 50px;">Since<br>Inception</th>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                                <th style="width: 50px;">Type</th>
                            @endif
                            @if(in_array("AMOUNT", $calculator_duration))
                                <th style="width: 50px;">Amount</th>
                            @endif
                        </tr>
                    @else
                        <tr>
                            <th colspan="3" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">DEBT</th>
                        </tr>
                        <tr>
                            <th>S.&nbsp;No.</th>
                            <th>Scheme Name</th>
                            <th>Category</th>
                        </tr>
                    @endif
                    @foreach($suggested_scheme_list as $suggested_scheme)
                        @php
                            $suggested_scheme = (array) $suggested_scheme;
                        @endphp
                        @if($suggested_scheme['ASSET_TYPE']=='Debt')
                            <tr>
                                <td>{{$scheme_cnt}}</td>
                                <td style="text-align: left; padding-left: 5px">{{(strlen($suggested_scheme['S_NAME']) > 70)?substr($suggested_scheme['S_NAME'], 0, 70).'..':$suggested_scheme['S_NAME']}}</td>
                                <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 103)?substr($suggested_scheme['CATEGORY'], 0, 103).'..':$suggested_scheme['CATEGORY']}}</td>
                                @if($suggested_performance=='with_performance')
                                    @if(in_array("AUM", $calculator_duration))
                                    <td>{{$suggested_scheme['AUM']?number_format((int)$suggested_scheme['AUM']/100, 0, '.', ''):'N/A '}}</td>
                                    @endif
                                    @if(in_array("PERCHANGE", $calculator_duration))
                                <td>{{$suggested_scheme['PERCHANGE']?number_format((float)$suggested_scheme['PERCHANGE'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("1WEEKRET", $calculator_duration))
                                <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("1MONTHRET", $calculator_duration))
                                <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("3MONTHRET", $calculator_duration))
                                <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("6MONTHRET", $calculator_duration))
                                <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("1YEARRET", $calculator_duration))
                                <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("3YEARRET", $calculator_duration))
                                <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("5YEARRET", $calculator_duration))
                                <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("10YEARRET", $calculator_duration))
                                <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("INCRET", $calculator_duration))
                                <td>{{$suggested_scheme['INCRET']?number_format((float)$suggested_scheme['INCRET'], 2, '.', '').'%':'N/A '}}</td>
                                @endif
                                @if(in_array("TYPE", $calculator_duration))
                                <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                                @endif
                                @if(in_array("AMOUNT", $calculator_duration))
                                <td>{{isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:""}}</td>
                                @endif
                                @endif
                            </tr>
                            @php
                                $scheme_cnt++;
                            @endphp
                        @endif
                    @endforeach
                @endif
                @if($hybrid_cnt>0)
                    @php
                        $scheme_cnt=1;
                    @endphp
                    @if($suggested_performance=='with_performance')
                        <tr>
                            <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">HYBRID</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                            <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                            <th rowspan="2" style="vertical-align: middle;">Category</th>
                            <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center">Past Performance</th>
                        </tr>
                        <tr>
                            @if(in_array("AUM", $calculator_duration))
                                <th style="width: 50px;">AUM <br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</th>
                            @endif
                            @if(in_array("PERCHANGE", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Day</th>
                            @endif
                            @if(in_array("1WEEKRET", $calculator_duration))
                                <th style="width: 50px;">7&nbsp;Day</th>
                            @endif
                            @if(in_array("1MONTHRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Mth</th>
                            @endif
                            @if(in_array("3MONTHRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Mth</th>
                            @endif
                            @if(in_array("6MONTHRET", $calculator_duration))
                                <th style="width: 50px;">6&nbsp;Mth</th>
                            @endif
                            @if(in_array("1YEARRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Yr</th>
                            @endif
                            @if(in_array("3YEARRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Yr</th>
                            @endif
                            @if(in_array("5YEARRET", $calculator_duration))
                                <th style="width: 50px;">5&nbsp;Yr</th>
                            @endif
                            @if(in_array("10YEARRET", $calculator_duration))
                                <th style="width: 50px;">10&nbsp;Yr</th>
                            @endif
                            @if(in_array("INCRET", $calculator_duration))
                                <th style="width: 50px;">Since<br>Inception</th>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                                <th style="width: 50px;">Type</th>
                            @endif
                            @if(in_array("AMOUNT", $calculator_duration))
                                <th style="width: 50px;">Amount</th>
                            @endif
                        </tr>
                    @else
                        <tr>
                            <th colspan="3" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">HYBRID</th>
                        </tr>
                        <tr>
                            <th>S.&nbsp;No.</th>
                            <th>Scheme Name</th>
                            <th>Category</th>
                        </tr>
                    @endif
                    @foreach($suggested_scheme_list as $suggested_scheme)
                        @php
                            $suggested_scheme = (array) $suggested_scheme;
                        @endphp
                        @if($suggested_scheme['ASSET_TYPE']=='Hybrid')
                            <tr>
                                <td>{{$scheme_cnt}}</td>
                                <td style="text-align: left; padding-left: 5px">{{(strlen($suggested_scheme['S_NAME']) > 70)?substr($suggested_scheme['S_NAME'], 0, 70).'..':$suggested_scheme['S_NAME']}}</td>
                                <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 103)?substr($suggested_scheme['CATEGORY'], 0, 103).'..':$suggested_scheme['CATEGORY']}}</td>
                                @if($suggested_performance=='with_performance')
                                    @if(in_array("AUM", $calculator_duration))
                                    <td>{{$suggested_scheme['AUM']?number_format((float)$suggested_scheme['AUM']/100, 0, '.', ''):'N/A '}}</td>
                                    @endif
                                    @if(in_array("PERCHANGE", $calculator_duration))
                                    <td>{{$suggested_scheme['PERCHANGE']?number_format((float)$suggested_scheme['PERCHANGE'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1WEEKRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("6MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("5YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("10YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("INCRET", $calculator_duration))
                                    <td>{{$suggested_scheme['INCRET']?number_format((float)$suggested_scheme['INCRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("TYPE", $calculator_duration))
                                    <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                    @if(in_array("AMOUNT", $calculator_duration))
                                    <td>{{isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                @endif
                            </tr>
                            @php
                                $scheme_cnt++;
                            @endphp
                        @endif
                    @endforeach
                @endif
                @if($equity_cnt>0)
                    @php
                        $scheme_cnt=1;
                    @endphp
                    @if($suggested_performance=='with_performance')
                        <tr>
                            <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">EQUITY</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                            <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                            <th rowspan="2" style="vertical-align: middle;">Category</th>
                            <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center">Past Performance</th>
                        </tr>
                        <tr>
                            @if(in_array("AUM", $calculator_duration))
                                <th style="width: 50px;">AUM <br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</th>
                            @endif
                            @if(in_array("PERCHANGE", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Day</th>
                            @endif
                            @if(in_array("1WEEKRET", $calculator_duration))
                                <th style="width: 50px;">7&nbsp;Day</th>
                            @endif
                            @if(in_array("1MONTHRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Mth</th>
                            @endif
                            @if(in_array("3MONTHRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Mth</th>
                            @endif
                            @if(in_array("6MONTHRET", $calculator_duration))
                                <th style="width: 50px;">6&nbsp;Mth</th>
                            @endif
                            @if(in_array("1YEARRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Yr</th>
                            @endif
                            @if(in_array("3YEARRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Yr</th>
                            @endif
                            @if(in_array("5YEARRET", $calculator_duration))
                                <th style="width: 50px;">5&nbsp;Yr</th>
                            @endif
                            @if(in_array("10YEARRET", $calculator_duration))
                                <th style="width: 50px;">10&nbsp;Yr</th>
                            @endif
                            @if(in_array("INCRET", $calculator_duration))
                                <th style="width: 50px;">Since<br>Inception</th>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                                <th style="width: 50px;">Type</th>
                            @endif
                            @if(in_array("AMOUNT", $calculator_duration))
                                <th style="width: 50px;">Amount</th>
                            @endif
                        </tr>
                    @else
                        <tr>
                            <th colspan="3" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">EQUITY</th>
                        </tr>
                        <tr>
                            <th>S.&nbsp;No.</th>
                            <th>Scheme Name</th>
                            <th>Category</th>
                        </tr>
                    @endif
                    @foreach($suggested_scheme_list as $suggested_scheme)
                        @php
                            $suggested_scheme = (array) $suggested_scheme;
                        @endphp
                        @if($suggested_scheme['ASSET_TYPE']=='Equity')
                            <tr>
                                <td>{{$scheme_cnt}}</td>
                                <td style="text-align: left; padding-left: 5px">{{(strlen($suggested_scheme['S_NAME']) > 70)?substr($suggested_scheme['S_NAME'], 0, 70).'..':$suggested_scheme['S_NAME']}}</td>
                                <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 103)?substr($suggested_scheme['CATEGORY'], 0, 103).'..':$suggested_scheme['CATEGORY']}}</td>
                                @if($suggested_performance=='with_performance')
                                    @if(in_array("AUM", $calculator_duration))
                                    <td>{{$suggested_scheme['AUM']?number_format((float)$suggested_scheme['AUM']/100, 0, '.', ''):'N/A '}}</td>
                                    @endif
                                    @if(in_array("PERCHANGE", $calculator_duration))
                                    <td>{{$suggested_scheme['PERCHANGE']?number_format((float)$suggested_scheme['PERCHANGE'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1WEEKRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("6MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("5YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("10YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("INCRET", $calculator_duration))
                                    <td>{{$suggested_scheme['INCRET']?number_format((float)$suggested_scheme['INCRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("TYPE", $calculator_duration))
                                    <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                    @if(in_array("AMOUNT", $calculator_duration))
                                    <td>{{isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                @endif
                            </tr>
                            @php
                                $scheme_cnt++;
                            @endphp
                        @endif
                    @endforeach
                @endif
                @if($commodity_cnt>0)
                    @php
                        $scheme_cnt=1;
                    @endphp
                    @if($suggested_performance=='with_performance')
                        <tr>
                            <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">COMMODITY</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                            <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                            <th rowspan="2" style="vertical-align: middle;">Category</th>
                            <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center">Past Performance</th>
                        </tr>
                        <tr>
                            @if(in_array("AUM", $calculator_duration))
                                <th style="width: 50px;">AUM <br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</th>
                            @endif
                            @if(in_array("PERCHANGE", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Day</th>
                            @endif
                            @if(in_array("1WEEKRET", $calculator_duration))
                                <th style="width: 50px;">7&nbsp;Day</th>
                            @endif
                            @if(in_array("1MONTHRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Mth</th>
                            @endif
                            @if(in_array("3MONTHRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Mth</th>
                            @endif
                            @if(in_array("6MONTHRET", $calculator_duration))
                                <th style="width: 50px;">6&nbsp;Mth</th>
                            @endif
                            @if(in_array("1YEARRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Yr</th>
                            @endif
                            @if(in_array("3YEARRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Yr</th>
                            @endif
                            @if(in_array("5YEARRET", $calculator_duration))
                                <th style="width: 50px;">5&nbsp;Yr</th>
                            @endif
                            @if(in_array("10YEARRET", $calculator_duration))
                                <th style="width: 50px;">10&nbsp;Yr</th>
                            @endif
                            @if(in_array("INCRET", $calculator_duration))
                                <th style="width: 50px;">Since<br>Inception</th>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                                <th style="width: 50px;">Type</th>
                            @endif
                            @if(in_array("AMOUNT", $calculator_duration))
                                <th style="width: 50px;">Amount</th>
                            @endif
                        </tr>
                    @else
                        <tr>
                            <th colspan="3" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">COMMODITY</th>
                        </tr>
                        <tr>
                            <th>S.&nbsp;No.</th>
                            <th>Scheme Name</th>
                            <th>Category</th>
                        </tr>
                    @endif
                    @foreach($suggested_scheme_list as $suggested_scheme)
                        @php
                            $suggested_scheme = (array) $suggested_scheme;
                        @endphp
                        @if($suggested_scheme['ASSET_TYPE']=='Commodity')
                            <tr>
                                <td>{{$scheme_cnt}}</td>
                                <td style="text-align: left; padding-left: 5px">{{(strlen($suggested_scheme['S_NAME']) > 70)?substr($suggested_scheme['S_NAME'], 0, 70).'..':$suggested_scheme['S_NAME']}}</td>
                                <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 103)?substr($suggested_scheme['CATEGORY'], 0, 103).'..':$suggested_scheme['CATEGORY']}}</td>
                                @if($suggested_performance=='with_performance')
                                    @if(in_array("AUM", $calculator_duration))
                                    <td>{{$suggested_scheme['AUM']?number_format((float)$suggested_scheme['AUM']/100, 0, '.', ''):'N/A '}}</td>
                                    @endif
                                    @if(in_array("PERCHANGE", $calculator_duration))
                                    <td>{{$suggested_scheme['PERCHANGE']?number_format((float)$suggested_scheme['PERCHANGE'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1WEEKRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("6MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("5YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("10YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("INCRET", $calculator_duration))
                                    <td>{{$suggested_scheme['INCRET']?number_format((float)$suggested_scheme['INCRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("TYPE", $calculator_duration))
                                    <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                    @if(in_array("AMOUNT", $calculator_duration))
                                    <td>{{isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                @endif
                            </tr>
                            @php
                                $scheme_cnt++;
                            @endphp
                        @endif
                    @endforeach
                @endif
                @if($other_cnt>0)
                    @php
                        $scheme_cnt=1;
                    @endphp
                    @if($suggested_performance=='with_performance')
                        <tr>
                            <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">OTHERS</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                            <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                            <th rowspan="2" style="vertical-align: middle;">Category</th>
                            <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center">Past Performance</th>
                        </tr>
                        <tr>
                            @if(in_array("AUM", $calculator_duration))
                                <th style="width: 50px;">AUM <br>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</th>
                            @endif
                            @if(in_array("PERCHANGE", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Day</th>
                            @endif
                            @if(in_array("1WEEKRET", $calculator_duration))
                                <th style="width: 50px;">7&nbsp;Day</th>
                            @endif
                            @if(in_array("1MONTHRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Mth</th>
                            @endif
                            @if(in_array("3MONTHRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Mth</th>
                            @endif
                            @if(in_array("6MONTHRET", $calculator_duration))
                                <th style="width: 50px;">6&nbsp;Mth</th>
                            @endif
                            @if(in_array("1YEARRET", $calculator_duration))
                                <th style="width: 50px;">1&nbsp;Yr</th>
                            @endif
                            @if(in_array("3YEARRET", $calculator_duration))
                                <th style="width: 50px;">3&nbsp;Yr</th>
                            @endif
                            @if(in_array("5YEARRET", $calculator_duration))
                                <th style="width: 50px;">5&nbsp;Yr</th>
                            @endif
                            @if(in_array("10YEARRET", $calculator_duration))
                                <th style="width: 50px;">10&nbsp;Yr</th>
                            @endif
                            @if(in_array("INCRET", $calculator_duration))
                                <th style="width: 50px;">Since<br>Inception</th>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                                <th style="width: 50px;">Type</th>
                            @endif
                            @if(in_array("AMOUNT", $calculator_duration))
                                <th style="width: 50px;">Amount</th>
                            @endif
                        </tr>
                    @else
                        <tr>
                            <th colspan="3" style="text-align: center;background: {{$address_color_background}} !important; color: #fff !important;text-transform:uppercase">OTHERS</th>
                        </tr>
                        <tr>
                            <th>S.&nbsp;No.</th>
                            <th>Scheme Name</th>
                            <th>Category</th>
                        </tr>
                    @endif
                    @foreach($suggested_scheme_list as $suggested_scheme)
                        @php
                            $suggested_scheme = (array) $suggested_scheme;
                        @endphp
                        @if($suggested_scheme['ASSET_TYPE']=='Other')
                            <tr>
                                <td>{{$scheme_cnt}}</td>
                                <td style="text-align: left; padding-left: 5px">{{(strlen($suggested_scheme['S_NAME']) > 70)?substr($suggested_scheme['S_NAME'], 0, 70).'..':$suggested_scheme['S_NAME']}}</td>
                                <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 103)?substr($suggested_scheme['CATEGORY'], 0, 103).'..':$suggested_scheme['CATEGORY']}}</td>
                                @if($suggested_performance=='with_performance')
                                    @if(in_array("AUM", $calculator_duration))
                                    <td>{{$suggested_scheme['AUM']?number_format((float)$suggested_scheme['AUM']/100, 0, '.', ''):'N/A '}}</td>
                                    @endif
                                    @if(in_array("PERCHANGE", $calculator_duration))
                                    <td>{{$suggested_scheme['PERCHANGE']?number_format((float)$suggested_scheme['PERCHANGE'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1WEEKRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("6MONTHRET", $calculator_duration))
                                    <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("1YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("3YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("5YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("10YEARRET", $calculator_duration))
                                    <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("INCRET", $calculator_duration))
                                    <td>{{$suggested_scheme['INCRET']?number_format((float)$suggested_scheme['INCRET'], 2, '.', '').'%':'N/A '}}</td>
                                    @endif
                                    @if(in_array("TYPE", $calculator_duration))
                                    <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                    @if(in_array("AMOUNT", $calculator_duration))
                                    <td>{{isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:""}}</td>
                                    @endif
                                @endif
                            </tr>
                            @php
                                $scheme_cnt++;
                            @endphp
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
                </div>
            @php
                $note_data1 = \App\Models\Calculator_note::where('calculator','All')->where('category','suggested_schemes_for_investment')->first();
                if(!empty($note_data1)){
            @endphp
                {!!$note_data1->description!!}
            @php } @endphp
            Report Date : {{date('d/m/Y')}}
        </main>
        
    @endif
    @include('frontend.calculators.common.watermark')
    @include('frontend.calculators.common.footer')

<SALESPRESENTER/>
<SALESPRESENTER_AFTER/>