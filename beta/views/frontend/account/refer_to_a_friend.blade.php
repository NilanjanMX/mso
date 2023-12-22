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
                    <h2 class="py-4">What Do You Get?</h2>
                    <p>You can redeem your Membership points for renewals, upgrades, future programs, store, etc. </p>
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
	            <div class="orderRight">
	                <h3 class="userHeadding">Membership Points</h3>
	            </div>
	        </div>
	    </div>
	    
        <div class="row">
            <div class="colProfileLeft">
                @include('frontend.account.left_menu')
            </div>

            <div class="colProfileRight"> 
                <div class="orderRight">
                    <div class="stage">
                        <div class="row">
                            <div class="col-lg-12">
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
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive orderTable membershipPointsTable">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th></th>
                                          <th>Transaction Description</th>
                                          <th>Membership Points</th>
                                          <th>Expire date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php if(count($user_list)){ ?>
                                          <?php foreach ($user_list as $key => $value) { ?>
                                              <?php if(!$value->is_paid){ ?>
                                                  <tr class="order">
                                                    <td><img src="{{asset('img/receivedPointsIcon.png')}}" class="img-fluid" alt=""></td>
                                                    <td>Received from {{$value->note}} <br>
                                                    <span>{{date('d-m-Y',strtotime($value->created_at))}}</span></td>
                                                    <td>+{{custome_money_format((int)$value->referral_amount)}} <br>
                                                    <span>CB: {{$value->total_amount}}</span></td>
                                                    <td>{{$value->expire_date}}</td>
                                                  </tr>
                                              <?php } else { ?>
                                                  <tr class="order">
                                                    <td><img src="{{asset('img/paidPointsIcon.png')}}" class="img-fluid" alt=""></td>
                                                    <td>Paid for {{$value->note}} <br>
                                                    <span>{{date('d-m-Y',strtotime($value->created_at))}}</span></td>
                                                    <td>-{{custome_money_format((int)$value->referral_amount)}} <br>
                                                    <span>CB: {{$value->total_amount}}</span></td>
                                                    <td></td>
                                                  </tr>
                                              <?php } ?>
                                          <?php } ?>
                                        <?php }else{ ?>
    
                                        <?php } ?>
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="membershipPointsInfo">
                            <div class="referanceTitle">Points earned: <span>{{$earned}}</span></div>
                            <div class="referanceTitle">Points claimed:  <span>{{$claimed}}</span></div>
                            <div class="referanceTitle" style="font-weight: 700;">Balance Points: <span><?php echo custome_money_format((int)$total);?></span></div>
                            <!-- <form action="" method="get" class="userManagementSearch" style="width: calc(100% - 150px);">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by name" name="search_text" value="{{$search_text}}">
                                        <div class="input-group-append">
                                            <button class="btn btn-search" type="submit">
                                                <img src="{{asset('f/images/img/serchIcon.png')}}" class="img-fluid serchIcon" alt="">
                                            </button>  
                                        </div>
                                </div>
                                <div class="userManagementCount invoiceListTitle">{{count($user_list)}} of {{$user_detail->number_user}} user</div>
                            </form> -->
                             <!--<a href="javascript:void(0);"  onclick="openShareOption();" class="btn btn-primary d-block-sm ml-auto">Refer to a friend</a>-->
                        </div>
                        
                    </div>
                </div>
                                 
                    

            </div>
        </div>
        
        <div class="groupExp membershipRefer">
            <div class="row">
                <div class="col-md-5">
                    <h3 class="subHeadding pt-0" style="color:#000; font-weight:700;text-align: left;">Refer to a friend and Earn Benefits</h3>
                    <p>What Do You Get? 
                        
                    </p>
                    <ul class="ml-3" style="list-style: unset;">
                        <li><p class="mb-0">10% Membership Points for MSO Basic/Premium Members</p></li>
                    </ul>
                    <p>What Your Friend Gets?
                    </p>
                    <ul class="ml-3" style="list-style: unset;">
                        <li><p class="mb-0">10% Off on Masterstroke Subscription. </p></li>
                    </ul>
                    <a href="javascript:void(0);"  onclick="openShareOption();" class="btn banner-btn" style="margin-top: 30px; padding: 12px 26.3px !important;">Refer a Friend</a>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-6" style="align-self: center;text-align: right;">
                    <img class="img-fluid" src="{{asset('')}}img/membershipRefer.png" alt="" style="position: relative;z-index: 9;">
                </div>
            </div>
        </div>
    </div>
    <!--<div class="btm-shape-prt">-->
    <!--    <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />-->
    <!--</div>-->
