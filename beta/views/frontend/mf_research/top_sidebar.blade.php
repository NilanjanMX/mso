@php
    $mf_research = \App\Models\Mf_research::where('status',1)->orderBy('position','ASC')->get();
    $disclaimers = \App\Models\Mf_research_disclaimers::where('is_active',1)->first();
    $pdf = "";
    if($disclaimers){
        $pdf = asset('uploads/mf_research_disclaimer/')."/".$disclaimers->pdf;
    }
@endphp
<div class="col-md-12">
    <!--<div class="top-tab">-->
    <div class="category-box categoryList">
        <ul class="cate-list">
            @foreach($mf_research as $key => $value)
                <li class=""><a href="{{url('')}}/{{$value->url}}">{{$value->name}}</a></li>
            @endforeach
            
            <!--<li class="{{ (isset($activemenu) && $activemenu=='about') ? 'active' : '' }}"><a href="{{route('frontend.scanner_about')}}">ABOUT</a></li>-->

            <!-- <li class="{{ (isset($activemenu) && $activemenu=='mf-scanner') ? 'active' : '' }}"><a href="{{route('frontend.MFScanner')}}">MF SCREENER</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-scanner-compare') ? 'active' : '' }}"><a href="{{route('frontend.mf_scanner_compare')}}">MF COMPARISON</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='stocks-held-by-mutual-fund') ? 'active' : '' }}"><a href="{{route('frontend.MFStocksHeld')}}">STOCKS HELD</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-rolling-return') ? 'active' : '' }}"><a href="{{route('frontend.mf_rolling_return')}}">ROLLING RETURN</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-category-performance') ? 'active' : '' }}"><a href="{{route('frontend.mf_category_performance')}}">CATEGORY-WISE PERFORMANCE</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-category-wise-performance') ? 'active' : '' }}"><a href="{{route('frontend.mf_category_wise_performance')}}">CATEGORY-WISE SCHEME PERFORMANCE</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-best-worst') ? 'active' : '' }}"><a href="{{route('frontend.mf_best_worst')}}">BEST WORST</a></li>

            <li class="{{ (isset($activemenu) && $activemenu=='mf-portfolio-analysis') ? 'active' : '' }}"><a href="{{route('frontend.portfolio_analysis')}}">PORTFOLIO ANALYSIS</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-investment-analysis') ? 'active' : '' }}"><a href="{{route('frontend.investment_analysis')}}">INVESTMENT ANALYSIS</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='mf-factsheet') ? 'active' : '' }}"><a href="{{route('frontend.factsheet')}}">FACTSHEET</a></li> -->

            <li class="{{ (isset($activemenu) && $activemenu=='mf-research-disclaimers') ? 'active' : '' }}"><a href="{{$pdf}}" target="_blank">DISCLAIMER</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='savefile') ? 'active' : '' }}"><a href="{{route('frontend.scanner_saved_files')}}">VIEW SAVED FILES</a></li>
        </ul>
    </div>
</div>