@php
    $calculator_category = \App\Models\Calculator_category::where('status',1)->orderBy('position','ASC')->get();
@endphp
<div class="col-md-12">
    <div class="top-tab">
        <ul>
            <li class="{{ (isset($activemenu) && $activemenu=='about') ? 'active' : '' }}"><a href="{{route('frontend.calculatorAllList')}}">All</a></li>
            @foreach($calculator_category as $key=>$value)
                <li class=""><a href="{{route('frontend.calculatorCategoryWise',['id'=>$value->id])}}">{{$value->name}}</a></li>
            @endforeach
            <!-- <li class="{{ (isset($activemenu) && $activemenu=='lumpsum') ? 'active' : '' }}"><a href="{{route('frontend.lumpSumIndex')}}">Lumpsum</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='sip') ? 'active' : '' }}"><a href="{{route('frontend.sipIndex')}}">SIP</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='stp') ? 'active' : '' }}" ><a href="{{route('frontend.stpIndex')}}">STP</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='swp') ? 'active' : '' }}" ><a href="{{route('frontend.swpIndex')}}">SWP</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='combination') ? 'active' : '' }}" ><a href="{{route('frontend.combinationIndex')}}">Combination</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='goalplanning') ? 'active' : '' }}" ><a href="{{route('frontend.goalplanningIndex')}}">Goal Planning</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='insurance') ? 'active' : '' }}"><a href="{{route('frontend.insuranceIndex')}}">MF vs Insurance</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='other') ? 'active' : '' }}"><a href="{{route('frontend.other_calculator')}}">Other</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='premium-calculator') ? 'active' : '' }}"><a href="{{route('frontend.premium_calculator')}}">Premium Calculator</a></li> -->
            <li class="{{ (isset($activemenu) && $activemenu=='fund-performance') ? 'active' : '' }}" ><a href="{{route('frontend.fundPerformanceIndex')}}">Fund Performance</a></li>
            <li class="{{ (isset($activemenu) && $activemenu=='savefile') ? 'active' : '' }}"><a href="{{route('frontend.view_saved_files')}}">View Saved File</a></li>
            <li><a href="{{route('frontend.membership')}}">Not a Member?</a></li>
        </ul>
    </div>
</div>