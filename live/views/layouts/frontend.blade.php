@php
    $site_logo = \App\Models\Option::where('option_name','site_logo')->first();
    $contact_address = \App\Models\Option::where('option_name','contact_address')->first();
    $contact_address2 = \App\Models\Option::where('option_name','contact_address2')->first();
    $contact_phone = \App\Models\Option::where('option_name','contact_phone')->first();
    $contact_email = \App\Models\Option::where('option_name','contact_email')->first();
    $site_title = \App\Models\Option::where('option_name','site_title')->first();
    
    $facebook = \App\Models\Option::where('option_name','facebook')->first();
    $youtube = \App\Models\Option::where('option_name','youtube')->first();
    $linkedin = \App\Models\Option::where('option_name','linkedin')->first();
    $twitter = \App\Models\Option::where('option_name','twitter')->first();
    $android = \App\Models\Option::where('option_name','android')->first();
    $ios = \App\Models\Option::where('option_name','ios')->first();
    $footerSlides = \App\Models\FooterSlide::where('is_active',1)->get();
    

    $page_share = \App\Models\PageShare::where('key_name',Request::path())->first();

    $membershipMenu = true;
    $renwalAlert = 0;
    if (Auth::check()){
        
        
        if(Request::path() != "store"){
            $renwalAlert = 2;
        }
        
        $membership = \App\Models\Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
        if($membership){
            $membershipMenu = true;
            $renwalAlert = 0;
        }else{
            $membershipMenu = false;
        }
        
        $cart_count = \App\Models\UserCarts::where('user_id',Auth::user()->id)->count();
    }else{
        $cart_count = \App\Models\UserCarts::where('cart_user_id',session()->get('cart_user_id'))->count();
    }

@endphp
        
        <!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-117997744-1']);
      _gaq.push(['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <meta property="og:title" content="{{isset($site_title['option_value'])?$site_title['option_value']:'Master Stroke'}}">
    

    @if($page_share)
        <meta property="fb:app_id" content="190232376157246" />
        <meta property="og:type" content="website" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image" content="{{asset('uploads/pageshare/original/'.$page_share->image)}}">
        <meta property="og:url" content="https://www.masterstrokeonline.com/{{Request::path()}}">
        <meta property="og:description" content="Masterstroke offers sales and branding tools for financial intermediaries.">
    @else
        <meta property="og:image" content="{{asset('images/masterstroke.png')}}">
        <meta property="og:url" content="https://www.masterstrokeonline.com">
        <meta property="og:description" content="Masterstroke offers sales and branding tools for financial intermediaries.">
    @endif
    <meta name="facebook-domain-verification" content="4m5l11wk6tvvcg53c9lv360mdb91i0" /> 

    <!-- Bootstrap CSS -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('')}}/f/images/favicon.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{isset($site_title['option_value'])?$site_title['option_value']:'Master Stroke'}}</title>

    @yield('css_before')
    <link rel="stylesheet" href="{{asset('')}}/f/css/bootstrap.min.css">
    <link href="{{asset('')}}/f/css/owl.carousel.min.css" rel="stylesheet">
    <link href="{{asset('')}}/f/css/owl.theme.default.min.css" rel="stylesheet">
    <link href="{{asset('')}}/f/css/prettyPhoto.css" rel="stylesheet" type="text/css"/>

    <!--<link href="{{asset('')}}/f/css/all.min.css" rel="stylesheet" type="text/css"/>-->
    
    <link rel="stylesheet" href="{{asset('')}}/f/css/croppie.css" />
    <link rel="stylesheet" href="{{asset('')}}/f/css/style24022023.css">
    <link rel="stylesheet" href="{{asset('')}}/f/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
    <link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{asset('')}}/f/select/dist/css/select2.min.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,500,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '487250810014914');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=487250810014914&ev=PageView&noscript=1"
    /></noscript>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KC75BLHNG7"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-KC75BLHNG7');
    </script>
    <!-- End Meta Pixel Code -->
@yield('css_after')
<!-- Scripts -->
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>

    <style>
        .unreadnoti .notiimg:after {
            position: absolute;
            left:0;
            top:0;
            content:"";
            width:11px;
            height:11px;
            background:#16a1db;
            border-radius:50%;
        }
        .notioptions {   
            position: absolute;
            right: 0;
            top: 32px;
            box-shadow: 0 0 7px rgb(0 0 0 / 40%);
            border-radius: 12px;
            z-index: 1000;
            background-color: #fff;
            padding: 12px;
            width: 168px;
            display:none;
        }
        .notioptions a {
            display: block;
            font-size: 13px !important;
            line-height: 27px;
        }
        .notificationBlock {
            position: absolute;
            transform: translate3d(-6px, -20px, 0px);
            will-change: transform;
            right: 0 !important;
            top: 66px !important;
            width: 352px;
            padding:14px;
            box-shadow: 0 0 7px rgba(0,0,0,0.4);
            border-radius: 12px;
            z-index: 1000;
            background-color: #fff;
            font-family: "Poppins", sans-serif;
            display:none;
        }
        .notificationBlock h5 {
            font-size:20px;
            font-weight:700;
            text-transform: uppercase;
            position: relative;
            font-family: "Poppins", sans-serif;
            line-height: 32px;
            margin-bottom:0;
        }
        .notificationBlock h5 span {
            font-weight: 400;
            font-size: 18px;
            color: #468ff6;
            padding-left: 25px;
            position: relative;
            top: -2px;
        }
        .notioptionlink {
            position: absolute;
            right:11px;
            top: -1px;
        }
        .notitab {
            border-bottom: 1px solid #ccc;
            padding-top:12px;
            margin-bottom: 12px;
        }
        .notitab a {
            display: inline-block;
            color:#c9c9c9 !important;
            font-size: 16px;
            text-transform: uppercase;
            padding: 0 54px;
            font-weight: 400 !important;
        }
        .notitab a.notiactive {
            color:#444444 !important;
        }
        .notitab a + a {
            border-left: 1px solid #ccc;
        }
        .notificationlist {
            max-height: 300px;
            overflow: auto;
            margin-right: -12px;
            display:none;
        }
        .notificationlist li {
            display: flex;
            position: relative;
            padding: 8px 0;
        }
        .notiimg {
            width: 47px;
        }
        .notiinfo {
            width: calc(100% - 85px);
            padding-left:8px;
        }
        .notiinfo p {
            color: #767676;
            margin:0;
            padding:0;
            font-size: 14px;
            line-height: 20px;
        }
        .notiinfo span {
            color: #767676;
            font-size: 13px;
            display: block;
        }
        .unreadnoti .notiinfo p {
            color: #131f55;
        }
        .unreadnoti .notiinfo span {
            color: #16a1db;
        }
        .noticount {
            position: absolute;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            background: #ff0000;
            color: #fff;
            font-weight: 300;
            font-size: 10px;
            top: -1px;
            text-align: center;
            line-height: 15px;
            right: 4px;
        }
        
        .cartcount {
            position: absolute;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            background: #ff0000;
            color: #fff;
            font-weight: 300;
            font-size: 10px;
            /*top: -1px;*/
            top: -3px;
            text-align: center;
            line-height: 15px;
            /*right: 64px;*/
            right: -13px;
        }
        
        /* Tooltip container */
    
        .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        padding: 5px;
        border-radius: 6px;
        font-size: 13px;
        /* Position the tooltip text - see examples below! */
        position: absolute;
        right: 0px;
        bottom: 70px;
        z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip1:hover .tooltiptext {
            visibility: visible;
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 64px;
            height: 64px;
            z-index: 9999;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s ease-out;
            border-radius: 50%;
            background-image: url({{asset('')}}img/top.png);
        }
        .back-to-top:hover{
            opacity: 0.7;
        }
    </style>
