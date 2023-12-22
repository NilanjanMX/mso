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
	            <div class="orderRight">
	                <h3 class="userHeadding">Personal Information</h3>
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
                                <div class="">
                                    <div class="personalInfoForm">
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
                                    <form action="{{route('account.profile.update',['id'=> $user->id])}}" method="post" name="updateprofile" enctype="multipart/form-data">
                                    @csrf
                                            <div class="form-row">
                                                <!--<div class="col-lg-12">-->
                                                <!--    <h4>Personal Information</h4>-->
                                                <!--</div>-->
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>First Name</label>-->
                                                        <input type="text" name="first_name" class="form-control" value="{{old('first_name',isset($user->first_name)?$user->first_name:'')}}" placeholder="First Name">
                                                        @if ($errors->has('first_name'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('first_name') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>  
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>Last Name</label>-->
                                                        <input type="text" name="last_name" class="form-control" value="{{old('last_name',isset($user->last_name)?$user->last_name:'')}}" placeholder="Last Name">
                                                        @if ($errors->has('last_name'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('last_name') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>  
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>Phone No</label>-->
                                                        <input type="text" name="phone_no" class="form-control" value="{{old('phone_no',isset($user->phone_no)?$user->phone_no:'')}}"  onkeypress="return isNumber(event)"  maxlength="10" placeholder="Phone Number">
                                                        @if ($errors->has('phone_no'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('phone_no') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>Email address</label>-->
                                                        <input type="email" name="email" class="form-control" value="{{old('email',isset($user->email)?$user->email:'')}}" disabled="" placeholder="Email Address">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>City</label>-->
                                                        <input type="text" name="city" class="form-control" value="{{old('city',isset($user->city)?$user->city:'')}}" placeholder="City">
                                                        @if ($errors->has('city'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('city') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>City</label>-->
                                                        <input type="text" name="gst_number" class="form-control" value="{{old('gst_number',isset($user->gst_number)?$user->gst_number:'')}}" placeholder="GST Number" maxlength="15">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <!--<label>Company Name</label>-->
                                                        <input type="text" name="company_name" class="form-control" value="{{old('company_name',isset($user->company_name)?$user->company_name:'')}}" placeholder="Company Name">
                                                        @if ($errors->has('company_name'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('company_name') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- <div class="col-lg-6">
                                                     <div class="form-group">
                                                        <label>Logo </label>
                                                        <input type="file" name="company_logo" class="form-control" >
                                                        @if ($errors->has('company_logo'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('company_logo') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div> -->
    
                                                <div class="col-lg-12">
                                                     <div class="form-group">
                                                        <!--<label>Logo </label>-->
                                                        <!--<input type="file" id="upload_image1" class="form-control" name="company_logo_old"  placeholder="Logo"/>-->
                                                        <div class="form-control uploadField">
                                                            <input type="file" id="upload_image1" name="company_logo_old" hidden="">
                                                            <label class="uploadFile" for="upload_image1">
                                                              <span>Logo</span>
                                                              <a class="btn banner-btn whitebg">Upload File</a>
                                                            </label>
                                                        </div>
                                                        
                                                        <div id="uploaded_image"></div>
                                                        
                                                        @if ($errors->has('company_logo'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('company_logo') }}</strong>
                                                            </div>
                                                        @endif
                                                        <div class="note-for-logo"><span style="font-weight:700;">Note:</span> Please check before upload logo <a href="#" data-toggle="modal" data-target="#myModal" style="color: #458FF6;">Click Here</a> </div>
                                                        <div class="note-for-logo mt-4">Pls upload 'png' transparant logo. If you don't have a logo, you can choose <a href="#" data-toggle="modal" data-target="#myModalLogos" style="color: #458FF6;">one here</a>. Get help from Masterstroke team. Call 9883818627</div>
                                                    </div>
                                                </div>
    
                                                @if(!empty($user->company_logo))
                                                <div class="col-lg-6">
                                                    <div class="uploadedLogoAll">
                                                        <img class="uploadedLogo" src="{{asset('uploads/logo/original')}}/{{$user->company_logo}}">
                                                        <a href="{{route('account.profile.remove-logo',['id'=> $user->id])}}" class="removeLogoIcon"><img class="img-fluid" src="{{asset('')}}img/removeLogo.png" alt="" /></a>
                                                    </div>
                                                    
                                                </div>
                                                @endif
                                                
                                                
                                                    <div class="uploadedLogoBtn ml-auto">
                                                        <button type="submit" name="update_profile" class="btn banner-btn">Update Profile</button>
                                                    </div>
                                                    
                                                @if(!empty($adminlogos))
                                                    <!--<div class="col-lg-12">-->
                                                         
                                                    <!--</div>-->
                                                    
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- <div class="btm-shape-prt">
    	<img class="img-fluid" src="images/shape2.png" alt="" />
    </div> -->
</section>
<style>
  .invalid-feedback {
      display:block;
  }
  .jcrop-holder{direction:ltr;text-align:left;}
  .jcrop-vline,.jcrop-hline{background:#FFF url(Jcrop.gif);font-size:0;position:absolute;}
  .jcrop-vline{height:100%;width:1px!important;}
  .jcrop-vline.right{right:0;}
  .jcrop-hline{height:1px!important;width:100%;}
  .jcrop-hline.bottom{bottom:0;}
  .jcrop-tracker{-webkit-tap-highlight-color:transparent;-webkit-touch-callout:none;-webkit-user-select:none;height:100%;width:100%;}
  .jcrop-handle{background-color:#333;border:1px #EEE solid;font-size:1px;height:7px;width:7px;}
  .jcrop-handle.ord-n{left:50%;margin-left:-4px;margin-top:-4px;top:0;}
  .jcrop-handle.ord-s{bottom:0;left:50%;margin-bottom:-4px;margin-left:-4px;}
  .jcrop-handle.ord-e{margin-right:-4px;margin-top:-4px;right:0;top:50%;}
  .jcrop-handle.ord-w{left:0;margin-left:-4px;margin-top:-4px;top:50%;}
  .jcrop-handle.ord-nw{left:0;margin-left:-4px;margin-top:-4px;top:0;}
  .jcrop-handle.ord-ne{margin-right:-4px;margin-top:-4px;right:0;top:0;}
  .jcrop-handle.ord-se{bottom:0;margin-bottom:-4px;margin-right:-4px;right:0;}
  .jcrop-handle.ord-sw{bottom:0;left:0;margin-bottom:-4px;margin-left:-4px;}
  .jcrop-dragbar.ord-n,.jcrop-dragbar.ord-s{height:7px;width:100%;}
  .jcrop-dragbar.ord-e,.jcrop-dragbar.ord-w{height:100%;width:7px;}
  .jcrop-dragbar.ord-n{margin-top:-4px;}
  .jcrop-dragbar.ord-s{bottom:0;margin-bottom:-4px;}
  .jcrop-dragbar.ord-e{margin-right:-4px;right:0;}
  .jcrop-dragbar.ord-w{margin-left:-4px;}
  .jcrop-light .jcrop-vline,.jcrop-light .jcrop-hline{background:#FFF;filter:alpha(opacity=70)!important;opacity:.70!important;}
  .jcrop-light .jcrop-handle{-moz-border-radius:3px;-webkit-border-radius:3px;background-color:#000;border-color:#FFF;border-radius:3px;}
  .jcrop-dark .jcrop-vline,.jcrop-dark .jcrop-hline{background:#000;filter:alpha(opacity=70)!important;opacity:.7!important;}
  .jcrop-dark .jcrop-handle{-moz-border-radius:3px;-webkit-border-radius:3px;background-color:#FFF;border-color:#000;border-radius:3px;}
  .solid-line .jcrop-vline,.solid-line .jcrop-hline{background:#FFF;}
  .jcrop-holder img,img.jcrop-preview{max-width:none;}
</style>

<div id="myModal" class="modal fade" role="dialog" style="padding-right: 400px;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="width: 900px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img src="{{asset('f/images/instruction.png')}}" width="850px;">
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>

<div id="myModalLogos" class="modal fade" role="dialog" style="padding-right: 400px;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="width: 900px;">
      <div class="modal-header">
         <h4 class="modal-title">Choose your logo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <form action="{{route('account.profile.updatelogo',['id'=> $user->id])}}" method="post" name="updatelogo" enctype="multipart/form-data">
              @csrf
                <!--<img src="{{asset('f/images/instruction.png')}}" width="850px;">-->
                <div class="row">
                    @if(!empty($adminlogos))
                        @foreach($adminlogos as $adminlogo)
                            <div class="col-md-4 text-center">
                                <input type="radio" id="male{{$adminlogo->id}}" name="company_logo" value="{{$adminlogo->id}}">
                                <label for="male{{$adminlogo->id}}"><img width="200px" src="{{asset('uploads/logo/original')}}/{{$adminlogo->logo}}"></label>
                            </div>
                         @endforeach
                     @endif
                 </div>
                 <br>
                 <button type="submit" name="update_logo" id="update_logo" class="btn btn-primary btn-round">Update Logo</button>
            </form>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>

<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
        		<!-- <button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Upload & Crop Logo</h4> -->
      		</div>
      		<div class="modal-body">
        		<div class="row">
  					<div class="col-md-12 text-center">
						  <div id="image_jcrop" style="width:350px; margin-top:30px"></div>
  					</div>
  					<div class="col-md-4" style="padding-top:30px;display: none;">
  						
              <canvas id="demo_canvas" ></canvas>
              <input id="demo_png" type="hidden" />
					</div>
				</div>
      		</div>
      		<div class="modal-footer">
            <button class="btn btn-success" onclick="uploadToServer();">Crop & Upload Logo</button>
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
    </div>
</div>




@endsection
@section("js_after")
  <script src="{{asset('f/js/')}}/jcrop.js"></script>
  <script>  
    var base_url = "{{url("/")}}";

    $(document).ready(function(){

      $("#upload_image1").change(function(){
        picture(this);
        $('#uploadimageModal').modal('show');
      });
      var picture_width;
      var picture_height;
      var crop_max_width = 300;
      var crop_max_height = 300;

      function picture(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $("#image_jcrop, #preview").html("").append("<img src=\""+e.target.result+"\" alt=\"\" />");
            picture_width = $("#preview img").width();
            picture_height = $("#preview img").height();
            $("#image_jcrop  img").Jcrop({
              onChange: canvas,
              onSelect: canvas,
              boxWidth: crop_max_width,
              boxHeight: crop_max_height
            });
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      function canvas(coords){
        var imageObj = $("#image_jcrop img")[0];
        var canvas = $("#demo_canvas")[0];
        canvas.width  = coords.w;
        canvas.height = coords.h;
        var context = canvas.getContext("2d");
        context.drawImage(imageObj, coords.x, coords.y, coords.w, coords.h, 0, 0, canvas.width, canvas.height);
        png();
      }

      function png() {
        var png = $("#demo_canvas")[0].toDataURL('image/png');
        $("#demo_png").val(png);
      }

    });  

    

    $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    
    function uploadToServer(){
      var data = {};
      data.image = document.getElementById("demo_png").value;
      $.ajax({
        url: base_url+'/logo-crop',
        type: "post",
        data:data,
        success:function(data)
        {
            console.log(data);
          $('#uploadimageModal').modal('hide');
          //$('#upload_image').val(data);
          $('#uploaded_image').html(data);
        }
      });
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

  </script>
@endsection