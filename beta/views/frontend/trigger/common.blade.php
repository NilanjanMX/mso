<ul class="cate-list tiggerTab">
    <li class="{{($menu_action=='new-trigger')?'active':''}}"><a href="{{url('trigger/add')}}">New Trigger</a></li>
    <li class="{{($menu_action=='saved-trigger')?'active':''}}"><a href="{{url('trigger/list')}}">Existing/Saved Triggers</a></li>
    <li class="{{($menu_action=='completed-trigger')?'active':''}}"><a href="{{url('trigger/completed')}}">Completed Triggers</a></li>
    <li class="{{($menu_action=='default-trigger')?'active':''}}"><a href="{{url('trigger/default')}}">MSO Triggers</a></li>
    <li class="{{($menu_action=='setting-trigger')?'active':''}}"><a href="{{url('trigger/setting')}}">Settings</a></li>
</ul>