</head>

<body>

<header>
    <div class="head-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="logo">
                        <a href="{{url('/')}}">
                            @if(!isset($site_logo))
                                <img class="img-fluid" src="{{asset('uploads/logo/'.$site_logo['option_value'])}}" alt="{{isset($site_title['option_value'])?$site_title['option_value']:'Master Stroke'}}" />
                            @else
                                <img class="img-fluid" src="{{asset('')}}img/logo.png" alt="" />
                            @endif
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="top-rt-prt">
                        <a href="tel:{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}" class="phoneno">{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}</a>
                        @if (Auth::check())
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="far fa-user"></i> My Account</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('account.profile')}}">My profile</a></li>
                                    <li><a class="dropdown-item" href="{{route('account.display-settings')}}">Display Settings</a></li>
                                    <li><a class="dropdown-item" href="{{route('account.subscription.index')}}">Subscription</a></li>
                                    <li><a class="dropdown-item" href="{{url('logout')}}">Logout</a></li>
                                </ul>
                            </div>
                        @else
                            <a href="{{url('login')}}" class="btn top-login-btn">Login</a>
                            <a href="{{url('free-register')}}" class="btn top-register-btn">Sign up</a>
                        @endif
                        <a href="{{route('frontend.cart')}}" class="position-relative">
                            <img class="img-fluid" src="{{asset('')}}img/cart.png" alt="" />
                            @if($cart_count)
                                <b class="cartcount">{{$cart_count}}</b>
                            @endif
                        </a>
                        
                        @if (Auth::check())
                        <div class="dropdown">
                            @if($header_data['noti_count'] > 0)
                                <b class="noticount">{{$header_data['noti_count']}}</b>
                            @endif
                            @php
                                $setting = \App\Models\NotificationSetting::where('key', 'expiry_period')->first();
                                $expiry = $setting->value;
                            @endphp
                            <a href="#" class="fornotification readAllNoti"><img class="img-fluid" src="{{asset('')}}img/notification_icon.png" alt="" /></a>
                            <input type="hidden" value="{{$header_data['allNotiIds']}}" id="readAllNotiValue">
                            <div class="notificationBlock notificationBlockRespon">
                                <h5>Notifications <span id="span_counter">{{$header_data['total_unread']}}</span>
                                    
                                </h5>
                                <div class="notitab">
                                    <a href="#allnoti" class="notiactive">All</a>
                                    <a href="#unreadnoti">Unread</a>
                                </div>
                                <ul class="notificationlist" id="allnoti" style="display:block;">
                                    @foreach($header_data['all_notifications'] as $data)
                                    @php
                                        $created = new \Carbon\Carbon($data->created_at);
                                        $now = \Carbon\Carbon::now();
                                        $difference = $created->diff($now)->days;
                                    @endphp 
                                    @if($data->status != 1 && $difference < $expiry) 
                                        <a href="javascript:void(0)" class="readit" id="readit{{$data->id}}" data-url="{{$data->url}}" data-id="{{$data->id}}">
                                        <li class="readit{{$data->id}} @if($data->status != 1) unreadnoti @endif">
                                            <div class="notiimg" style="width: 50px; height: 50px; background: @if($data->status == 1) grey; @else #131f55;@endif color: white; border-radius: 50%;">
                                                {{-- <i class="fa fa-bell" style="position: absolute; top: 25px; left: 18px;"></i> --}}
                                                @if(!empty($data->image))
                                                    <img class="img-fluid" src="{{asset('')}}uploads/salespresentersoftcopy/{{$data->image}}" alt="" />
                                                @else
                                                    <img class="img-fluid" src="{{asset('')}}uploads/salespresentersoftcopy/defaultnotification.png" alt="" />
                                                @endif
                                            </div>
                                            <div class="notiinfo">
                                            <p>
                                                <b>{{$data->title}}</b> 
                                                {{strip_tags($data->description)}} 
                                                </p>
                                            <span>
                                                {{\Carbon\Carbon::parse($data->updated_at)->diffForHumans()}}
                                            </span>
                                            @if(\Carbon\Carbon::parse($data->updated_at)->diff()->format('%a') > 7)
                                            <span class="">
                                                <a href="" class="btn btn-sm btn-info text-light markAsReadBtn" id="markAsReadBtn{{$data->id}}" data-url="{{$data->url}}" data-id="{{$data->id}}">Mark as Read</a>
                                            </span>
                                            @endif
                                            </div>
                                        </li>
                                        </a>
                                    @endif
                                    @endforeach
                                    @foreach($header_data['all_notifications'] as $data)
                                    @php
                                        $created = new \Carbon\Carbon($data->created_at);
                                        $now = \Carbon\Carbon::now();
                                        $difference = $created->diff($now)->days;
                                    @endphp 
                                    @if($data->status != 0 && $difference < $expiry) 
                                        <a href="javascript:void(0)" class="readit" id="readit{{$data->id}}" data-url="{{$data->url}}" data-id="{{$data->id}}">
                                        <li class="readit{{$data->id}} @if($data->status != 1) unreadnoti @endif">
                                            <div class="notiimg" style="width: 50px; height: 50px; background: @if($data->status == 1) grey; @else #131f55;@endif color: white; border-radius: 50%;">
                                                {{-- <i class="fa fa-bell" style="position: absolute; top: 25px; left: 18px;"></i> --}}
                                                @if(!empty($data->image))
                                                    <img class="img-fluid" src="{{asset('')}}uploads/salespresentersoftcopy/{{$data->image}}" alt="" />
                                                @else
                                                    <img class="img-fluid" src="{{asset('')}}uploads/salespresentersoftcopy/defaultnotification.png" alt="" />
                                                @endif
                                            </div>
                                            <div class="notiinfo">
                                            <p>
                                                <b>{{$data->title}}</b> 
                                                {{strip_tags($data->description)}} 
                                                </p>
                                            <span>
                                                {{\Carbon\Carbon::parse($data->updated_at)->diffForHumans()}}
                                                </span>
                                            </div>
                                        </li>
                                        </a>
                                    @endif
                                    @endforeach
                                    
                                </ul>
                                <ul class="notificationlist" id="unreadnoti">
                                    @foreach($header_data['all_notifications'] as $data)
                                    @php
                                        $created = new \Carbon\Carbon($data->created_at);
                                        $now = \Carbon\Carbon::now();
                                        $difference = $created->diff($now)->days;
                                    @endphp 
                                    @if($data->status != 1 && $difference < $expiry) 
                                    {{-- @if($data->status != 1) --}}
                                    <a href="javascript:void(0)" class="readit" id="unreadremove{{$data->id}}" data-url="{{$data->url}}" data-id="{{$data->id}}">
                                    <li class="readit{{$data->id}} unreadnoti">
                                        <div class="notiimg" style="width: 50px; height: 50px; background: @if($data->status == 1) grey; @else #131f55;@endif color: white; border-radius: 50%;">
                                            {{-- <i class="fa fa-bell" style="position: absolute; top: 25px; left: 18px;"></i> --}}
                                            @if(!empty($data->image))
                                                <img class="img-fluid" src="{{asset('')}}uploads/salespresentersoftcopy/{{$data->image}}" alt="" />
                                            @else
                                                <img class="img-fluid" src="{{asset('')}}uploads/salespresentersoftcopy/defaultnotification.png" alt="" />
                                            @endif
                                        </div>
                                        
                                        <div class="notiinfo">
                                           <p><b>{{$data->title}}</b> {{strip_tags($data->description)}} </p>
                                           <span>{{\Carbon\Carbon::parse($data->updated_at)->diffForHumans()}}</span>
                                        </div>
                                        
                                    </li>
                                    </a>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                            
                        </div>
                        @endif
                    </div>
                    <div class="top-menu">
                        <ul id="normal-menu">

                            <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Membership</a>
                            <ul class="dropdown-menu">
                                    @if (Auth::check())
                                        @if($membershipMenu)
                                            @if(Auth::user()->permission_sales_presenter)
                                                <li><a class="dropdown-item" href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>
                                            @endif
                                            @if(Auth::user()->permission_calculators_proposals)
                                                <li><a class="dropdown-item" href="{{route('frontend.calculatorAllList')}}">Client Proposals</a></li>
                                            @endif
                                            <?php //dd(Auth::user()); ?>
                                            @if(Auth::user()->permission_premium_calculator)
                                                <li><a class="dropdown-item" href="{{route('frontend.premium_calculator')}}">Premium Reports</a></li>
                                            @endif
                                            <li><a class="dropdown-item"  href="{{route('frontend.msoportfolioinput')}}">MSO Model Portfolio</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.readymadePortfolio.index')}}">MSO Readymade Portfolio</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.trigger_add')}}">MSO Triggers</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.welcomeletter.index')}}">Welcome Letter</a></li>
                                            @if(Auth::user()->package_id == 17)
                                                
                                            @endif
                                            @if(Auth::user()->permission_investment_suitablity_profiler)
                                                <li><a class="dropdown-item" href="{{route('frontend.suitability_profiler')}}">Investment Suitability Profiler</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_banners)
                                                <li><a class="dropdown-item" href="{{url('premium-banners')}}">Marketing Banners</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_video)
                                                <li><a class="dropdown-item" href="{{url('marketing-video')}}">Marketing Videos</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premade_sales_presenter)
                                                <li><a class="dropdown-item" href="{{route('frontend.premade-sales-presenter')}}">Pre-Made Sales Presenters</a></li>
                                            @endif
                                            
                                            <li><a class="dropdown-item" href="{{route('frontend.client-communication')}}">Client Communication</a></li>
                                            @if(Auth::user()->permission_famous_quotes)
                                                <li><a class="dropdown-item" href="{{route('frontend.famous_quotes')}}">Other Downloads</a></li>
                                            @endif
                                            @if(Auth::user()->permission_trail_calculators)
                                                <li><a class="dropdown-item" href="{{route('frontend.BrokerageIndex')}}">Trail Calculator</a></li>
                                            @endif
                                        @else
                                            @if(Auth::user()->permission_sales_presenter)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Sales Presenters</a></li>
                                            @endif
                                            @if(Auth::user()->permission_calculators_proposals)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Client Proposals</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premium_calculator)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Premium Reports</a></li>
                                            @endif
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">MSO Model Portfolio</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">MSO Readymade Portfolio</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">MSO Triggers</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Welcome Letter</a></li>
                                            @if(Auth::user()->package_id == 17)
                                                
                                            @endif
                                            @if(Auth::user()->permission_investment_suitablity_profiler)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Investment Suitability Profiler</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_banners)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Marketing Banners</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_video)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Marketing Videos</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premade_sales_presenter)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Pre-Made Sales Presenters</a></li>
                                            @endif
                                            
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Client Communication</a></li>
                                            @if(Auth::user()->permission_famous_quotes)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Other Downloads</a></li>
                                            @endif
                                            @if(Auth::user()->permission_trail_calculators)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Trail Calculator</a></li>
                                            @endif
                                            {{-- @if (Auth::user()->user_type == '')
                                                <li><a class="dropdown-item" href="{{url('membership')}}">Become a member</a></li>
                                            @endif
                                            @if (Auth::user()->user_type == 'N')
                                                <li><a class="dropdown-item" href="{{url('/account/subscription')}}">Upgrade Subscription</a></li>
                                            @endif --}}
                                        @endif
                                    @else
                                        <li><a class="dropdown-item" href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.calculatorAllList')}}">Client Proposals</a></li>
                                        <!-- <li><a class="dropdown-item" href="{{route('frontend.premium_calculator')}}">Premium Reports</a></li> -->
                                        <li><a class="dropdown-item" href="{{route('frontend.msoportfolioinput')}}">MSO Model Portfolio</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.readymadePortfolio.index')}}">MSO Readymade Portfolio</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.trigger_add')}}">MSO Triggers</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.welcomeletter.index')}}">Welcome Letter</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.suitability_profiler')}}">Investment Suitability Profiler</a></li>
                                        <li><a class="dropdown-item" href="{{url('premium-banners')}}">Marketing Banners</a></li>
                                        <li><a class="dropdown-item" href="{{url('marketing-video')}}">Marketing Videos</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.premade-sales-presenter')}}">Pre-Made Sales Presenters</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.client-communication')}}">Client Communication</a></li>
                                        <!--<li><a class="dropdown-item" href="{{route('frontend.paid-videos')}}">Training Videos</a></li>-->
                                        <li><a class="dropdown-item" href="{{route('frontend.BrokerageIndex')}}">Trail Calculator</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.famous_quotes')}}">Other Downloads</a></li>
                                        <li><a class="dropdown-item" href="{{url('membership')}}">Become a member</a></li>
                                    @endif
                                </ul>
                            </li>
                            @if (Auth::check())
                                @if(Auth::user()->user_id)
                                    @if(Auth::user()->permission_scanner)
                                        <li><a href="{{route('frontend.MFScanner')}}">MF Research</a></li>
                                    @endif
                                @else
                                    <li><a href="{{route('frontend.MFScanner')}}">MF Research</a></li>
                                @endif
                            @else
                                <li><a href="{{route('frontend.MFScanner')}}">MF Research</a></li>
                            @endif
                            <!--<li><a href="{{route('frontend.conference.index')}}">Conference</a></li>-->
                            <li><a href="{{route('frontend.demo.index')}}">Demo</a></li>
                            <li><a href="{{route('frontend.membership')}}">Pricing</a></li>
                            <li><a href="{{route('frontend.stationary')}}">Store</a></li>
                            <!--<li><a href="{{route('frontend.coaching')}}">Coaching</a></li>-->
                            @if (Auth::check())
                                <li><a href="{{url('/library/index')}}">My library</a></li>
                            @else
                                <li><a href="{{url('/website-tree')}}">Website Tree</a></li>
                            @endif
                            <!--<li><a href="{{route('frontend.blog')}}">Blog</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="head-bttm">
        <div class="container">
            <div class="top-contact">
                <img src="{{asset('')}}/f/images/call-ico.png" alt="">
                <p>HELP LINE <a href="tel:{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}">{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}</a></p>
            </div>
            <div class="main-menu">
                <nav class="navbar navbar-expand-md navbar-light navigation">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                            
                            
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto" id="normal-menu">
                            <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Membership</a>
                                <ul class="dropdown-menu">
                                    @if (Auth::check())
                                        @if($membershipMenu)
                                            @if(Auth::user()->permission_sales_presenter)
                                                <li><a class="dropdown-item" href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>
                                            @endif
                                            @if(Auth::user()->permission_calculators_proposals)
                                                <li><a class="dropdown-item" href="{{route('frontend.calculatorAllList')}}">Client Proposals</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premium_calculator)
                                                <li><a class="dropdown-item" href="{{route('frontend.premium_calculator')}}">Premium Reports</a></li>
                                            @endif
                                            <li><a class="dropdown-item"  href="{{route('frontend.msoportfolioinput')}}">MSO Model Portfolio</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.readymadePortfolio.index')}}">MSO Readymade Portfolio</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.trigger_add')}}">MSO Triggers</a></li>
                                            <li><a class="dropdown-item" href="{{route('frontend.welcomeletter.index')}}">Welcome Letter</a></li>
                                            @if(Auth::user()->package_id == 17)
                                                
                                            @endif
                                            @if(Auth::user()->permission_investment_suitablity_profiler)
                                                <li><a class="dropdown-item" href="{{route('frontend.suitability_profiler')}}">Investment Suitability Profiler</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_banners)
                                                <li><a class="dropdown-item" href="{{url('premium-banners')}}">Marketing Banners</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_video)
                                                <li><a class="dropdown-item" href="{{url('marketing-video')}}">Marketing Videos</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premade_sales_presenter)
                                                <li><a class="dropdown-item" href="{{route('frontend.premade-sales-presenter')}}">Pre-Made Sales Presenters</a></li>
                                            @endif
                                            
                                            <li><a class="dropdown-item" href="{{route('frontend.client-communication')}}">Client Communication</a></li>
                                            @if(Auth::user()->permission_famous_quotes)
                                                <li><a class="dropdown-item" href="{{route('frontend.famous_quotes')}}">Other Downloads</a></li>
                                            @endif
                                            @if(Auth::user()->permission_trail_calculators)
                                                <li><a class="dropdown-item" href="{{route('frontend.BrokerageIndex')}}">Trail Calculator</a></li>
                                            @endif
                                        @else
                                            @if(Auth::user()->permission_sales_presenter)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Sales Presenters</a></li>
                                            @endif
                                            @if(Auth::user()->permission_calculators_proposals)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Client Proposals</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premium_calculator)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Premium Reports</a></li>
                                            @endif
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">MSO Readymade Portfolio</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">MSO Triggers</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Welcome Letter</a></li>
                                            @if(Auth::user()->package_id == 17)
                                                
                                            @endif
                                            @if(Auth::user()->permission_investment_suitablity_profiler)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Investment Suitability Profiler</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_banners)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Marketing Banners</a></li>
                                            @endif
                                            @if(Auth::user()->permission_marketing_video)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Marketing Videos</a></li>
                                            @endif
                                            @if(Auth::user()->permission_premade_sales_presenter)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Pre-Made Sales Presenters</a></li>
                                            @endif
                                            
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Client Communication</a></li>
                                            @if(Auth::user()->permission_famous_quotes)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Other Downloads</a></li>
                                            @endif
                                            @if(Auth::user()->permission_trail_calculators)
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="openGlobalRenewalAlert();">Trail Calculator</a></li>
                                            @endif
                                        @endif
                                    @else
                                        <li><a class="dropdown-item" href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.calculatorAllList')}}">Client Proposals</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.premium_calculator')}}">Premium Reports</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.readymadePortfolio.index')}}">MSO Readymade Portfolio</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.trigger_add')}}">MSO Triggers</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.suitability_profiler')}}">Investment Suitability Profiler</a></li>
                                        <li><a class="dropdown-item" href="{{url('premium-banners')}}">Marketing Banners</a></li>
                                        <li><a class="dropdown-item" href="{{url('marketing-video')}}">Marketing Videos</a></li>
                                        <li><a class="dropdown-item" href="{{route('frontend.premade-sales-presenter')}}">Pre-Made Sales Presenters</a></li>
                                        <!--<li><a class="dropdown-item" href="{{route('frontend.paid-videos')}}">Training Videos</a></li>-->
                                        <li><a class="dropdown-item" href="{{route('frontend.BrokerageIndex')}}">Trail Calculator</a></li>

                                        <li><a class="dropdown-item" href="{{route('frontend.famous_quotes')}}">Other Downloads</a></li>
                                        <li><a class="dropdown-item" href="{{url('membership')}}">Become a member</a></li>
                                    @endif
                                </ul>
                            </li>
                            <!--<li><a href="{{route('frontend.free-videos')}}">Short Videos</a>-->
                            <!-- <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('frontend.paid-videos')}}">Paid Videos</a></li>
                                <li><a class="dropdown-item" href="{{url('marketting-free-videos')}}">Free Videos</a></li>
                            </ul> 
                            </li>
                            <li><a href="{{route('frontend.ifa-tools')}}">TOOLS</a></li>-->
                            <!-- <li><a href="{{route('frontend.mockExamIndex')}}">MOCK EXAMS</a></li> -->
                            @if (Auth::check())
                                @if(Auth::user()->user_id)
                                    @if(Auth::user()->permission_scanner)
                                        <li><a href="{{route('frontend.MFScanner')}}">MF Research</a></li>
                                    @endif
                                @else
                                    <li><a href="{{route('frontend.MFScanner')}}">MF Research</a></li>
                                @endif
                            @else
                                <li><a href="{{route('frontend.MFScanner')}}">MF Research</a></li>
                            @endif
                            <!--<li><a href="{{route('frontend.conference.index')}}">Conference</a></li>-->
                            <li><a href="{{route('frontend.demo.index')}}">Demo</a></li>
                            <li><a href="{{route('frontend.membership')}}">Pricing</a></li>
                            <li><a href="{{route('frontend.stationary')}}">Store</a></li>
                            @if (Auth::check())
                                <li><a href="{{url('/library/index')}}">My library</a></li>
                            @else
                                <li><a href="{{url('/website-tree')}}">Website Tree</a></li>
                            @endif
                            <!--<li><a href="{{route('frontend.coaching')}}">COACHING</a></li>-->
                            <!--<li><a href="{{url('/website-tree')}}">WEBSITE TREE</a></li>-->
                            <div class="d-block d-md-none">
                                <li><a href="{{url('/')}}">Home</a></li>
                                <li><a href="{{route('frontend.aboutUs')}}">About</a></li>
                                <li><a href="{{route('frontend.disclaimers')}}">Disclaimers</a></li>
                                <li><a href="{{route('frontend.contactUs')}}">Contact</a></li>
                            </div>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    
    
