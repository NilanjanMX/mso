@php
    $calculator_category = \App\Models\Calculator_category::where('status',1)->orderBy('position','ASC')->get();
@endphp
<div class="col-md-12">
    <!--<div class="top-tab">-->
    <div class="category-box categoryList">
        <ul class="cate-list">
            <li class="{{ (isset($activemenu) && $activemenu=='about') ? 'active' : '' }}"><a href="{{route('frontend.calculatorAllList')}}">All</a></li>
            @foreach($calculator_category as $key=>$value)
                <li class=""><a href="{{route('frontend.calculatorCategoryWise',['id'=>$value->id])}}">{{$value->name}}</a></li>
            @endforeach
            
            <li class="{{ (isset($activemenu) && $activemenu=='fund-performance') ? 'active' : '' }}" ><a href="{{route('frontend.fundPerformanceIndex')}}">Fund Performance</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='savefile') ? 'active' : '' }}"><a href="{{route('frontend.view_saved_files')}}">View Saved File</a></li>
            <li><a href="{{route('frontend.membership')}}">Not a Member?</a></li>
        </ul>
    </div>
</div>