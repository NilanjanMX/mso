@extends('layouts.frontend')

@section('js_after')
    <script>
        $(".fixed_deposit_chk").click( function(){
            if( $(this).val()=='yes'){
                $('.fixed_deposit_box').css("display", "block");
                $('.fixed_deposit_box').children().children().find('.form-control').attr("required", "true");
            }else {
                 $('.fixed_deposit_box').css("display", "none");;
            }
        });
        
        $(".debt_fund_chk").click( function(){
            if( $(this).val()=='yes'){
                $('.debt_fund_box').css("display", "block");
                $('.debt_fund_box').children().children().find('.form-control').attr("required", "true");
            }else {
                 $('.debt_fund_box').css("display", "none");;
            }
        });

        $(".equity_fund_chk").click( function(){
            if( $(this).val()=='yes'){
                $('.equity_fund_box').css("display", "block");
                $('.equity_fund_chk').children().children().find('.form-control').attr("required", "true");
            }else {
                 $('.equity_fund_box').css("display", "none");;
            }
        });

        $(".user_defined_chk").click( function(){
            if( $(this).val()=='yes'){
                $('.user_defined_box').css("display", "block");
                $('.user_defined_chk').children().children().find('.form-control').attr("required", "true");
            }else {
                $('.user_defined_box').css("display", "none");;
            }
        });

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
                    capital_gain_amount: {
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
                    ltcg_tax_rate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                    interest_cg_bond_scheme: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 18.00]
                    },
                    expected_indexation_rate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 18.00]
                    },
                    applicable_income_tax_slab: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                    fixed_deposit_expected_return: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 18.00]
                    },
                    fixed_deposit_taxation_rate: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                    debt_fund_expected_return: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 10.00]
                    },
                    debt_fund_taxation_rate: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                    equity_fund_expected_return: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00]
                    },
                    equity_fund_taxation_rate: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                    other_expected_return: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 18.00]
                    },
                    other_taxation_rate: {
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 99.00]
                    },
                },
                messages:{
                    capital_gain_amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    period: "Please enter no more than 2 characters.",
                    ltcg_tax_rate: "Please enter a value between 00.00 - 99.00%.",
                    interest_cg_bond_scheme: "Please enter a value between 0.10 - 18.00%.",
                    expected_indexation_rate: "Please enter a value between 0.10 - 18.00%.",
                    applicable_income_tax_slab: "Please enter a value between 00.00 - 99.00%.",
                    fixed_deposit_expected_return: "Please enter a value between 0.10 - 18.00%.",
                    fixed_deposit_taxation_rate: "Please enter a value between 00.00 - 99.00%.",
                    debt_fund_expected_return: "Please enter a value between 0.10 - 10.00%.",
                    debt_fund_taxation_rate: "Please enter a value between 00.00 - 99.00%.",
                    equity_fund_expected_return: "Please enter a value between 0.10 - 15.00%.",
                    equity_fund_taxation_rate: "Please enter a value between 00.00 - 99.00%.",
                    other_expected_return: "Please enter a value between 0.10 - 18.00%.",
                    other_taxation_rate: "Please enter a value between 00.00 - 99.00%."
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
                    <h3 class="mb-3 text-center">CG Bond vs. Other Investment</h3>
                    <div class="card sip-calculator">
                    @include('frontend.calculators.common_bio')
                    <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.CG_Bond_vs_Other_Investment_output')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Long Term Capital Gain Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('capital_gain_amount') ? ' is-invalid' : '' }}" name="capital_gain_amount" value="{{old('capital_gain_amount')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('capital_gain_amount'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('capital_gain_amount') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">LTCG Tax Rate</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('ltcg_tax_rate') ? ' is-invalid' : '' }}" name="ltcg_tax_rate" value="{{old('ltcg_tax_rate')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('ltcg_tax_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('ltcg_tax_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Period of CG Bond Scheme</label>
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
                                <label class="col-sm-5 col-form-label">Interest on CG Bond Scheme</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('interest_cg_bond_scheme') ? ' is-invalid' : '' }}" name="interest_cg_bond_scheme" value="{{old('interest_cg_bond_scheme')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('interest_cg_bond_scheme'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('interest_cg_bond_scheme') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Expected Average Yearly Indexation Rate</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('expected_indexation_rate') ? ' is-invalid' : '' }}" name="expected_indexation_rate" value="{{old('expected_indexation_rate')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('expected_indexation_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('expected_indexation_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Applicable Income Tax Slab</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('applicable_income_tax_slab') ? ' is-invalid' : '' }}" name="applicable_income_tax_slab" value="{{old('applicable_income_tax_slab')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('applicable_income_tax_slab'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('applicable_income_tax_slab') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted">Other Available Investment Options</h6>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Fixed Deposit</label>
                                <div class="col-sm-7">
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="fixed_deposit"  value="yes" @if(old('fixed_deposit')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="fixed_deposit" value="no" @if(old('fixed_deposit')=='no')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="fixed_deposit_box" style="display:none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Assumed Return (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('fixed_deposit_expected_return') ? ' is-invalid' : '' }}" name="fixed_deposit_expected_return" value="{{old('fixed_deposit_expected_return')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Taxation Rate (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('fixed_deposit_taxation_rate') ? ' is-invalid' : '' }}" name="fixed_deposit_taxation_rate" value="{{old('fixed_deposit_taxation_rate')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Indexation Benefit</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="fixed_deposit_indexation_benefit">
                                                    <!--<option value="">Indexation Benefit</option>-->
                                                    <option value="yes">Yes</option>
                                                    <option selected value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="d-flex align-items-center">-->
                                    <!--    <input type="text" placeholder="Expected Return (%)" class="form-control pr-2 mr-1 number {{ $errors->has('fixed_deposit_expected_return') ? ' is-invalid' : '' }}" name="fixed_deposit_expected_return" value="{{old('fixed_deposit_expected_return')}}" >-->
                                    <!--    <input type="text" placeholder="Taxation Rate (%)" class="form-control pr-2 mr-1 number {{ $errors->has('fixed_deposit_taxation_rate') ? ' is-invalid' : '' }}" name="fixed_deposit_taxation_rate" value="{{old('fixed_deposit_taxation_rate')}}" >-->
                                    <!--    <select class="form-control" name="fixed_deposit_indexation_benefit">-->
                                    <!--        <option value="">Indexation Benefit</option>-->
                                    <!--        <option value="yes">Yes</option>-->
                                    <!--        <option selected value="no">No</option>-->
                                    <!--    </select>-->
                                    <!--</div>-->
                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Debt Fund</label>
                                <div class="col-sm-7">
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input debt_fund_chk" type="radio" name="debt_fund" value="yes" @if(old('debt_fund')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input debt_fund_chk" type="radio" name="debt_fund" value="no" @if(old('debt_fund')=='no')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="debt_fund_box" style="display:none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Assumed Return (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('debt_fund_expected_return') ? ' is-invalid' : '' }}" name="debt_fund_expected_return" value="{{old('debt_fund_expected_return')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Taxation Rate (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('debt_fund_taxation_rate') ? ' is-invalid' : '' }}" name="debt_fund_taxation_rate" value="{{old('debt_fund_taxation_rate')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Indexation Benefit</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="debt_fund_indexation_benefit">
                                                    <!--<option value="">Indexation Benefit</option>-->
                                                    <option selected value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                
                                <!--<div class="d-flex align-items-center">-->
                                <!--    <input type="text" placeholder="Expected Return (%)" class="form-control pr-2 mr-1 number {{ $errors->has('debt_fund_expected_return') ? ' is-invalid' : '' }}" name="debt_fund_expected_return" value="{{old('debt_fund_expected_return')}}" >-->
                                <!--    <input type="text" placeholder=Taxation Rate (%)" class="form-control pr-2 mr-1 number {{ $errors->has('debt_fund_taxation_rate') ? ' is-invalid' : '' }}" name="debt_fund_taxation_rate" value="{{old('debt_fund_taxation_rate')}}" >-->
                                <!--    <select class="form-control" name="debt_fund_indexation_benefit">-->
                                <!--        <option value="">Indexation Benefit</option>-->
                                <!--        <option selected value="yes">Yes</option>-->
                                <!--        <option value="no">No</option>-->
                                <!--    </select>-->
                                <!--</div>-->
                                        
                                        
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Equity Fund</label>
                                <div class="col-sm-7">
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input equity_fund_chk" type="radio" name="equity_fund" value="yes" @if(old('equity_fund')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input equity_fund_chk" type="radio" name="equity_fund" value="no" @if(old('equity_fund')=='no')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="equity_fund_box" style="display:none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Assumed Return (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('equity_fund_expected_return') ? ' is-invalid' : '' }}" name="equity_fund_expected_return" value="{{old('equity_fund_expected_return')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Taxation Rate (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('equity_fund_taxation_rate') ? ' is-invalid' : '' }}" name="equity_fund_taxation_rate" value="{{old('equity_fund_taxation_rate')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Indexation Benefit</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="equity_fund_indexation_benefit">
                                                    <!--<option value="">Indexation Benefit</option>-->
                                                    <option value="yes">Yes</option>
                                                    <option selected value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="d-flex align-items-center">-->
                                    <!--        <input type="text" placeholder="Expected Return (%)" class="form-control pr-2 mr-1 number {{ $errors->has('equity_fund_expected_return') ? ' is-invalid' : '' }}" name="equity_fund_expected_return" value="{{old('equity_fund_expected_return')}}" >-->
                                    <!--        <input type="text" placeholder="Taxation Rate (%)" class="form-control pr-2 mr-1 number {{ $errors->has('equity_fund_taxation_rate') ? ' is-invalid' : '' }}" name="equity_fund_taxation_rate" value="{{old('equity_fund_taxation_rate')}}" >-->
                                    <!--        <select class="form-control" name="equity_fund_indexation_benefit">-->
                                    <!--            <option value="">Indexation Benefit</option>-->
                                    <!--            <option value="yes">Yes</option>-->
                                    <!--            <option selected value="no">No</option>-->
                                    <!--        </select>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Enter any other user-defined investment: (Optional)</label>
                                <div class="col-sm-7">
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input user_defined_chk" type="radio" name="user_defined" value="yes" @if(old('user_defined')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input user_defined_chk" type="radio" name="user_defined" value="no" @if(old('user_defined')=='no')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="user_defined_box" style="display:none">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row form-group">
                                            <label class="col-sm-5 col-form-label">
                                                Product Name
                                            </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control pr-2 mr-1" name="product_name" value="{{old('product_name')}}" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="row form-group">
                                            <label class="col-sm-7 col-form-label">Assumed Return (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('other_expected_return') ? ' is-invalid' : '' }}" name="other_expected_return" value="{{old('other_expected_return')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Taxation Rate (%)</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('other_taxation_rate') ? ' is-invalid' : '' }}" name="other_taxation_rate" value="{{old('other_taxation_rate')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Indexation Benefit</label>
                                            <div class="col-sm-5">
                                                <select class="form-control" name="other_indexation_benefit">
                                                    <!--<option value="">Indexation Benefit</option>-->
                                                    <option value="yes">Yes</option>
                                                    <option selected value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="col-sm-12">-->
                                    <!--    <div class="d-flex align-items-center">-->
                                    <!--        <input type="text" placeholder="Product Name" class="form-control pr-2 mr-1" name="product_name" value="{{old('product_name')}}" >-->
    
                                    <!--        <input type="text" placeholder="Expected Return (%)" class="form-control pr-2 mr-1 number {{ $errors->has('other_expected_return') ? ' is-invalid' : '' }}" name="other_expected_return" value="{{old('other_expected_return')}}" >-->
    
                                    <!--        <input type="text" placeholder="Taxation Rate (%)" class="form-control pr-2 mr-1 number {{ $errors->has('other_taxation_rate') ? ' is-invalid' : '' }}" name="other_taxation_rate" value="{{old('other_taxation_rate')}}" >-->
                                    <!--        <select class="form-control" name="other_indexation_benefit">-->
                                    <!--            <option value="">Indexation Benefit</option>-->
                                    <!--            <option value="yes">Yes</option>-->
                                    <!--            <option value="no">No</option>-->
                                    <!--        </select>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                
                                </div>
                            </div>


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