</header>


@yield('content')
<!-- Modal -->
<div class="modal fade" id="saveOutput" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">SAVE OUTPUT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="save_title" autocomplete="off" placeholder="Enter Desired Download File Name">
                </div>
                <div id="save_cal_msg" class="alert ">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Back</button>
                <button type="button" class="btn btn-primary btn-round" id="save_cal_btn">Save</button>
            </div>
        </div>
    </div>
</div>


<div class="newsletter">
    @if(isset($footerSlides) && count($footerSlides) != 0)
    <div id="newsletterSlider" class="owl-carousel owl-theme">
        @foreach($footerSlides as $footerSlide)
            <div class="item">
                <img class="img-fluid similarProductsImg" src="{{asset("/uploads/footerslide/thumbnail/$footerSlide->image")}}" alt="" />
            </div>
            {{-- <div class="item">
                <img class="img-fluid similarProductsImg" src="{{asset('')}}img/footerSlider.jpg" alt="" />
            </div>
            <div class="item">
                <img class="img-fluid similarProductsImg" src="{{asset('')}}img/footerSlider.jpg" alt="" />
            </div> --}}
        @endforeach
    </div>
    @endif
    <div class="slider_nav">
        <a class="am-next"><img class="img-fluid" src="{{asset('')}}img/frameleft.png" alt="" /></a>
        <a class="am-prev"><img class="img-fluid" src="{{asset('')}}img/frameright.png" alt="" /></a>
    </div>
    <!--<div class="container">-->
    <!--    <form action="#">-->
    <!--        <div class="newsletterblock">-->
    <!--            <h3>Don’t miss our weekly updates about Mutual Fund investment information</h3>-->
    <!--            <div class="newsinputs">-->
    <!--                <input type="text" placeholder="Enter your email address">-->
    <!--                <input type="submit" value="Subscribe">-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </form>-->
    <!--</div>-->
