@extends('layouts.frontend')

@section('content')
<style>
    .top-tab {
        margin-bottom: 61px;
    }
    /*.newsletter {*/
    /*    margin-top: 104px;*/
    /*    margin-bottom: -24px;*/
    /*}*/
    .stationery-btn .banner-btn {
        padding: 10px 15px !important;
    }
    

    .vidpos02 {
    left: -43px;
    top: 187px;
    width: 100px;
    }
    .vidpos04 {
        left: -53px;
        top: 1375px;
    }
    .vidpos03 {
        right: 0;
        left: -58px;
        top: 2652px;
        width: 100px;
    }
    .vidpos05 {
        right: -108px;
        top: 1400px;
        width: 150px;
    }
    .vidpos06 {
        right: -65px;
        top: 530px;
        width: 150px;
    }
    .visp {
        right: -30px;
        top: 935px;
        width: 675px;
    }
    .visp2 {
        left: -30px;
        top: 533px;
        width: 575px;
        opacity: 0.7;
    }
</style>
<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />
<img class="kuchi visp2" style="" src="{{asset('')}}img/videopageart2.png" alt="" />
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />-->
<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />
<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Marketing Banners for your Business</h2>
                    <p>Download banners with your personal branding for social media marketing and client engagement.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <!--<img class="img-fluid" src="{{asset('')}}img/marketing_vid_banner.png" alt="" />-->
                    <img class="img-fluid" src="{{asset('')}}img/marketingbanner.png" alt="" />
                </div>
            </div>
        </div>
    </div>
    
                <div class="col-md-12">
                    {{-- // nila  --}}
                    @if (isset($isSearchBarShow) && $isSearchBarShow == true)
                    <form action="" method="get">
                        <div class="input-group searchItemField">
                            <input type="text" name="tag" class="form-control" placeholder="Looking for something? Type here for searching.." value="{{$tag}}">
                            <button type="submit" name="" class="btn btnSearchItemGlass"><img class="img-fluid" src="{{asset('')}}img/searchItemGlass.png" alt="" /></button>
                        </div>
                    </form>
                    @endif
                    {{-- // nila  --}}
                </div>
</div>

<section class="main-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="category-box categoryList">
                        <ul class="cate-list">
                            <li class="@if(empty($slug)) ? '' : active @endif"><a href="{{url('premium-banners')}}">All <span>({{$totalPosts}})</span></a></li> 

                            @foreach($categories as $category)
                              @if(count($category->premiumbanners) > 0)
                                <li class="@if($slug == $category->slug) ? '' : active @endif"><a href="{{url('premium-banners/category')}}/{{$category->slug}}?tag={{$tag}}">{{$category->name}} ({{count($category->premiumbanners)}})</a></li>
                              @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="famousQuotesDetailsAll premiumBannersAll">
                        <div class="row text-center">
                            @if(isset($posts))
                                @php
                                $count = 0;
                                @endphp
                                @foreach($posts as $post)
                                @php
                                $count++;
                                $random_number = rand(99,100000);
                                @endphp
                                <div class="col-md-3 famousQuotesDetailsCol">
                                    <div class="stationery-box famousQuotesBox famousQuotesDetails">
                                        <div class="famousQuotesDetailsImg">
                                            <img class="img-fluid vidcover" src="{{asset('uploads/premiumbanner')}}/{{$post->cover_image}}" alt="">
                                            <div class="water-mark-sale">
                                                MasterStroke
                                            </div>
                                        </div>
                                            
                                        <div class="stationery-btn">
                                            @if (Auth::check()) 
                                                
                                                <a href="{{url('watermark-image-test')}}/{{$post->premium_banner}}/{{$random_number}}" class="w-100">
                                                    <button class="btn banner-btn whitebg">Download</button>
                                                </a>
        
                                                <input type="hidden" id="primium-banner{{$count}}" value="{{$post->premium_banner}}">
                                                <input type="hidden" id="primium-banner-link{{$count}}" value="{{$post->premium_banner}}">
        
                                                <!--<button class="btn btn-primary btn-round mt-3" onclick="openFacebook('{{$count}}');">-->
                                                <!--    F-->
                                                <!--</button>-->
                                                <!--<button class="btn btn-primary btn-round mt-3" onclick="openWhatapp('{{$count}}');">-->
                                                <!--    W-->
                                                <!--</button>-->
                                            @else
                                                <!--<a href="{{url('login')}}">
                                                    <button class="btn btn-primary  btn-round mt-3"><i class="fab fa-facebook"></i></button>
                                                </a>-->
                                                <a href="{{url('login')}}" class="w-100">
                                                    <button class="btn banner-btn whitebg">Download</button>
                                                </a>
                                                
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                <div class="col-md-12 bannerpremioum">
                                    <nav aria-label="Page navigation example">
                                        {{$posts->links()}}
                                    </nav>
                                </div>
                            @endif
                            @if($count < 1)
                                <div class="col-12">
                                    <h2>No Premium Banner Found!</h2>
                                </div> 
                            @endif
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />-->
        <!--</div>-->
    </section>

@endsection

@section("js_after")
<script>
  $(document).ready(function() {
      $(this).bind("contextmenu", function(e) {
          e.preventDefault();
      });

        /*$(".whatsapp-share").click(function(){

            image_id = "#primium-banner"+$(this).attr("data-count");

           var primiumBanner = $(image_id).val();
            
           var base_url = window.location.origin;
            $.ajax({
                
                //url: 'http://172.16.10.42/masterstrokeonline.myvtd.site/public'+'/whatappshare/'+primiumBanner,
                url: base_url+'/whatappshare/'+primiumBanner,
                type: 'get',
                dataType: 'html',
                success: function(response){
                    //console.log(response);
                    var imageURL = response;
                    //window.location.href = 'https://api.whatsapp.com/?text='+encodeURIComponent(imageURL);
                    window.location = 'https://wa.me/?text='+encodeURIComponent(imageURL);

                }
            });

            return false;

        });*/
        
        // Facebook share
        
        $(".facebook-share").click(function(){

            image_id = "#primium-banner"+$(this).attr("data-count");

           var primiumBanner = $(image_id).val();
            
           

            return false;

        });

        

    }); 

    function openWhatapp(index){
            var primiumBanner = $("#primium-banner-link"+index).val();
            console.log(primiumBanner);
            var base_url = "{{url('whatappshare')}}";
            $.ajax({
                url: base_url+'/'+primiumBanner,
                type: 'get',
                dataType: 'html',
                success: function(response){
                    u=response;
                     // t=document.title;
                    //t=TheImg.getAttribute('alt');
                    t='';
                    window.open('https://web.whatsapp.com/send?text='+u);return false;

                }
            });
        }

        function openFacebook(index){

            var primiumBanner = $("#primium-banner-link"+index).val();
            var base_url = "{{url('whatappshare')}}";
            $.ajax({
                url: base_url+'/'+primiumBanner,
                type: 'get',
                dataType: 'html',
                success: function(response){
                    u=response;
                     // t=document.title;
                    //t=TheImg.getAttribute('alt');
                    t='';
                    window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;

                }
            });
        }
</script>

@endsection
