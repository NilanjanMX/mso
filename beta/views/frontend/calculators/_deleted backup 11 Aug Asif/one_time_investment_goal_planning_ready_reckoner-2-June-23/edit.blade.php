@extends('layouts.frontend')

@php

    $data['investment'] = old('investment');
    if(!$data['investment']){
        $data['investment'] = $form_data['investment'];
    }

    $data['period1'] = old('period1');
    if(!$data['period1']){
        $data['period1'] = $form_data['period1'];
    }
    
    $data['period2'] = old('period2');
    if(!$data['period2']){
        $data['period2'] = $form_data['period2'];
    }
    
    $data['period3'] = old('period3');
    if(!$data['period3']){
        $data['period3'] = $form_data['period3'];
    }
    
    $data['period4'] = old('period4');
    if(!$data['period4']){
        $data['period4'] = $form_data['period4'];
    }
    
    $data['period5'] = old('period5');
    if(!$data['period5']){
        $data['period5'] = $form_data['period5'];
    }
    
    $data['interest1'] = old('interest1');
    if(!$data['interest1']){
        $data['interest1'] = $form_data['interest1'];
    }
    
    $data['interest2'] = old('interest2');
    if(!$data['interest2']){
        $data['interest2'] = $form_data['interest2'];
    }
    
    $data['interest3'] = old('interest3');
    if(!$data['interest3']){
        $data['interest3'] = $form_data['interest3'];
    }
    
    $data['report'] = old('report');
    if(!$data['report']){
        $data['report'] = $form_data['report'];
    }
    
    $data['suggest'] = old('suggest');
    if(!$data['suggest']){
        $data['suggest'] = $form_data['suggest'];
    }
    
    $data['note'] = old('note');
    if(!$data['note']){
        $data['note'] = $form_data['note'];
    }
    
    $data['client'] = old('client');
    if(!$data['client']){
        $data['client'] = $form_data['client'];
    }
    
    $data['is_note'] = old('is_note');
    if(!$data['is_note']){
        $data['is_note'] = $form_data['is_note'];
    }
    
    $data['is_graph'] = old('is_graph');
    if(!$data['is_graph']){
        $data['is_graph'] = $form_data['is_graph'];
    }
    
    $data['include_performance'] = old('include_performance');
    if(!$data['include_performance']){
        $data['include_performance'] = $suggested_performance;
    }
    
    $data['suggestedlist_type'] = old('suggestedlist_type');
    if(!$data['suggestedlist_type']){
        $data['suggestedlist_type'] = $suggestedlist_type;
    }

@endphp

@section('js_after')
    <script>
        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });
        $(function() {
            jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
                return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
            }, "You must include two decimal places");

            var validator = $(".js-validate-form").validate({
                errorElement: "em",
                errorContainer: $("#warning, #summary"),
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                },
                rules: {
                    investment: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                        range: [100, 9999999999]
                    },
                    interest1: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00]
                    },
                    interest2: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00]
                    },
                    interest3: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00]
                    },
                    period1: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    period2: {
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    period3: {
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    period4: {
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    period5: {
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    }
                },
                messages: {
                    investment: "Please enter a value between 100 and 9,99,99,99,999.",
                    interest1: "Please enter a value between 0.10 - 15.00%.",
                    interest2: "Please enter a value between 0.10 - 15.00%.",
                    interest3: "Please enter a value between 0.10 - 15.00%.",
                    period1: "Please enter no more than 2 characters.",
                    period2: "Please enter no more than 2 characters.",
                    period3: "Please enter no more than 2 characters.",
                    period4: "Please enter no more than 2 characters.",
                    period5: "Please enter no more than 2 characters.",
                }
            });
        });
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    @if(old('client')!='')
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif
@endsection

@section('content')
{{-- <div class="banner bannerForAll container" style="padding-bottom: 0;">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Premium Calculators</h2>
                    <p>Lörem ipsum rutavdrag bespepp. Danyre gereras, sar rugbyförälder, ären. Multitirade pabel men spökgarn medan nåfus kreddig. Decill eus. Osm kromera, diadunade intrarade. 
                    </p>
                    <a href="" class="createtempbtn" style=" margin-right: 22px; "><button class="btn banner-btn mt-3">Sample Reports</button></a>
                    <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">How to Use</button></a>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/pcalculatorbanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div> --}}
    <div class="banner">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
            </div>
        </div>
    </div> 

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Lumpsum Investment Ready Reckoner</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.oneTimeInvestmentReadyReckonerOutput')}}" method="post">
                            <div class="card sip-calculator singleLineHolder">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Initial Investment</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control {{ $errors->has('investment') ? ' is-invalid' : '' }}" name="investment" value="{{$data['investment']}}"  required>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                        @if ($errors->has('investment'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('investment') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Assumed Rate Of Return:</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('interest1') ? ' is-invalid' : '' }}" name="interest1" value="{{$data['interest1']}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('interest2') ? ' is-invalid' : '' }}" name="interest2" value="{{$data['interest2']}}">
                                            <input type="text" class="form-control number {{ $errors->has('interest3') ? ' is-invalid' : '' }}" name="interest3" value="{{$data['interest3']}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('interest1'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('interest1') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('interest2'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('interest2') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('interest3'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('interest3') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Investment Period:</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period1') ? ' is-invalid' : '' }}" name="period1" value="{{$data['period1']}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period2') ? ' is-invalid' : '' }}" name="period2" value="{{$data['period2']}}">
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period3') ? ' is-invalid' : '' }}" name="period3" value="{{$data['period3']}}">
                                            <input type="text" class="form-control pr-5 mr-1 maxtwodigit {{ $errors->has('period4') ? ' is-invalid' : '' }}" name="period4" value="{{$data['period4']}}">
                                            <input type="text" class="form-control maxtwodigit {{ $errors->has('period5') ? ' is-invalid' : '' }}" name="period5" value="{{$data['period5']}}">
                                        </div>
                                        <div class="cal-icon">
                                            Yr
                                        </div>
                                        @if ($errors->has('period1'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period1') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period2'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period2') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period3'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period3') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period4'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period4') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period4'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period4') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                               
                            </div>
                            
                            <div class="card sip-calculator singleLineHolder">
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if($form_data['client']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{old('clientname')}}" maxlength="30">
                                        <div class="cal-icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        @if ($errors->has('clientname'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('clientname') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                            <label class="sqarecontainer">View Detailed Graph
                                                <input id="is_graph" type="checkbox" name="is_graph" value="1" @if($form_data['is_graph']=='1') checked  @endif> 
                                                <span class="checkmark"></span>
                                            </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label class="sqarecontainer">Add Comments (If any)
                                            <input id="is_note" type="checkbox" name="is_note" value="1" @if($form_data['is_note']=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$form_data['note']}}</textarea>
                                        <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Get Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Summary Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if($data['report']=='summary') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Detailed Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if($data['report']=='summary')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                @include('frontend.calculators.suggested.edit_form')
                                <div class="form-group row">
                                    
                                    <!-- <div class="offset-1 col-sm-10">
                                        <div class=" calcBelowBtn">
                                                <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                        </div>
                                    </div> -->
                                    
                                    <div class="offset-1 col-sm-10">
                                        <div class="calcBelowBtn">
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><i class="fa fa-angle-left"></i> Back</button>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="btm-shape-prt">
                <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
            </div>
        </div></section>

@endsection
