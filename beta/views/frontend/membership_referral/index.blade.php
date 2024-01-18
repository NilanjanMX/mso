@extends('layouts.frontend')
@section('js_after')
<script>
    
</script>
@endsection
@section('content')
<style type="text/css">
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
        left: -20px;
        top: 187px;
        width: 100px;
        }
    .vidpos04 {
        left: -53px;
        top: 580px;
    }
    .vidpos03 {
        right: 0;
        left: -30px;
        top: 1000px;
        width: 130px;
    }
    .vidpos05 {
        right: -65px;
        top: 1000px;
        width: 130px;
    }
    .vidpos06 {
        right: -65px;
        top: 530px;
        width: 130px;
    }
    .visp {
        right: 0px;
        top: 600px;
        width: 560px;
    }
    .visp3 {
        left: 0px;
        top: 420px;
        width: 250px;
        opacity: 0.6;
    }
    .orderTable table thead tr th, 
    .orderTable table tbody tr td {
        padding: 4px 9px;
    }
</style>
<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />
<img class="kuchi visp3" style="" src="{{asset('')}}img/videopageart3.png" alt="" />
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />-->
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />-->

<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item shoppingCartBannaer">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Hi First Name</h2>
                    <p>Your profile page, subscription details, membership points, order history, and more.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/ProfileBanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div>

<section class="main-sec">
    <div class="container">
        <div class="row">
            <div class="colProfileLeft"></div>
            <div class="colProfileRight">
                <div class="userManagementRight">
                    <h3 class="userHeadding">Refer a friend</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="colProfileLeft">
                @include('frontend.account.left_menu')
            </div>

            <div class="colProfileRight">
                <div class="userManagementRight">  
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
    
    
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif   
                    <div class="stage">
                        <div class="userManagementTop">
                            <form action="" method="get" class="userManagementSearch">
                                <!-- <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by name" name="search_text" value="{{$search_text}}">
                                        <div class="input-group-append">
                                            <button class="btn btn-search" type="submit">
                                                <img src="{{asset('f/images/serchIconUser.png')}}" class="img-fluid serchIcon" alt="">
                                            </button>  
                                        </div>
                                </div> -->
                            </form>
                            <div class="userAddUpgrade">
                                <a href="{{route('account.membershipReferralAdd')}}" class="addUserBtn">
                                    <div class="addUserBtnImg">
                                        <img src="{{asset('f/images/img/addUserIcon.png')}}" class="img-fluid addUserIcon" alt="">
                                    </div>
                                    <div class="addUserBtnText">Add New</div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="">
                                    <div class="">
                                        <div class="table-responsive orderTable subscriptionTable">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th ><span>SN</span></th>
                                                        <th ><span>Name</span></th>
                                                        <th ><span>Email</span></th>
                                                        <th ><span>Phone Number</span></th>
                                                        <th><span>Link</span></th>
                                                        <th><span>Subscribed</span></th>
                                                        <th><span>Created Date</span></th>
                                                        <th><span>Valid Till Date</span></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
    
                                                <tbody>
                                                    @if(count($list))
                                                        @foreach($list as $key => $result)
                                                            <tr class="order">
                                                                <td>
                                                                    <a href="#">#{{$key+1}}</a>
                                                                </td>
                                                                <td>
                                                                    {{$result->name}}
                                                                </td>
                                                                <td>
                                                                    {{$result->email}}
                                                                </td>
                                                                <td>
                                                                    {{$result->phone_number}}
                                                                </td>
                                                                <td>
                                                                    @if(strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($result->expire_at))) )
                                                                    <div class="d-flex justify-content-center align-items-center">
                                                                    <a href="javascript:void(0);" class="subscriptionView text-nowrap" onclick="copyLinkFun('{{$result->id}}')">Copy Link</a>
                                                                    <span id="link_{{$result->id}}" style="font-size: 0px;">{{url('/')}}/membership/{{$result->link}}</span>
                                                                    </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if(strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($result->expire_at))) )
                                                                        @if($result->is_used)
                                                                            Yes
                                                                        @else
                                                                            No
                                                                        @endif
                                                                    @else
                                                                        Link Expired
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                   {{date('d-m-Y', strtotime($result->created_at))}}
                                                                </td>
                                                                <td class="text-nowrap">
                                                                    {{date('d-m-Y', strtotime($result->expire_at))}}
                                                                 </td>
                                                                <td class="text-nowrap">
                                                                    <a href="{{route('account.membershipReferralEdit',['id'=>$result->id])}}" class="subscriptionView">Edit</a>&nbsp;&nbsp;
                                                                    <a href="{{route('account.membershipReferralDelete',['id'=>$result->id])}}"  onclick="return confirm('Are you sure?')"  class="subscriptionView">Delete</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="order">
                                                            <td colspan="5" style="text-align: center;">
                                                                No data found
                                                            </td>
                                                        </tr>

                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function copyLinkFun(id){
        var range = document.createRange();
        range.selectNode(document.getElementById('link_'+id));
        window.getSelection().removeAllRanges(); // clear current selection
        window.getSelection().addRange(range); // to select text
        document.execCommand("copy");
        window.getSelection().removeAllRanges();// to deselect
        
        alert("Link copied successfully.");
    }
</script>

@endsection
