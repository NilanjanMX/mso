<style>
    .styleApril .roundBorderHolder .table tr.performanceSlab th {
        width: 6%;
    }
</style>

@if(isset($suggest) && session()->has('suggested_scheme_list'))
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

        $past_performance = 0;
        if(in_array("PERCHANGE", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("1WEEKRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("1MONTHRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("3MONTHRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("6MONTHRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("1YEARRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("3YEARRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("5YEARRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("10YEARRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
        if(in_array("INCRET", $calculator_duration)){
            $past_performance = $past_performance+1;
        }
    @endphp
    <h1 class="midheading">SUGGESTED SCHEMES FOR INVESTMENT</h1>
    <div class="roundBorderHolder">
    <table class="table table-bordered text-center less-padding suggestfont" style="background: #fff;">
        <tbody>
        @if($debt_cnt>0)
            @php
                $scheme_cnt=1;
            @endphp

            @if($suggested_performance=='with_performance')
            <tr>
                <th colspan="11" class="bluetableheader">DEBT</th>
            </tr>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                <th rowspan="2" style="vertical-align: middle;">Scheme Name</th>
                @if(in_array("AUM", $calculator_duration))
                    <th rowspan="2" style="vertical-align: middle;width: 10%;">AUM <br>(in <span style="color:#458ff6;">₹</span> Cr)</th>
                @endif
                <th colspan="{{$past_performance}}" style="vertical-align: middle; text-align: center;white-space: nowrap;">Past Performance (%)</th>

                @if(in_array("TYPE", $calculator_duration))
                    <th rowspan="2" style="vertical-align: middle;">Type <br>&nbsp;</th>
                    <th rowspan="2" style="vertical-align: middle;width:10%;">Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                @endif
            </tr>
            <tr class="performanceSlab">
                @if(in_array("PERCHANGE", $calculator_duration))
                    <th>1 Day</th>
                @endif
                @if(in_array("1WEEKRET", $calculator_duration))
                    <th>7 Day</th>
                @endif
                @if(in_array("1MONTHRET", $calculator_duration))
                    <th>1 Mth</th>
                @endif
                @if(in_array("3MONTHRET", $calculator_duration))
                    <th>3 Mth</th>
                @endif
                @if(in_array("6MONTHRET", $calculator_duration))
                    <th>6 Mth</th>
                @endif
                @if(in_array("1YEARRET", $calculator_duration))
                    <th>1 Yr</th>
                @endif
                @if(in_array("3YEARRET", $calculator_duration))
                    <th>3 Yr</th>
                @endif
                @if(in_array("5YEARRET", $calculator_duration))
                    <th>5 Yr</th>
                @endif
                @if(in_array("10YEARRET", $calculator_duration))
                    <th>10 Yr</th>
                @endif
                @if(in_array("INCRET", $calculator_duration))
                    <th>Since<br>Inception</th>
                @endif
            </tr>
            @else
                <tr>
                    <th colspan="5" class="bluetableheader">DEBT</th>
                </tr>
                <tr>
                    <th>S.&nbsp;No.</th>
                    <th>Scheme Name</th>
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Type</th>
                    @endif
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
            @endif

            @foreach($suggested_scheme_list as $suggested_scheme)
                @php
                    $suggested_scheme = (array) $suggested_scheme;
                @endphp
                @if($suggested_scheme['ASSET_TYPE']=='Debt')
                    <tr>
                        <td>{{$scheme_cnt}}</td>
                        <td class="text-left" style="padding-left: 10px;"><strong>{{$suggested_scheme['S_NAME']}}</strong> <br> <span style="font-size: 12px;"><i>{{$suggested_scheme['CATEGORY']}}</i></span></td>
                        @if($suggested_performance=='with_performance')

                            @if(in_array("AUM", $calculator_duration))
                            <td>{{custome_money_format($suggested_scheme['AUM']?number_format((int)($suggested_scheme['AUM']/100), 0, '.', ''):'N/A ')}}</td>
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
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
                            @endif

                        @else
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
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
                    <th colspan="11" class="bluetableheader">HYBRID</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Scheme Name</th>
                    @if(in_array("AUM", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;width: 10%;">AUM <br>(in <span style="color:#458ff6;">₹</span> Cr)</th>
                    @endif
                    <th colspan="{{$past_performance}}" style="vertical-align: middle; text-align: center;white-space: nowrap;">Past Performance (%)</th>

                    @if(in_array("TYPE", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;">Type <br>&nbsp;</th>
                        <th rowspan="2" style="vertical-align: middle;width:10%;">Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
                <tr class="performanceSlab">
                    @if(in_array("PERCHANGE", $calculator_duration))
                        <th>1 Day</th>
                    @endif
                    @if(in_array("1WEEKRET", $calculator_duration))
                        <th>7 Day</th>
                    @endif
                    @if(in_array("1MONTHRET", $calculator_duration))
                        <th>1 Mth</th>
                    @endif
                    @if(in_array("3MONTHRET", $calculator_duration))
                        <th>3 Mth</th>
                    @endif
                    @if(in_array("6MONTHRET", $calculator_duration))
                        <th>6 Mth</th>
                    @endif
                    @if(in_array("1YEARRET", $calculator_duration))
                        <th>1 Yr</th>
                    @endif
                    @if(in_array("3YEARRET", $calculator_duration))
                        <th>3 Yr</th>
                    @endif
                    @if(in_array("5YEARRET", $calculator_duration))
                        <th>5 Yr</th>
                    @endif
                    @if(in_array("10YEARRET", $calculator_duration))
                        <th>10 Yr</th>
                    @endif
                    @if(in_array("INCRET", $calculator_duration))
                        <th>Since<br>Inception</th>
                    @endif
                </tr>
            @else
                <tr>
                    <th colspan="5" class="bluetableheader">HYBRID</th>
                </tr>
                <tr>
                    <th>S.&nbsp;No.</th>
                    <th>Scheme Name</th>
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Type</th>
                    @endif
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
            @endif
            @foreach($suggested_scheme_list as $suggested_scheme)
                @php
                    $suggested_scheme = (array) $suggested_scheme;
                @endphp
                @if($suggested_scheme['ASSET_TYPE']=='Hybrid')
                   <tr>
                        <td>{{$scheme_cnt}}</td>
                        <td class="text-left" style="padding-left: 10px;"><strong>{{$suggested_scheme['S_NAME']}}</strong> <br> <span style="font-size: 12px;"><i>{{$suggested_scheme['CATEGORY']}}</i></span></td>
                        @if($suggested_performance=='with_performance')

                            @if(in_array("AUM", $calculator_duration))
                            <td>{{custome_money_format($suggested_scheme['AUM']?number_format((int)($suggested_scheme['AUM']/100), 0, '.', ''):'N/A ')}}</td>
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
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
                            @endif

                        @else
                            
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
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
                    <th colspan="11" class="bluetableheader">EQUITY</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Scheme Name</th>
                    @if(in_array("AUM", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;width: 10%;">AUM <br>(in <span style="color:#458ff6;">₹</span> Cr)</th>
                    @endif
                    <th colspan="{{$past_performance}}" style="vertical-align: middle; text-align: center;white-space: nowrap;">Past Performance (%)</th>

                    @if(in_array("TYPE", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;">Type <br>&nbsp;</th>
                        <th rowspan="2" style="vertical-align: middle;width:10%;">Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
                <tr class="performanceSlab">
                    @if(in_array("PERCHANGE", $calculator_duration))
                        <th>1 Day</th>
                    @endif
                    @if(in_array("1WEEKRET", $calculator_duration))
                        <th>7 Day</th>
                    @endif
                    @if(in_array("1MONTHRET", $calculator_duration))
                        <th>1 Mth</th>
                    @endif
                    @if(in_array("3MONTHRET", $calculator_duration))
                        <th>3 Mth</th>
                    @endif
                    @if(in_array("6MONTHRET", $calculator_duration))
                        <th>6 Mth</th>
                    @endif
                    @if(in_array("1YEARRET", $calculator_duration))
                        <th>1 Yr</th>
                    @endif
                    @if(in_array("3YEARRET", $calculator_duration))
                        <th>3 Yr</th>
                    @endif
                    @if(in_array("5YEARRET", $calculator_duration))
                        <th>5 Yr</th>
                    @endif
                    @if(in_array("10YEARRET", $calculator_duration))
                        <th>10 Yr</th>
                    @endif
                    @if(in_array("INCRET", $calculator_duration))
                        <th>Since<br>Inception</th>
                    @endif
            </tr>
            @else
                <tr>
                    <th colspan="5" class="bluetableheader">EQUITY</th>
                </tr>
                <tr>
                    <th>S.&nbsp;No.</th>
                    <th>Scheme Name</th>
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Type</th>
                    @endif
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
            @endif
            @foreach($suggested_scheme_list as $suggested_scheme)
                @php
                    $suggested_scheme = (array) $suggested_scheme;
                @endphp
                @if($suggested_scheme['ASSET_TYPE']=='Equity')
                    <tr>
                        <td>{{$scheme_cnt}}</td>
                        <td class="text-left" style="padding-left: 10px;"><strong>{{$suggested_scheme['S_NAME']}}</strong> <br> <span style="font-size: 12px;"><i>{{$suggested_scheme['CATEGORY']}}</i></span></td>
                        @if($suggested_performance=='with_performance')

                            @if(in_array("AUM", $calculator_duration))
                            <td>{{custome_money_format($suggested_scheme['AUM']?number_format((int)($suggested_scheme['AUM']/100), 0, '.', ''):'N/A ')}}</td>
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
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
                            @endif

                        @else
                            
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
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
                    <th colspan="11" class="bluetableheader">COMMODITY</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Scheme Name</th>
                    @if(in_array("AUM", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;width: 10%;">AUM <br>(in <span style="color:#458ff6;">₹</span> Cr)</th>
                    @endif
                    <th colspan="{{$past_performance}}" style="vertical-align: middle; text-align: center;white-space: nowrap;">Past Performance (%)</th>

                    @if(in_array("TYPE", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;">Type <br>&nbsp;</th>
                        <th rowspan="2" style="vertical-align: middle;width:10%;">Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
                <tr class="performanceSlab">
                    @if(in_array("PERCHANGE", $calculator_duration))
                        <th>1 Day</th>
                    @endif
                    @if(in_array("1WEEKRET", $calculator_duration))
                        <th>7 Day</th>
                    @endif
                    @if(in_array("1MONTHRET", $calculator_duration))
                        <th>1 Mth</th>
                    @endif
                    @if(in_array("3MONTHRET", $calculator_duration))
                        <th>3 Mth</th>
                    @endif
                    @if(in_array("6MONTHRET", $calculator_duration))
                        <th>6 Mth</th>
                    @endif
                    @if(in_array("1YEARRET", $calculator_duration))
                        <th>1 Yr</th>
                    @endif
                    @if(in_array("3YEARRET", $calculator_duration))
                        <th>3 Yr</th>
                    @endif
                    @if(in_array("5YEARRET", $calculator_duration))
                        <th>5 Yr</th>
                    @endif
                    @if(in_array("10YEARRET", $calculator_duration))
                        <th>10 Yr</th>
                    @endif
                    @if(in_array("INCRET", $calculator_duration))
                        <th>Since<br>Inception</th>
                    @endif
                </tr>
            @else
                <tr>
                    <th colspan="5" class="bluetableheader">COMMODITY</th>
                </tr>
                <tr>
                    <th>S.&nbsp;No.</th>
                    <th>Scheme Name</th>
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Type</th>
                    @endif
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
            @endif
            @foreach($suggested_scheme_list as $suggested_scheme)
                @php
                    $suggested_scheme = (array) $suggested_scheme;
                @endphp
                @if($suggested_scheme['ASSET_TYPE']=='Commodity')
                    <tr>
                        <td>{{$scheme_cnt}}</td>
                        <td class="text-left" style="padding-left: 10px;"><strong>{{$suggested_scheme['S_NAME']}}</strong> <br> <span style="font-size: 12px;"><i>{{$suggested_scheme['CATEGORY']}}</i></span></td>
                        @if($suggested_performance=='with_performance')

                            @if(in_array("AUM", $calculator_duration))
                            <td>{{custome_money_format($suggested_scheme['AUM']?number_format((int)($suggested_scheme['AUM']/100), 0, '.', ''):'N/A ')}}</td>
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
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
                            @endif

                        @else
                            
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
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
                    <th colspan="11" class="bluetableheader">OTHERS</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Scheme Name</th>
                    @if(in_array("AUM", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;width: 10%;">AUM <br>(in <span style="color:#458ff6;">₹</span> Cr)</th>
                    @endif
                    <th colspan="{{$past_performance}}" style="vertical-align: middle; text-align: center;white-space: nowrap;">Past Performance (%)</th>

                    @if(in_array("TYPE", $calculator_duration))
                        <th rowspan="2" style="vertical-align: middle;">Type <br>&nbsp;</th>
                        <th rowspan="2" style="vertical-align: middle;width:10%;">Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif                    
                </tr>
                <tr class="performanceSlab">
                    @if(in_array("PERCHANGE", $calculator_duration))
                        <th>1 Day</th>
                    @endif
                    @if(in_array("1WEEKRET", $calculator_duration))
                        <th>7 Day</th>
                    @endif
                    @if(in_array("1MONTHRET", $calculator_duration))
                        <th>1 Mth</th>
                    @endif
                    @if(in_array("3MONTHRET", $calculator_duration))
                        <th>3 Mth</th>
                    @endif
                    @if(in_array("6MONTHRET", $calculator_duration))
                        <th>6 Mth</th>
                    @endif
                    @if(in_array("1YEARRET", $calculator_duration))
                        <th>1 Yr</th>
                    @endif
                    @if(in_array("3YEARRET", $calculator_duration))
                        <th>3 Yr</th>
                    @endif
                    @if(in_array("5YEARRET", $calculator_duration))
                        <th>5 Yr</th>
                    @endif
                    @if(in_array("10YEARRET", $calculator_duration))
                        <th>10 Yr</th>
                    @endif
                    @if(in_array("INCRET", $calculator_duration))
                        <th>Since<br>Inception</th>
                    @endif
                </tr>
            @else
                <tr>
                    <th colspan="5" class="bluetableheader">OTHERS</th>
                </tr>
                <tr>
                    <th>S.&nbsp;No.</th>
                    <th>Scheme Name</th>
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Type</th>
                    @endif
                    @if(in_array("TYPE", $calculator_duration))
                        <th>Amount <br>(<span style="color:#458ff6;">₹</span>)</th>
                    @endif
                </tr>
            @endif
            @foreach($suggested_scheme_list as $suggested_scheme)
                @php
                    $suggested_scheme = (array) $suggested_scheme;
                @endphp
                @if($suggested_scheme['ASSET_TYPE']=='Other')
                    <tr>
                        <td>{{$scheme_cnt}}</td>
                        <td class="text-left" style="padding-left: 10px;"><strong>{{$suggested_scheme['S_NAME']}}</strong> <br> <span style="font-size: 12px;"><i>{{$suggested_scheme['CATEGORY']}}</i></span></td>
                        @if($suggested_performance=='with_performance')

                            @if(in_array("AUM", $calculator_duration))
                            <td>{{custome_money_format($suggested_scheme['AUM']?number_format((int)($suggested_scheme['AUM']/100), 0, '.', ''):'N/A ')}}</td>
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
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
                            @endif


                        @else
                            
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{isset($scheme_type[$suggested_scheme['Schemecode']])?$scheme_type[$suggested_scheme['Schemecode']]:""}}</td>
                            @endif
                            @if(in_array("TYPE", $calculator_duration))
                            <td>{{custome_money_format(isset($scheme_amount[$suggested_scheme['Schemecode']])?$scheme_amount[$suggested_scheme['Schemecode']]:"")}}</td>
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
    <div style="text-align:left;">
        @php
            $note_data1 = \App\Models\Calculator_note::where('calculator','All')->where('category','suggested_schemes_for_investment')->first();
            if(!empty($note_data1)){
        @endphp
            {!!$note_data1->description!!}
        @php } @endphp
        Report Date : {{date('d/m/Y')}}
    </div>
@endif