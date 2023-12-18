@extends('layouts.frontend')

@section('js_after')
    <script>
        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });

        $("#for_period_upto").on("keyup",function(){
            var vl=$(this).val();
            if(vl!=0)
            {
                $("#from_the_year").val(parseInt(vl)+1);
            }else{
                $("#from_the_year").val('');
            }
            
        })

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
                    initial_investment: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                        range: [100, 9999999999]
                    },
                    period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    fixed_deposit: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 12.00]
                    },
                    debt_fund: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 10.00]
                    },
                    applicable_short_term_tax_rate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                    applicable_long_term_tax_rate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 99.00]
                    },
                    assumed_inflation_rate_for_indexation: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 12.00]
                    },
                    for_period_upto: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    from_the_year: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    }
                },
                messages:{
                    initial_investment: "Please enter a value between 100 and 9,99,99,99,999.",
                    period: "Please enter no more than 2 characters.",
                    fixed_deposit: "Please enter a value between 0.10 - 12.00%.",
                    debt_fund: "Please enter a value between 0.10 - 10.00%.",
                    assumed_inflation_rate_for_indexation: "Please enter a value between 0.00 - 12.00%.",
                    applicable_short_term_tax_rate: "Please enter a value between 0.00 - 99.00%.",
                    applicable_long_term_tax_rate: "Please enter a value between 0.0 - 99.00%.",
                    for_period_upto: "Please enter no more than 2 characters.",
                    from_the_year: "Please enter no more than 2 characters."
                }
            });
        });
    </script>
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
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar') 
                <div class="col-md-12">
                    <h3 class="mb-3 text-center">Bank Fixed Deposit vs. Debt Mutual Fund</h3>
                    <div class="card sip-calculator">
                        @include('frontend.calculators.common_bio')
                    <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.bank_fixed_deposit_vs_debt_fund_output')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Initial Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('initial_investment') ? ' is-invalid' : '' }}" name="initial_investment" value="{{old('initial_investment')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('initial_investment'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('initial_investment') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('period') ? ' is-invalid' : '' }}" name="period" value="{{old('period')}}"  >
                                    <div class="cal-icon">
                                        Yr
                                    </div>
                                    @if ($errors->has('period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted">Assumed Rate of Return:</h6>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Fixed Deposit</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('fixed_deposit') ? ' is-invalid' : '' }}" name="fixed_deposit" value="{{old('fixed_deposit')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('fixed_deposit'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('fixed_deposit') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Debt Fund</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('debt_fund') ? ' is-invalid' : '' }}" name="debt_fund" value="{{old('debt_fund')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('debt_fund'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('debt_fund') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Inflation Rate for Indexation</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('assumed_inflation_rate_for_indexation') ? ' is-invalid' : '' }}" name="assumed_inflation_rate_for_indexation" value="{{old('assumed_inflation_rate_for_indexation')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('assumed_inflation_rate_for_indexation'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('assumed_inflation_rate_for_indexation') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @php
                            $applicable_short_term_tax_rate='30';
                            @endphp

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investor's  Tax Slab</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('applicable_short_term_tax_rate') ? ' is-invalid' : '' }}" name="applicable_short_term_tax_rate" value="{{old('applicable_short_term_tax_rate',isset($applicable_short_term_tax_rate)?$applicable_short_term_tax_rate:'')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('applicable_short_term_tax_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('applicable_short_term_tax_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="fixed_deposit_box">
                                <div class="row">
                                    @php
                                    $applicable_long_term_tax_rate='20';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Applicable Long Term Tax Rate</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('applicable_long_term_tax_rate') ? ' is-invalid' : '' }}" name="applicable_long_term_tax_rate" value="{{old('applicable_long_term_tax_rate',isset($applicable_long_term_tax_rate)?$applicable_long_term_tax_rate:'')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                    $for_period_upto='3';
                                    @endphp
                                    
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">After</label>
                                            <div class="col-sm-5">
                                                <input type="text" id="for_period_upto" class="form-control pr-2 mr-1 number maxtwodigit {{ $errors->has('for_period_upto') ? ' is-invalid' : '' }}" name="for_period_upto" value="{{old('for_period_upto',isset($for_period_upto)?$for_period_upto:'')}}" >
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                        
                                </div>
                            </div>

                           
                            @php
                            $from_the_year='4';
                            @endphp
                                    
                            <input type="hidden" readonly class="form-control pr-2 mr-1 number maxtwodigit {{ $errors->has('from_the_year') ? ' is-invalid' : '' }}" id="from_the_year" name="from_the_year" value="{{old('from_the_year',isset($from_the_year)?$from_the_year:'')}}" >

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if(old('client')=='1') checked  @endif> Add Client Name
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{old('clientname')}}" maxlength="30">
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
                                <label class="col-sm-5 col-form-label">
                                    <input id="is_client" type="checkbox" name="benefit" value="1" @if(old('benefit')=='1') checked  @endif> Display benefit of debt funds
                                </label>
                            </div>
                            

                            @include('frontend.calculators.suggested.form')

                            <div class="form-group row">

                                <div class="offset-5 col-sm-7">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                        </div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary btn-round btn-block">Calculate</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