</div>

<div class="btm-shape-prt">
	<img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />
</div>
    
<footer>
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="f-logo">
                        <a href="{{url('/')}}">
                            @if(isset($site_logo))
                                <img class="img-fluid" src="{{asset('')}}/f/images/site_logo_1602484384.png" alt="" />
                            @else
                                <img class="img-fluid" src="{{asset('')}}/f/images/site_logo_1602484384.png" alt="" />
                            @endif
                        </a>
                    </div> 
                    <ul class="address-list">
                        <li class="footerGps">
                            <!--<img class="img-fluid" src="{{asset('')}}/img/foot_loca_icon.png" alt="" />-->
                            <p>{{isset($contact_address['option_value'])?$contact_address['option_value']:''}}</p>
                            <p>{{isset($contact_address2['option_value'])?$contact_address2['option_value']:''}}</p>
                        </li>
                        
                        <li class="footerMail">
                            <!--<img class="img-fluid" src="{{asset('')}}/img/foot_add_icon.png" alt="" />-->
                            <a href="mailto:{{isset($contact_email['option_value'])?$contact_email['option_value']:''}}">{{isset($contact_email['option_value'])?$contact_email['option_value']:''}}</a>
                        </li>

                        <li class="footerPh">
                            <!--<img class="img-fluid" src="{{asset('')}}/img/foot_call_icon.png" alt="" />-->
                            <a href="tel:{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}">{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}</a>
                        </li>
                    </ul>
                    <div class="social-media">
                        <ul>
                            @if(!empty($facebook['option_value']))
                            <li>
                                <a href="{{$facebook['option_value']}}" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($youtube['option_value']))
                            <li>
                                <a href="{{$youtube['option_value']}}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($linkedin['option_value']))
                            <li>
                                <a href="{{$linkedin['option_value']}}" target="_blank">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                            @endif
                            @if(!empty($twitter['option_value']))
                            <li>
                                <a href="{{$twitter['option_value']}}" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="footer-menu-title">About</div>
                    <ul class="footer-menu">
                        <li><a href="{{route('frontend.aboutUs')}}">About Us</a></li>
                        <li><a href="{{route('frontend.membership')}}">Become a member</a></li>
                        <li><a href="{{route('frontend.whatsapp-broadcast')}}">Join Broadcast</a></li>
                        <li><a href="{{route('frontend.contactUs')}}">Contact Us</a></li>
                        <!--<li><a href="{{route('frontend.ask-brijesh')}}">Contact Us</a></li>-->
                        
                        <!--<li><a href="{{route('frontend.ask-brijesh')}}">Feedback</a></li>-->
                        <!--<li><a href="{{route('frontend.membership')}}">Free Trial Period</a></li>-->
                        <!--<li><a href="{{route('frontend.paid-videos')}}">Membership Videos</a></li>-->
                        <!--<li><a href="{{route('frontend.lumpSumIndex')}}">Trail Calculator</a></li>-->
                        <!--<li><a href="{{route('frontend.demo.index')}}">Demo</a></li>-->

                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <div class="footer-menu-title">Resources</div>
                    <ul class="footer-menu">
                        <li><a href="{{route('frontend.previous-webinar')}}">Previous Webinar</a></li>
                        <li><a href="{{route('frontend.webinars')}}">Upcoming Webinars</a></li>
                        <li><a href="{{route('frontend.free-videos')}}">Short video</a></li>
                        <li><a href="{{route('frontend.ifa-tools')}}">Tools</a></li>
                        <li><a href="{{route('frontend.ifa-product-faqs')}}">Product FAQs</a></li>
                        <li><a href="{{route('frontend.ifa-business-faqs')}}">Business FAQ</a></li>
                        <li><a href="{{route('frontend.products-suitability')}}">Product Suitability</a></li>
                        <li><a href="{{route('frontend.article')}}">Articles</a></li>
                        <li><a href="{{route('frontend.blog')}}">Blogs</a></li>
                        
                        <!--<li><a href="{{route('frontend.MFScanner')}}">MF Screener</a></li>-->
                        <!--<li><a href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>-->
                        <!--<li><a href="{{route('frontend.lumpSumIndex')}}">Client Proposal </a></li>-->
                        <!--<li><a href="{{route('frontend.client-communication')}}">Client Communication</a></li>-->
                        <!--<li><a href="{{route('frontend.conference.index')}}">National Conference</a></li>-->
                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <div class="footer-menu-title">Others</div>
                    <ul class="footer-menu">
                        <li><a href="{{route('frontend.how-to-use-videos')}}">How to Use Videos</a></li>
                        <li><a href="{{route('frontend.success-stories')}}">Success Stories</a></li>
                        <li><a href="{{route('frontend.mockExamIndex')}}">Mock Exams</a></li>
                        <li><a href="{{route('frontend.coaching')}}">Coaching</a></li>
                        <li><a href="{{route('frontend.mso-associate')}}">MSO Associate</a></li>
                        <li><a href="{{route('frontend.fifa_membership_process')}}">FIFA Membership</a></li>
                        <li><a href="{{route('frontend.nps')}}">Invest in NPS</a></li>
                        <li><a href="{{route('frontend.write-a-testimonial')}}">Write Testimonial</a></li>
                        
                        <!--<li><a href="{{route('frontend.stationary')}}">Store</a></li>-->
                        <!--<li><a href="{{route('frontend.readymadePortfolio.index')}}">Readymade Portfolio</a></li>-->
                    </ul>
                </div>
                <div class="col-6 col-md-2">
                    <div class="footer-menu-title">Policies</div>
                    <ul class="footer-menu">
                        <li><a href="{{route('frontend.privacy-policy')}}">Privacy Policy</a></li>
                        <li><a href="{{route('frontend.terms-conditions')}}">Terms & Conditions</a></li>
                        <li><a href="{{route('frontend.disclaimers')}}">Disclaimer</a></li>
                        
                        <!--<li><a href="{{route('frontend.websiteTree')}}">Website Tree</a></li>-->
                        <!--<li><a href="{{route('frontend.welcomeletter.index')}}">Welcome Letter</a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!--<div class="footer-mid">-->
    <!--    @if(!empty($ios['option_value']) || !empty($android['option_value'] ))-->
    <!--    <div class="download-app">-->
    <!--        <h6>Our Flagship Mobile App</h6>-->
    <!--        <ul>-->
    <!--            @if(!empty($ios['option_value']))-->
    <!--            <li>-->
    <!--                <a href="{{$ios['option_value']}}" target="_blank">-->
    <!--                    <img src="{{asset('')}}/f/images/apple-store.png" class="img-fluid" alt="">-->
    <!--                </a>-->
    <!--            </li>-->
    <!--            @endif-->
    <!--            @if(!empty($android['option_value']))-->
    <!--            <li>-->
    <!--                <a href="{{$android['option_value']}}" target="_blank">-->
    <!--                    <img src="{{asset('')}}/f/images/play-store.png" class="img-fluid" alt="">-->
    <!--                </a>-->
    <!--            </li>-->
    <!--            @endif-->
    <!--        </ul>-->
    <!--    </div>-->
    <!--    @endif-->
    <!--</div>-->
    <div class="footer-bttm">
        <div class="container">
            <div class="copyrDiv">
                <p class="copyright">&copy; {{date('Y')}} All Rights Reserved. </p>
                <!--<p class="copyright">Developed by <a target="_blank" href="https://zabingo.com/" rel="nofollow">Zabingo Softwares</a></p>-->
            </div>
        </div>
    </div>
