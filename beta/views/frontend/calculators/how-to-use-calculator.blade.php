@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">{{isset($page['title'])?$page['title']:''}}</h2>
            </div>
        </div>
    </div>
    <a href="#" class="btn-chat">Chat With Us</a>
</div>
<section class="main-sec">
	<div class="container">
    	<div class="row">
            <div class="col-md-12">
                <div class="cms-content">
                    {!! isset($page['content'])?$page['content']:'' !!}
                </div>
            </div>
        </div>
    
    </div>
</section>

@endsection
