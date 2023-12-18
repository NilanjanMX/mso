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
    .mb-30 {
        margin-bottom:30px;
    }
    .visp {
        right: -30px;
        top: 2334px;
    }
    .vidpos02 {
    left: -52px;
    top: 197px;
    width: 100px;
    }
.vidpos03 {
    right: -46px;
    top: 546px;
}
.vidpos04 {
    left: -58px;
    top: 2691px;
}
.vidpos05 {
    right: -101px;
    top: 2940px;
    width: 142px;
}
.vidpos06 {
    left: -42px;
    top: 3042px;
    width: 93px;
    }
</style>
<img class="kuchi" style="left:-96px; top:0; z-index:999; opacity:0.3; display:none;" src="{{asset('')}}img/membership_videos.png" alt="" />
<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />
<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Marketing Videos for your Business</h2>
                    <p>Download short videos with your personal branding for social media marketing and client engagement.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <!--<img class="img-fluid" src="{{asset('')}}img/marketing_vid_banner.png" alt="" />-->
                    <img class="img-fluid" src="{{asset('')}}img/shortvideobanner.png" alt="" />
                </div>
            </div>
        </div>
    </div>
    
                <div class="col-md-12">
                    @if (isset($isSearchBarShow) && $isSearchBarShow == true)
                    <form action="" method="get">
                        <div class="input-group searchItemField">
                            <input type="text" name="tag" class="form-control" placeholder="Looking for something? Type here for searching.." value="{{old('tag',$tag)}}">
                            <button type="submit" name="" class="btn btnSearchItemGlass"><img class="img-fluid" src="{{asset('')}}img/searchItemGlass.png" alt="" /></button>
                        </div>
                    </form>
                    @endif
                </div>
</div>
<!--<div class="banner">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-md-12 text-center">-->
<!--                <h2 class="page-title">MARKETING VIDEO</h2>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <a href="#" class="btn-chat">Chat With Us</a>-->
<!--</div>-->
<section class="main-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="category-box categoryList">
                        <ul class="cate-list">
                            <li class="@if(empty($slug)) ? '' : active @endif"><a href="{{url('marketing-video')}}">All <span>({{$totalPosts}})</span></a></li> 

                            @foreach($categories as $category)
                                <li class="@if($slug == $category->slug) ? '' : active @endif"><a href="{{url('marketing-video/category')}}/{{$category->slug}}?tag={{$tag}}">{{$category->name}} ({{count($category->marketingvideos)}})</a></li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row text-center market-video">

                        @if(isset($posts))
                            @php
                                $count = 0;
                            @endphp
                            @foreach($posts as $post)
                            @php
                                $count++;
                            @endphp
                            <div class="col-md-4 mb-30">
                                <div class="rt-pnl">
                                        <img src="{{asset('uploads/marketingvideo')}}/{{$post->cover_image}}" class="img-fluid vidcover" alt="">
                                        
                                        <div class="rec-book-text download-tools">
                                            <h5>{{substr(strip_tags($post->title), 0, 25)}}{{strlen(strip_tags($post->title)) > 25 ? " ..." : "" }}</h5>
                                            
                                            <div class="market-video-text">
                                                
                                                    {!! $post->content !!}
                                               
                                            </div>
                                            <div class="playdownlaodbutton">
                                                <a href="{{url('marketing-video')}}/{{$post->slug}}">
                                                <button class="btn banner-btn mt-3 w-auto mr-0" style="padding: 12px 43px !important;">Play</button>
                                                </a>

                                                <!-- <a href="{{(isset($post['video']) && $post['video']!='')?asset('uploads/marketingvideo/video/'.$post['video']):''}}" class="btn btn-success btn-round" target="_blank" download><i class="fa fa-download"></i> Download</a> -->
                                            
                                                @if (Auth::check()) 
                                                    @if(isset($post['video']) && $post['video']!='')
                                                        @if($permission['is_download'])
                                                             <a href="{{url('marketingvideo-download-new')}}/{{$post->slug}}/{{$post['video']}}" class="btn banner-btn whitebg mt-3 w-auto mr-0" target="_blank"> Download  </a>
                                                        @else 
                                                             <a href="javascript:void(0);" onclick="openDownloadPermissionModal();" class="btn banner-btn whitebg mt-3 w-auto mr-0"> Download </a>
                                                        @endif
                                                       
                                                            <!--<img class="img-fluid" src="{{asset('')}}img/downarrow.png" alt="" />-->
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(isset($post['video']) && $post['video']!='')
                                                        <a href="{{url('login')}}">
                                                            <button class="btn banner-btn whitebg mt-3 w-auto mr-0">Download 
                                                                <!--<img class="img-fluid" src="{{asset('')}}img/downarrow.png" alt="" />-->
                                                            </button>
                                                        </a>
                                                    @endif

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12 mt-4 bannerpremioum">
                                <nav aria-label="Page navigation example">
                                    {{$posts->links()}}
                                </nav>
                            </div>
                        @endif

                        @if($count < 1)
                            <div class="col-12">
                                <h2>No Marketing Video Found!</h2>
                            </div> 
                        @endif

                        

                        
                    </div>
                </div>

                
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />-->
        <!--</div>-->
    
    
        <!--<div class="newsletter">-->
        <!--    <div class="container">-->
        <!--        <form action="#">-->
        <!--            <div class="newsletterblock">-->
        <!--                <h3>Don’t miss our weekly updates about Mutual Fund investment information</h3>-->
        <!--                <div class="newsinputs">-->
        <!--                    <input type="text" placeholder="Enter your email address">-->
        <!--                    <input type="submit" value="Subscribe">-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </form>-->
        <!--    </div>-->
        <!--</div>-->
    </section>

    @include('frontend.calculators.modal')

@endsection
@section("js_after")
<script>
  $(document).ready(function() {
      $(this).bind("contextmenu", function(e) {
          e.preventDefault();
      });
  });
 </script>
  @endsection
