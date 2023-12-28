@php
    $site_logo = \App\Models\Option::where('option_name','site_logo')->first();
    $contact_address = \App\Models\Option::where('option_name','contact_address')->first();
    $contact_phone = \App\Models\Option::where('option_name','contact_phone')->first();
    $contact_email = \App\Models\Option::where('option_name','contact_email')->first();
    $site_title = \App\Models\Option::where('option_name','site_title')->first();
@endphp
        <!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('')}}/f/images/favicon.png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{isset($site_title['option_value'])?$site_title['option_value']:'Master Stroke'}}</title>

    @yield('css_before')
    <link rel="stylesheet" href="{{url('')}}/f/css/bootstrap.min.css">
    <link href="{{url('')}}/f/css/owl.carousel.min.css" rel="stylesheet">
    <link href="{{url('')}}/f/css/owl.theme.default.min.css" rel="stylesheet">
    <link href="{{url('')}}/f/css/prettyPhoto.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{url('')}}/f/css/style.css">
    <link rel="stylesheet" href="{{url('')}}/f/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,500,300,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@yield('css_after')
<!-- Scripts -->
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
</head>

<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<header>
    <div class="head-top">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="logo">
                        <a href="{{url('/')}}">
                            @if(isset($site_logo))
                                <img class="img-fluid" src="{{asset('uploads/logo/'.$site_logo['option_value'])}}" alt="{{isset($site_title['option_value'])?$site_title['option_value']:'Master Stroke'}}" />
                            @else
                                <img class="img-fluid" src="{{url('')}}/f/images/logo.png" alt="" />
                            @endif
                        </a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="top-rt-prt">
                        <a href="{{route('frontend.cart')}}"><i class="fas fa-cart-plus"></i></a>
                        @if (Auth::check())
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="far fa-user"></i> My Account</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('account.profile')}}">My profile</a></li>
                                    <li><a class="dropdown-item" href="{{route('account.display-settings')}}">General Settings</a></li>
                                    <li><a class="dropdown-item" href="{{route('account.subscription.index')}}">Subscription</a></li>
                                    <li><a class="dropdown-item" href="{{url('logout')}}">Logout</a></li>
                                </ul>
                            </div>
                        @else
                            <a href="{{url('login')}}" class="btn btn-login">Login</a>
                        @endif

                    </div>
                    <div class="top-menu d-md-block d-none">
                        <nav class="navbar navbar-expand-md navbar-light navigation">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto" id="normal-menu">
                                    <li><a href="{{url('/')}}">Home</a></li>
                                    <li><a href="{{route('frontend.aboutUs')}}">About</a></li>
                                    <li><a href="{{route('frontend.disclaimers')}}">Disclaimers</a></li>
                                    <li><a href="{{route('frontend.contactUs')}}">Contact</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="head-bttm">
        <div class="container">
            <div class="top-contact">
                <img src="{{url('')}}/f/images/call-ico.png" alt="">
                <p>FREE HELP LINE <a href="tel:{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}">{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}</a></p>
            </div>
            <div class="main-menu">
                <nav class="navbar navbar-expand-md navbar-light navigation">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto" id="normal-menu">
                            <li class="dropdown active"><a href="#" class="dropdown-toggle" data-toggle="dropdown">MEMBERSHIP</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>
                                    <li><a class="dropdown-item" href="{{route('frontend.lumpSumIndex')}}">Client Proposals</a></li>
                                    <li><a class="dropdown-item" href="{{url('premium-banners')}}">Marketing Banners</a></li>
                                    <li><a class="dropdown-item" href="{{url('marketing-video')}}">Marketing Videos</a></li>
                                    <li><a class="dropdown-item" href="{{route('frontend.paid-videos')}}">Training Videos</a></li>
                                    <li><a class="dropdown-item" href="{{route('frontend.BrokerageIndex')}}">Trail Calculator</a></li>
                                    <li><a class="dropdown-item" href="{{url('membership')}}">Become a member</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a class="dropdown-item" href="{{url('marketting-free-videos')}}">Short Videos</a>
                                <!-- <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('frontend.paid-videos')}}">Paid Videos</a></li>
                                    <li><a class="dropdown-item" href="{{url('marketting-free-videos')}}">Free Videos</a></li>
                                </ul> -->
                            </li>
                            <li><a href="{{url('ifa-tools')}}">IFA TOOLS</a></li>
                            <li><a href="{{route('frontend.mockExamIndex')}}">MOCK EXAMS</a></li>
                            <li><a href="{{route('frontend.success-stories')}}">SUCCESS STORIES</a></li>

                            <li><a href="{{route('frontend.coaching')}}">COACHING</a></li>
                            <li><a href="#">WEBSITE TREE</a></li>
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

