<style>
    .suggested-sceme tr td{
        padding: 6px 2px;
        font-size: 13px;
    }
    .suggested-sceme tr th{
        padding: 6px 2px;
        font-size: 13px;
    }
    header img{
        height:110px;
    }
    th {
        border-left: 1px solid #b8b8b8;
    }
</style>

@if(isset($suggest) && session()->has('suggested_scheme_list'))
    <div class="page-break"></div>
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
    <header style="padding-top: 10px; width: 806px;">
        <table style="border:0 !important; width: 100%;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle">
                    <img style="display:inline-block; width:110px; height:auto; margin-top: 30px;" src="{{$company_logo}}" alt=""></td>
                </tr>
            </tbody>
        </table>
    </header>

    <div style="padding-top: 70px;">
    <h1 style="background-color: #131f45;color:#fff !important;font-size:20px;padding:10px;text-align:center; width:100%">Suggested Schemes For Investment</h1>     
    <table class="suggested-sceme">
        <tbody>
        <!-- <tr>
            @if($suggested_performance=='with_performance')
            <td style="padding: 0px;border: 0px;" colspan="{{count($calculator_duration)+3}}">
            @else
            <td style="padding: 0px;border: 0px;" colspan="3">
            @endif
                <h1 style="background-color: #131f45;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Suggested Schemes For Investment</h1>
            </td>
        </tr> -->

        @if($debt_cnt>0)
            @php
                $scheme_cnt=1;
            @endphp
            @if($suggested_performance=='with_performance')
                <tr>
                    <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">DEBT</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 150px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center; border-bottom: 1px solid #b8b8b8;">Past Performance </th>
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
                </tr>
            @else
                <tr>
                    <th colspan="3" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">DEBT</th>
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
                    <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">HYBRID</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 150px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center;  border-bottom: 1px solid #b8b8b8">Past Performance</th>
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
                    <th style="width: 50px; border-left: 1px solid #b8b8b8;">1&nbsp;Mth</th>
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
                </tr>
            @else
                <tr>
                    <th colspan="3" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">HYBRID</th>
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
                    <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">EQUITY</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 150px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center; border-bottom: 1px solid #b8b8b8;">Past Performance </th>
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
                    <th style="width: 50px; border-left: 1px solid #b8b8b8;">1&nbsp;Mth</th>
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
                </tr>
            @else
                <tr>
                    <th colspan="3" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">EQUITY</th>
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
                    <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">COMMODITY</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 150px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center; border-bottom: 1px solid #b8b8b8;">Past Performance</th>
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
                    <th style="width: 50px; border-left: 1px solid #b8b8b8;">1&nbsp;Mth</th>
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
                </tr>
            @else
                <tr>
                    <th colspan="3" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">COMMODITY</th>
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
                    <th colspan="{{count($calculator_duration)+3}}" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">OTHERS</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 150px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="{{count($calculator_duration)}}" style="vertical-align: middle; text-align: center; border-bottom: 1px solid #b8b8b8;">Past Performance</th>
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
                    <th style="width: 50px; border-left: 1px solid #b8b8b8;">1&nbsp;Mth</th>
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
                </tr>
            @else
                <tr>
                    <th colspan="3" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">OTHERS</th>
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
        $note_data1 = \App\Models\Calculator_note::where('category','fund_performance')->where('calculator','All')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
    @php } @endphp
    <p>
       Report Date : {{date('d/m/Y')}}
    </p>
    @include('frontend.calculators.common.footer')
@endif

<SALESPRESENTER/>
<SALESPRESENTER_AFTER/>