<div class="lft-pnl2 userLeftMenu">
    <ul id="menulist" class="mb-0">
    	@if(Auth::user()->user_id)
    		<li class="@php if($left_menu=='profile'){ echo "active"; } @endphp userProfile">
		    	<a href="{{route('account.profile')}}">My Profile</a>
		    </li>
		    <li class="@php if($left_menu=='display_settings'){ echo "active"; } @endphp userDisplay">
		    	<a href="{{route('account.display-settings')}}">Display Settings</a>
		    </li>
		    <li class="@php if($left_menu=='my_purchases'){ echo "active"; } @endphp userManagement">
		    	<a href="{{route('account.my_purchases')}}">My Purchases</a>
		    </li>
		    <li class="@php if($left_menu=='membershipReferral'){ echo "active"; } @endphp userSubs">
		    	<a href="{{route('account.membershipReferral')}}">Refer a friend</a>
		    </li>
		    <li class="@php if($left_menu=='refer_to_a_friend'){ echo "active"; } @endphp userMembers">
		    	<a href="{{route('account.refer_to_a_friend')}}">Membership Points</a>
		    </li>
    	@else
    		<li class="@php if($left_menu=='profile'){ echo "active"; } @endphp userProfile">
		    	<a href="{{route('account.profile')}}">My Profile</a>
		    </li>
		    <li class="@php if($left_menu=='display_settings'){ echo "active"; } @endphp userDisplay">
		    	<a href="{{route('account.display-settings')}}">Display Settings</a>
		    </li>
		    <li class="@php if($left_menu=='user_management'){ echo "active"; } @endphp userManagement">
		    	<a href="{{route('account.user_management')}}">User Management</a>
		    </li>
		    <!-- <li class="@php if($left_menu=='billing'){ echo "active"; } @endphp">
		    	<a href="{{route('account.billing')}}">Billing Details</a>
		    </li> -->
		    <li class="@php if($left_menu=='my_purchases'){ echo "active"; } @endphp userManagement">
		    	<a href="{{route('account.my_purchases')}}">My Purchases</a>
		    </li>
		    <li class="@php if($left_menu=='subscription'){ echo "active"; } @endphp userSubs">
		    	<a href="{{route('account.subscription.index')}}">Subscriptions</a>
		    </li>
		    <li class="@php if($left_menu=='orders'){ echo "active"; } @endphp userOrder">
		    	<a href="{{route('account.orderlist.index')}}">Orders</a>
		    </li>
		    <li class="@php if($left_menu=='membershipReferral'){ echo "active"; } @endphp userSubs">
		    	<a href="{{route('account.membershipReferral')}}">Refer a friend</a>
		    </li>
		    <li class="@php if($left_menu=='refer_to_a_friend'){ echo "active"; } @endphp userMembers">
		    	<a href="{{route('account.refer_to_a_friend')}}">Membership Points</a>
		    </li>
    	@endif
		    
	    <li class="@php if($left_menu=='change_password'){ echo "active"; } @endphp userChangePass">
	    	<a href="{{route('change.password.index')}}">Change Password</a>
	    </li>
	    <li class="logout"><a href="{{route('logout')}}">Logout</a></li>
	</ul>
</div>