@extends('layouts.frontend')

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
        top: 1089px;
        width: 150px;
    }
    .vidpos06 {
        right: -65px;
        top: 530px;
        width: 150px;
    }
    .visp {
        right: -30px;
        top: 520px;
        width: 660px;
    }
    .conferencesTable .table tr:hover {
        background-color: #468ff61c;
        transition: all 0.5s;
    }
</style>
<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />-->
<!--<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />-->
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />-->

<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item shoppingCartBannaer">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Create your own Custom MSO Triggers</h2>
                    <p>Serve your clients with precision. Set triggers and get reminders for profit booking, buying, selling , switch, etc., based on various parameters.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/tiggerBanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div>

<section class="main-sec bodyResponsive">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="category-box categoryList">
                        @include('frontend.trigger.common')
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="triggerSetting">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="triggerCountBox">
                                    <div class="triggerCountNo">{{$total_trigger_count-$total_trigger_used}}</div>
                                    <div class="triggerCountTitle">Total Triggers Left</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="triggerCountBox">
                                    <div class="triggerCountNo">{{$total_trigger_used}}</div>
                                    <div class="triggerCountTitle">Total Triggers Used</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="triggerCountBox">
                                    <div class="triggerCountNo">{{$total_trigger_count}}</div>
                                    <div class="triggerCountTitle">Total Triggers Available</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{route('frontend.trigger_setting_update')}}" method="post">
                        @csrf
                        <div class="triggerSetting">
                            <div class="form-group">
                                <label class="radioTitle">Set Notifications</label><br>
                                <div class="formCheeckAll">
                                    <div class="formCheeck" style="display: none;">
                                        <label class="displaySettionRadio">
                                            <input type="radio" name="notification" id="" value="Whatsapp" @if($notification =="Whatsapp") checked="" @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radioOption">Whatsapp</label>
                                    </div>
                                    <div class="formCheeck">
                                        <label class="displaySettionRadio">
                                            <input type="radio" name="notification" value="E-mail" @if($notification =="E-mail") checked="" @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radioOption">E-mail</label>
                                    </div>
                                    <div class="formCheeck">
                                        <label class="displaySettionRadio">
                                            <input type="radio" name="notification" id="" value="SMS" @if($notification =="SMS") checked="" @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radioOption">SMS</label>
                                    </div>
                                    <div class="formCheeck">
                                        <label class="displaySettionRadio">
                                            <input type="radio" name="notification" id="" value="Both" @if($notification =="Both") checked="" @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radioOption">Both</label>
                                    </div>
                                </div>               
                            </div>
                            
                            <div class="form-group">
                                <label class="radioTitle">Subscribe to MSO Triggers</label><br>
                                <div class="formCheeckAll">
                                    <div class="formCheeck">
                                        <label class="displaySettionRadio">
                                            <input type="radio" name="subscribe" id="" value="Yes" @if($subscribe =="Yes") checked="" @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radioOption">Yes</label>
                                    </div>
                                    <div class="formCheeck">
                                        <label class="displaySettionRadio">
                                            <input type="radio" name="subscribe" id="" value="No" @if($subscribe =="No") checked="" @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="radioOption">No</label>
                                    </div> 
                                </div>               
                            </div>
                        </div> 
                        
                        <div class="uploadedLogoBtn triggerSettingBtn">
                            <button type="submit" class="btn banner-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="https://masterstroke.5gsoftware.net/public/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>


@endsection