</footer>


<div class="modal fade" id="global_renewal_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Renewal Alert</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">Your membership has expired , kindly renew now</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{route('account.upgradePackage')}}" class="btn btn-secondary btnblue">Upgrade plan</a>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="global_renewal_alert1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Renewal Alert</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="success_model_body" style="text-align: center;font-size: 16px;">You do not have access to this feature, kindly refer to your membership plan and renew/upgrade your membership</p>
        </div>
        <div class="modal-footer text-center" style="justify-content: center;">
            <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
            <a href="{{route('account.subscription.index')}}" type="button" class="btn btn-secondary btnblue">Renew Now</a>
        </div>
    </div>
  </div>
</div>

<a href="#" class="back-to-top"></a>
@yield('js_before')
<script src="{{asset('')}}/f/js/jquery.min.js"></script>

<script src="{{asset('')}}/f/js/jquery-migrate-1.3.0.js"></script>
<script src="{{asset('')}}/f/js/popper.min.js"></script>
<script src="{{asset('')}}/f/js/bootstrap.min.js"></script>
<script src="{{asset('')}}/f/js/owl.carousel.min.js"></script>
<script src="{{asset('')}}/f/js/stepper.js"></script>
<script src="{{asset('')}}/f/js/jquery.prettyPhoto.js"></script>
<script src="{{asset('')}}/f/js/jquery.validate.js"></script>
<script src="{{asset('')}}/f/select/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js" integrity="sha512-4cwt+hxZvRNV/uyChBvjB3AHzwHYxUbd3aESA5sVCilu1fw9gDAGOOQz8yVEArFYM/kyYQx5vqkvphTeA7OWGg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/share.js') }}"></script>
<script src="{{ asset('js/croppie.js') }}"></script>
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">

    @if($renwalAlert == 2)
        $("#global_renewal_alert1").modal("show");
    @endif
    
    function openGlobalRenewalAlert(){
        $("#global_renewal_alert").modal("show");
    }
    
    jQuery(document).ready(function($) {
        
        $(window).scroll(function(){
            var showAfter = 100;
            if ($(this).scrollTop() > showAfter) {                 
                $('.back-to-top').fadeIn();
            } else {   
                $('.back-to-top').fadeOut();
            }
            });
            $('.back-to-top').click(function(){
            $('html, body').animate({scrollTop : 0},800);
            return false;
        });
        
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
        $(".notitab a").click(function(e) {
            e.preventDefault();
            $(".notitab a").removeClass('notiactive');
            $(".notificationlist").css('display','none');
            var opentab = $(this).attr('href');
            $(opentab).css('display','block');
            $(this).addClass('notiactive');
        });
        $(".notioptionlink").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).parent().children('.notioptions').toggle();
        });
        
        $(".fornotification").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.notificationBlock').toggle();
        });
        $(document).click(function(event) { 
            var $target = $(event.target);
            if(!$target.closest('.notificationBlock').length && 
            $('.notificationBlock').is(":visible")) {
                $('.notificationBlock').hide();
                $('.notioptions').hide();
            }        
        });

    ///count
      var a = 0;
      setTimeout(function() {
      $(window).scroll(function () {
        
        var oTop = $('#counter').offset().top - window.innerHeight;
        if (a == 0 && $(window).scrollTop() > oTop) {
          $('.counter-value').each(function () {
            var $this = $(this),
              countTo = $this.attr('data-count');
            $({
              countNum: $this.text()
            }).animate({
              countNum: countTo
            },
              {
                duration: 2000,
                easing: 'swing',
                step: function () {
                  $this.text(Math.floor(this.countNum));
                },
                complete: function () {
                  $this.text(this.countNum);
                  //alert('finished');
                }
              });
          });
          a = 1;
        }
        
      });
    }, 500);
      ///count

    });