</section>
<div id="shareOptionModal" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header text-center">
            <h3>Refer to a friend</h3>
          </div>
          <div class="modal-body">
            <div type="text" name="referral_code_copy" value="" id="referral_code_copy" style="font-size: 0px;position: absolute;">
                Hi,<br><br>
                I highly recommend you to become an exclusive member of MasterStroke Online (MSO).<br><br>
                MSO is a digital platform for mutual fund distributors and other investment professionals. They offer sales and branding tools to help us grow in our business at a rapid speed. I personally use it frequently and have greatly benefited.<br><br>
                Below is the referral link through which you can subscribe to their services and also get an instant cash discount of 10%.<br><br>
                Reference Link : {{url('/membership')}}/{{$user_detail->referral_code}}<br><br>
                In case of any query or doubt, please feel free to call MSO Help Line at 9883818627 or write them at info@masterstrokeonline.com<br><br>

                Best Regards. <br>


            </div>
            <div class="row">
              <div class="col-sm-4 text-center">
                <button class="btn btn-success" onclick="clickShareOption(1);">
                  GMAIL
                </button>
              </div>
              <div class="col-sm-4 text-center">
                <button class="btn btn-success" onclick="clickShareOption(2);">
                  WhatsApp
                </button>
              </div>
              <div class="col-sm-4 text-center">
                <button class="btn btn-success" onclick="clickShareOption(3);">
                  Copy Link
                </button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
</div>
<style>
.invalid-feedback {
    display:block;
}
</style>

<script type="text/javascript">
    var base_url = "{{url('/membership')}}/{{$user_detail->referral_code}}";

    function openShareOption(){
        $('#shareOptionModal').modal('show');
    }

    function clickShareOption(type){
        // base_url = document.getElementById('referral_code_copy').innerHTML;
        if(type == 1){
            var body = "Hi,%0D%0A%0D%0A";
            body = body+"I highly recommend you to become an exclusive member of MasterStroke Online (MSO).%0D%0A%0D%0A";
            body = body+"MSO is a digital platform for mutual fund distributors and other investment professionals. They offer sales and branding tools to help us grow in our business at a rapid speed. I personally use it frequently and have greatly benefited.%0D%0A%0D%0A";
            body = body+"Below is the referral link through which you can subscribe to their services and also get an instant cash discount of 10 percent.%0D%0A%0D%0A";
            body = body+"Reference Link : "+base_url+"%0D%0A%0D%0A";
            body =  body+ "In case of any query or doubt, please feel free to call MSO Help Line at 9883818627 or write them at info@masterstrokeonline.com%0D%0A%0D%0A";
            body =  body+ "Best Regards.%0D%0A%0D%0A";
            var to = "";
            var subject = "Join MasterStroke Online !!";
            window.open("https://mail.google.com/mail/?view=cm&fs=1&su="+subject+"&to="+to+"&body="+body);
        }else if(type == 2){
            var body = "Hi,%0D%0A%0D%0A";
            body = body+"I highly recommend you to become an exclusive member of MasterStroke Online (MSO).%0D%0A%0D%0A";
            body = body+"MSO is a digital platform for mutual fund distributors and other investment professionals. They offer sales and branding tools to help us grow in our business at a rapid speed. I personally use it frequently and have greatly benefited.%0D%0A%0D%0A";
            body = body+"Below is the referral link through which you can subscribe to their services and also get an instant cash discount of 10 percent.%0D%0A%0D%0A";
            body = body+"Reference Link : "+base_url+"%0D%0A%0D%0A";
            body =  body+ "In case of any query or doubt, please feel free to call MSO Help Line at 9883818627 or write them at info@masterstrokeonline.com%0D%0A%0D%0A";
            body =  body+ "Best Regards.%0D%0A%0D%0A";
            window.open('https://web.whatsapp.com/send?text='+body);  
        }else{
            var range = document.createRange();
            range.selectNode(document.getElementById('referral_code_copy'));
            window.getSelection().removeAllRanges(); // clear current selection
            window.getSelection().addRange(range); // to select text
            document.execCommand("copy");
            window.getSelection().removeAllRanges();// to deselect
        }
    }
</script>

@endsection
