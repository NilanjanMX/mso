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
</style>
@if(isset($suggest))
    <div class="page-break"></div>
    @php
        //$suggested_performance = session()->get('suggested_performance');
        //$suggested_scheme_list = session()->get('suggested_scheme_list');
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
    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle">
                    <img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>


    <table class="suggested-sceme">
        <tbody>
        <tr>
            @if($suggested_performance=='with_performance')
            <td style="padding: 0px;border: 0px;" colspan="11">
            @else
            <td style="padding: 0px;border: 0px;" colspan="3">
            @endif
                <h1 style="background-color: #131f45;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Suggested Schemes For Investment</h1>
            </td>
        </tr>

        @if($debt_cnt>0)
            @php
                $scheme_cnt=1;
            @endphp
            @if($suggested_performance=='with_performance')
                <tr>
                    <th colspan="11" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">DEBT</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="8" style="vertical-align: middle; text-align: center">Past Performance</th>
                </tr>
                <tr>
                    <th style="width: 50px;">7&nbsp;Day</th>
                    <th style="width: 50px;">1&nbsp;Mth</th>
                    <th style="width: 50px;">3&nbsp;Mth</th>
                    <th style="width: 50px;">6&nbsp;Mth</th>
                    <th style="width: 50px;">1&nbsp;Yr</th>
                    <th style="width: 50px;">3&nbsp;Yr</th>
                    <th style="width: 50px;">5&nbsp;Yr</th>
                    <th style="width: 50px;">10&nbsp;Yr</th>
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
                        <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 15)?substr($suggested_scheme['CATEGORY'], 0, 15).'..':$suggested_scheme['CATEGORY']}}</td>
                        @if($suggested_performance=='with_performance')
                            <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
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
                    <th colspan="11" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">HYBRID</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="8" style="vertical-align: middle; text-align: center">Past Performance</th>
                </tr>
                <tr>
                    <th style="width: 50px;">7&nbsp;Day</th>
                    <th style="width: 50px;">1&nbsp;Mth</th>
                    <th style="width: 50px;">3&nbsp;Mth</th>
                    <th style="width: 50px;">6&nbsp;Mth</th>
                    <th style="width: 50px;">1&nbsp;Yr</th>
                    <th style="width: 50px;">3&nbsp;Yr</th>
                    <th style="width: 50px;">5&nbsp;Yr</th>
                    <th style="width: 50px;">10&nbsp;Yr</th>
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
                        <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 15)?substr($suggested_scheme['CATEGORY'], 0, 15).'..':$suggested_scheme['CATEGORY']}}</td>
                        @if($suggested_performance=='with_performance')
                            <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
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
                    <th colspan="11" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">EQUITY</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="8" style="vertical-align: middle; text-align: center">Past Performance</th>
                </tr>
                <tr>
                    <th style="width: 50px;">7&nbsp;Day</th>
                    <th style="width: 50px;">1&nbsp;Mth</th>
                    <th style="width: 50px;">3&nbsp;Mth</th>
                    <th style="width: 50px;">6&nbsp;Mth</th>
                    <th style="width: 50px;">1&nbsp;Yr</th>
                    <th style="width: 50px;">3&nbsp;Yr</th>
                    <th style="width: 50px;">5&nbsp;Yr</th>
                    <th style="width: 50px;">10&nbsp;Yr</th>
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
                        <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 15)?substr($suggested_scheme['CATEGORY'], 0, 15).'..':$suggested_scheme['CATEGORY']}}</td>
                        @if($suggested_performance=='with_performance')
                            <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
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
                    <th colspan="11" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">COMMODITY</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="8" style="vertical-align: middle; text-align: center">Past Performance</th>
                </tr>
                <tr>
                    <th style="width: 50px;">7&nbsp;Day</th>
                    <th style="width: 50px;">1&nbsp;Mth</th>
                    <th style="width: 50px;">3&nbsp;Mth</th>
                    <th style="width: 50px;">6&nbsp;Mth</th>
                    <th style="width: 50px;">1&nbsp;Yr</th>
                    <th style="width: 50px;">3&nbsp;Yr</th>
                    <th style="width: 50px;">5&nbsp;Yr</th>
                    <th style="width: 50px;">10&nbsp;Yr</th>
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
                        <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 15)?substr($suggested_scheme['CATEGORY'], 0, 15).'..':$suggested_scheme['CATEGORY']}}</td>
                        @if($suggested_performance=='with_performance')
                            <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
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
                    <th colspan="11" style="text-align: center;background: #999; color: #fff !important;text-transform:uppercase">OTHERS</th>
                </tr>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">S.&nbsp;No.</th>
                    <th rowspan="2" style="vertical-align: middle;width: 200px;">Scheme Name</th>
                    <th rowspan="2" style="vertical-align: middle;">Category</th>
                    <th colspan="8" style="vertical-align: middle; text-align: center">Past Performance</th>
                </tr>
                <tr>
                    <th style="width: 50px;">7&nbsp;Day</th>
                    <th style="width: 50px;">1&nbsp;Mth</th>
                    <th style="width: 50px;">3&nbsp;Mth</th>
                    <th style="width: 50px;">6&nbsp;Mth</th>
                    <th style="width: 50px;">1&nbsp;Yr</th>
                    <th style="width: 50px;">3&nbsp;Yr</th>
                    <th style="width: 50px;">5&nbsp;Yr</th>
                    <th style="width: 50px;">10&nbsp;Yr</th>
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
                        <td style="text-align: left; padding-left: 2px">{{(strlen($suggested_scheme['CATEGORY']) > 15)?substr($suggested_scheme['CATEGORY'], 0, 15).'..':$suggested_scheme['CATEGORY']}}</td>
                        @if($suggested_performance=='with_performance')
                            <td>{{$suggested_scheme['1WEEKRET']?number_format((float)$suggested_scheme['1WEEKRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1MONTHRET']?number_format((float)$suggested_scheme['1MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3MONTHRET']?number_format((float)$suggested_scheme['3MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['6MONTHRET']?number_format((float)$suggested_scheme['6MONTHRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['1YEARRET']?number_format((float)$suggested_scheme['1YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['3YEARRET']?number_format((float)$suggested_scheme['3YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['5YEARRET']?number_format((float)$suggested_scheme['5YEARRET'], 2, '.', '').'%':'N/A '}}</td>
                            <td>{{$suggested_scheme['10YEARRET']?number_format((float)$suggested_scheme['10YEARRET'], 2, '.', '').'%':'N/A '}}</td>
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
    @php
        $note_data1 = \App\Models\Calculator_note::where('category','fund_performance')->where('calculator','All')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
    @php } @endphp
    @include('frontend.calculators.common.footer')
@endif

<SALESPRESENTER/>
<SALESPRESENTER_AFTER/>