</script>

<?php if((isset($_SESSION['mobile']) && $_SESSION['mobile']=='1') || (isset($mobile) && $mobile == 1)){ ?>

<?php }else{ ?>
<!-- Start of Tawk.to Script- ->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5c246b2a82491369ba9f968f/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->
<?php } ?>


<!--End of Tawk.to Script-->
<script src="{{asset('')}}/f/js/main.js"></script>
<script>
$(document).ready(function() {
    var owl = $('#whatsnew');
    owl.owlCarousel({
        loop:true,
        nav:true,
        dots:true,
        margin:30,
        autoplay:true,
        autoplayTimeout:3000,
        navText: [$('.am-next'),$('.am-prev')],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },            
            960:{
                items:3
            },
            1200:{
                items:3
            }
        }
    });
    var owltest = $('#customerSlide');
    owltest.owlCarousel({
        loop:true,
        nav:true,
        dots:true,
        margin:30,
        autoplay:true,
        autoplayTimeout:3000,
        navText: [$('.am-next'),$('.am-prev')],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },            
            960:{
                items:1
            },
            1200:{
                items:1
            }
        }
    });
    var owlproduct = $('#similarProductSlider');
    owlproduct.owlCarousel({
        loop:true,
        nav:true,
        dots:false,
        margin:30,
        autoplay:false,
        autoplayTimeout:3000,
        navText: [$('.am-next'),$('.am-prev')],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },            
            960:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });
    var owlproduct = $('#newsletterSlider');
    owlproduct.owlCarousel({
        loop:true,
        nav:false,
        dots:true,
        margin:0,
        autoplay:true,
        autoplayTimeout:5000,
        navText: [$('.am-next'),$('.am-prev')],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },            
            960:{
                items:1
            },
            1200:{
                items:1
            }
        }
    });
    var owlmostViewed = $('#mostViewedSlider');
    owlmostViewed.owlCarousel({
        loop:true,
        nav:false,
        dots:false,
        margin:30,
        autoplay:true,
        autoplayTimeout:3000,
        navText: [$('.am-next'),$('.am-prev')],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },            
            960:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });
    var owlproduct = $('#similarBlogSlider');
    owlproduct.owlCarousel({
        loop:true,
        nav:true,
        dots:false,
        margin:30,
        autoplay:false,
        autoplayTimeout:3000,
        navText: [$('.am-next'),$('.am-prev')],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },            
            960:{
                items:3
            }
        }
    });
    
    $('.cartPaymentBox .rt-cart-prt').css('minHeight',$('.cartPaymentBox').height());
});