<footer>
    <div class="footer-top">
        <div class="container">
            <ul class="footer-menu">
                <li><a href="{{url('/')}}">Home</a></li>
                {{--<li><a href="{{route('frontend.sales-presenters')}}">Sales Presenters</a></li>--}}
               {{-- <li><a href="{{route('frontend.lumpSumIndex')}}">Calculators</a></li>--}}
                <li><a href="{{route('frontend.membership')}}">Free Trial Period</a></li>
                <li><a href="{{route('frontend.ifa-business-faqs')}}">Business FAQs</a></li>
                {{--<li><a href="{{route('frontend.BrokerageIndex')}}">Brokerage Calculator</a></li>--}}
               {{-- <li><a href="{{route('frontend.paid-videos')}}">Training Videos</a></li>--}}
                <li><a href="{{route('frontend.free-videos')}}">Short Videos</a></li>
                <li><a href="{{route('frontend.success-stories')}}">Success Stories</a></li>
                <li><a href="{{route('frontend.article')}}">Articles</a></li>
                <li><a href="{{route('frontend.blog')}}">Blogs</a></li>
                <li><a href="{{route('frontend.ifa-tools')}}">IFA Tools</a></li>
                <li><a href="{{route('frontend.downloadtool')}}">Download Tools</a></li>
                <li><a href="{{route('frontend.book-recomendation-for-ifas')}}">Book Recommendations</a></li>
                <li><a href="{{route('frontend.client-objection-handling')}}">Client Objection Handling</a></li>
                <li><a href="{{route('frontend.ifa-product-faqs')}}">Product FAQ</a></li>
                <li><a href="{{route('frontend.premade-sales-presenters')}}">Pre-Made Sales Presenters</a></li>
                <li><a href="{{route('frontend.stationary')}}">Store</a></li>
                <li><a href="{{route('frontend.gallery')}}">Gallery</a></li>
                <li><a href="{{route('frontend.products-suitability')}}">Products Suitability</a></li>
                <li><a href="{{route('frontend.ask-brijesh')}}">Ask Brijesh</a></li>
                <li><a href="{{route('frontend.write-a-testimonial')}}">Write Testimonial</a></li>
                <li><a href="{{route('frontend.whatsapp-broadcast')}}">Join Broadcast</a></li>
            </ul>
            <div class="footer-mid">
                <div class="f-logo">

                    <a href="{{url('/')}}">
                        @if(isset($site_logo))
                            <img class="img-fluid" src="{{asset('uploads/logo/'.$site_logo['option_value'])}}" alt="" />
                        @else
                            <img class="img-fluid" src="{{url('')}}/f/images/logo.png" alt="" />
                        @endif
                    </a>
                </div>
                <ul class="address-list">
                    <li><i class="fa fa-map-marker"></i> <p>{{isset($contact_address['option_value'])?$contact_address['option_value']:''}}</p></li>
                    <li><i class="fa fa-phone"></i> <a href="tel:{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}">{{isset($contact_phone['option_value'])?$contact_phone['option_value']:''}}</a></li>
                    <li><i class="fa fa-envelope"></i> <a href="mailto:{{isset($contact_email['option_value'])?$contact_email['option_value']:''}}">{{isset($contact_email['option_value'])?$contact_email['option_value']:''}}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bttm">
        <div class="container">
            <p class="copyright">© 2019 All Rights Reserved. Designed by <a target="_blank" href="https://1webstreet.com/" rel="nofollow">1webstreet</a></p>
        </div>
    </div>
</footer>

@yield('js_before')
<script src="{{url('')}}/f/js/jquery.min.js"></script>
<script src="{{url('')}}/f/js/jquery-migrate-1.3.0.js"></script>
<script src="{{url('')}}/f/js/popper.min.js"></script>
<script src="{{url('')}}/f/js/bootstrap.min.js"></script>
<script src="{{url('')}}/f/js/owl.carousel.min.js"></script>
<script src="{{url('')}}/f/js/stepper.js"></script>
<script src="{{url('')}}/f/js/jquery.prettyPhoto.js"></script>
<script src="{{url('')}}/f/js/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/share.js') }}"></script>
<!--Start of Tawk.to Script-->
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
<script src="{{url('')}}/f/js/main.js"></script>
@yield('js_after')
@include('frontend.calculators.suggested.script')
</body>
</html>
