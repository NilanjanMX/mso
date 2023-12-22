@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">Add New User</h2>
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
                <div class="stage">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('account.save_user_management')}}" method="post" name="updateprofile" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>First Name <em>*</em></label>
                                                    <input type="text" name="first_name" class="form-control" value="{{old('first_name')}}">
                                                </div>
                                                @if ($errors->has('first_name'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('first_name') }}</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-lg-6">
                                                 <div class="form-group">
                                                    <label>Last Name <em>*</em></label>
                                                    <input type="text" name="last_name" class="form-control" value="{{old('last_name')}}">
                                                </div>  
                                                @if ($errors->has('last_name'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('last_name') }}</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-lg-6">
                                                 <div class="form-group">
                                                    <label>Email address <em>*</em></label>
                                                    <input type="email" name="email" class="form-control" value="{{old('email')}}">
                                                </div>
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Phone No <em>*</em></label>
                                                    <input type="number" name="phone_no" class="form-control" value="{{old('phone_no')}}"  onkeypress="return isNumber(event)">
                                                </div>
                                                @if ($errors->has('phone_no'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone_no') }}</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>GST Number </label>
                                                    <input type="text" name="gst_number" class="form-control" value="{{old('gst_number')}}" maxlength="15">
                                                </div>
                                            </div>

                                            <!-- <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Company Name</label>
                                                    <input type="text" name="company_name" class="form-control" value="{{old('company_name')}}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>City</label>
                                                    <input type="text" name="city" class="form-control" value="{{old('city')}}">
                                                </div>
                                            </div> -->
                                        </div>

                                        <div class="add-new-user-btn mt-4 mb-2">
                                            <button type="submit" name="" class="btn btn-primary btn-round">Save</button>
                                            <a href="{{route('account.user_management')}}" class="btn btn-cancel btn-round ml-2">Cancel</a>
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
    <div class="btm-shape-prt">
    	<img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />
    </div>
</section>
<style>
.invalid-feedback {
    display:block;
}
</style>

@endsection