</script>
@yield('js_after')
@include('frontend.calculators.suggested.script')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

@if(!empty($header_data['total_unread']))
var counter = {{$header_data['total_unread']}};
@else
var counter = 0;
@endif
if(counter == 0){
    $('.noticount').hide();
    $('#span_counter').hide();
}
$(document).ready(function() {
     $(".readit").on("click", function(e) {
        // alert(counter);
        e.preventDefault();
        var redirect = $(this).data("url");
        var url = "{{route('frontend.readNotification')}}";
        var id = $(this).data("id");
        $('.readit'+id).removeClass('unreadnoti');
        $('#unreadremove'+id).hide();
        $('#allnoti').append($('#readit'+id));
        $('#markAsReadBtn'+id).hide();
        counter = counter -1;
        if(counter == 0){
            $('.noticount').hide();
            $('#span_counter').hide();
        }else{
            $('.noticount').text(counter);
            $('#span_counter').text(counter);
        }
        // alert('readit'+id);
        $.ajax({
            url: url,
            type: "POST",
            data: 'id='+id,
            success: function (data) {
                // console.log(data);
                // $('#readit'+id).hide();
                
                if(data === '0' || data === 0){
                    alert('Something went wrong!');
                }else{
                    var win = window.open(redirect, '_blank');
                    if (win) {
                        win.focus();
                    }
                    // $('#friends-count').text(data.total_friends);
                }
            },
        });
    });

    $(".markAsReadBtn").on("click", function(e) {
        // alert(counter);
        e.preventDefault();
        var redirect = $(this).data("url");
        var url = "{{route('frontend.readNotification')}}";
        var id = $(this).data("id");
        $('.readit'+id).removeClass('unreadnoti');
        $('#unreadremove'+id).hide();
        $('#allnoti').append($('#readit'+id));
        $('#markAsReadBtn'+id).hide();
        counter = counter -1;
        if(counter == 0){
            $('.noticount').hide();
            $('#span_counter').hide();
        }else{
            $('.noticount').text(counter);
            $('#span_counter').text(counter);
        }
        // alert('readit'+id);
        $.ajax({
            url: url,
            type: "POST",
            data: 'id='+id,
            success: function (data) {
                // console.log(data);
                // $('#readit'+id).hide();
                
                if(data === '0' || data === 0){
                    alert('Something went wrong!');
                }else{
                    // var win = window.open(redirect, '_blank');
                    // if (win) {
                    //     win.focus();
                    // }
                    // $('#friends-count').text(data.total_friends);
                }
            },
        });
    });
    
    $(".webinarsCardClick").click(function(){
        $(this).toggleClass("webinarsCardExpand");
    });


    $('.readAllNoti').click(function () {
        
        // var id = $(this).data("id");
        var id = $('#readAllNotiValue').val();
        $('#readAllNotiValue').val('');
        
        // $('.readAllNoti').data("id");    
            // alert(id);
            var url = "{{route('frontend.resetNotificationCount')}}";
            $.ajax({
                url: url,
                type: "POST",
                data: 'id='+id,
                success: function (data) {
                    // console.log(data);
                    // $('#readit'+id).hide();
                    
                    if(data === '0' || data === 0){
                        // alert('Something went wrong!');
                    }else{
                        // alert(1);
                        // var win = window.open(redirect, '_blank');
                        // if (win) {
                        //     win.focus();
                        // }
                        // $('#friends-count').text(data.total_friends);
                        $('.noticount').hide();
                    }
                },
            });
        

        return false;
    });
    
    $('#close-btn').click(function () {
        var id = 1;
        // alert(id);
        var url = "{{route('frontend.hidePopups')}}";
        $.ajax({
            url: url,
            type: "GET",
            data: 'id='+id,
            success: function (data) {
                $('#all-btns').hide();
                Tawk_API.hideWidget();
            },
        });
        return false;
    });
    
    @if($header_data['hide_popups'] == 1)
    window.Tawk_API.onLoad = function(){
        // $('#all-btns').hide();
        Tawk_API.hideWidget();
    };
        
    @endif
});
</script>
<script>
    $(document).ready(function() {
        $('.charcount').css('display','none');
        
        if( $(this).is(':checked') ){
            $('textarea[name="note"]').css('display','block');
            $('.charcount').css('display','block');
        }else {
            $('textarea[name="note"]').css('display','none');
            $('.charcount').css('display','none');
        }
        $("#is_note").click( function(){
            if( $(this).is(':checked') ){
                $('textarea[name="note"]').css('display','block');
                $('.charcount').css('display','block');
            }else {
                $('textarea[name="note"]').css('display','none');
                $('.charcount').css('display','none');
            }
        });
        
        @if(session()->has('calc_title'))
            $('#save_title').val("{{ session()->get('calc_title') }}");
        @endif

        @if(isset($is_note) && $is_note == 1 || isset($form_data['is_note']) && $form_data['is_note'] == 1)
            $('textarea[name="note"]').css('display','block');
            $('.charcount').css('display','block');
        @else
            $('textarea[name="note"]').css('display','none');
            $('.charcount').css('display','none');
        @endif
    
    });
</script>
@if($header_data['hide_popups'] != 1)
<div id="all-btns"  style="z-index:99990000">
    <div id="close-btn" style="position:fixed;width:25px; height:25px; bottom:170px; right:45px; background-color: rgba(0, 0, 0, 0.103); font-weight: 100; color:#FFF; border-radius:50px; text-align:center; font-size:16px; z-index:9999000; cursor: pointer;">
        <i class="fa fa-times bg-red text-danger p-1"></i>
    </div>
    <a href="https://api.whatsapp.com/send?phone=919883818627&text=Hi%20there%21" class="tooltip1" style="position:fixed;width:60px; height:60px; bottom:100px; right:25px; background-color:#25d366; color:#FFF; border-radius:50px; text-align:center; font-size:30px; box-shadow: 2px 2px 3px #999; z-index:9999000;" target="_blank" title="">
        <!--<span class="tooltiptext">For the best format, copy text and share on WhatsApp separately</span>-->
        <i class="fab fa-whatsapp" style="margin-top:18px;"></i>
    </a>
</div>
@endif

</body>
</html>
