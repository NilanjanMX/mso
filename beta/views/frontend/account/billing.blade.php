@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">Billing Details</h2>
            </div>
        </div>
    </div>
    <a href="#" class="btn-chat">Chat With Us</a>
</div>
<section class="main-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('frontend.account.left_menu')
            </div>

            <div class="col-md-9">  
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
                <div class="col-md-9">     
                <div class="stage">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('account.save_billing')}}" method="post" name="updateprofile" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Billing / Company Name</label>
                                                    <input type="text" name="company_name" class="form-control" value="{{old('company_name',isset($detail->company_name)?$detail->company_name:'')}}">
                                                    @if ($errors->has('company_name'))
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('company_name') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>  
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Billing Address</label>
                                                    <input type="text" name="billing_address" class="form-control" value="{{old('billing_address',isset($detail->billing_address)?$detail->billing_address:'')}}">
                                                    @if ($errors->has('billing_address'))
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('billing_address') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>  
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>GST Details <span style="color: #131f55;">(Optional)</span></label>
                                                    <input type="text" name="gst_detail" class="form-control" value="{{old('gst_detail',isset($detail->gst_detail)?$detail->gst_detail:'')}}">
                                                    @if ($errors->has('gst_detail'))
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('gst_detail') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                 <div class="form-group">
                                                    <label>GST ZONE</label><br>
                                                    <input type="radio" name="gst_zone" value="outside" @if(isset($detail->gst_zone) && $detail->gst_zone=='outside') checked @endif>
                                                    <label>Outside West Bengal</label>&emsp;<br>
                                                    <input type="radio" name="gst_zone" value="west_bengal" @if(isset($detail->gst_zone) && $detail->gst_zone=='west_bengal') checked @endif>
                                                    <label>West Bengal</label><br>
                                                    @if ($errors->has('gst_zone'))
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('gst_zone') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                        </div>
                                        <div class="add-new-user-btn mt-4 mb-2">
                                            <button type="submit" name="" class="btn btn-primary btn-round">Update</button>
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
    <div class="btm-shape-prt">
        <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />
    </div>
</section>

<div class="modal fade" id="permissionModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Permission</h4>
          <button type="button" class="close" data-dismiss="modal">
              <img src="{{asset('f/images/img/closeModal.png')}}" class="img-fluid" alt="">
          </button>
        </div>
        <form action="{{route('account.save_user_permission')}}" method="post">
            @csrf
            <input type="hidden" name="permission_user_id" id="permission_user_id" value="1">
            <!-- Modal body -->
            <div class="modal-body">
                <ul class="linkAccountList mb-0" id="permission_body_div">

                </ul>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-submit btn-block">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>
<style>
.invalid-feedback {
    display:block;
}
</style>

@endsection
