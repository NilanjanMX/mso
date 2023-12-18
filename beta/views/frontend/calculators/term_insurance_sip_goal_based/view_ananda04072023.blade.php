@extends('layouts.frontend')
@section('js_after')
    <script>
        jQuery(document).ready(function(){
            jQuery('#save_cal_btn').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var title = jQuery('#save_title').val();
                if(title.trim()==''){
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').addClass('alert-danger');
                    jQuery('#save_cal_msg').html('Please Enter Desired Download File Name');
                }else{
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').html('');
                    jQuery('#save_title').val('');
                    jQuery.ajax({
                        url: "{{ route('frontend.termInsuranceSIPgoalBase_save') }}",
                        method: 'get',
                        data: {
                            title: title
                        },
                        success: function(result){
                            jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                            jQuery('#save_cal_msg').addClass('alert-success');
                            jQuery('#save_cal_msg').html('Data Successfully Saved');
                            setTimeout(function () {
                                $('#saveOutput').modal('toggle');
                                jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                                jQuery('#save_cal_msg').html('');
                            },500);
                            jQuery('.save_only').hide();
                            jQuery('.view_save_only').show();
                        }});
                }

            });
        });
    </script>
    <style>
        nostyleshow {
            display: none;
        }
    
        main header{
            display: none;
        }
        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 30px;
        }
    
        table th,
        table td {
            text-align: center;
            border: 1px solid #b8b8b8;
            padding: 5px 20px;
            font-weight: normal;
            color: #000;
        }
    
        table {
            margin: 0;
        }
    
        table th {
            font-weight: bold;
            background: #a9f3ff;
        }
    
        .table-bordered th, .table-bordered td{
            padding: 10px;
            font-size: 18px;
        }
    
        h1 {
            font-size: 20px !important;
            color: #131f55 !important;
            margin-bottom: 0 !important;
            margin-top: 15px !important;
            width: 100%;
        }
        .page-break {
            page-break-after: always;
        }
    
    
        @page {
            margin-top: 160px
        }
    
        footer p{
            display: none;
        }
    
        p{
            text-align: left;
        }
    
        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: 42%;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
            display: none;
        }
        main{
            width: 100% !important;
        }
    </style>
@endsection
@section('content')

    <div class="banner">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div> --}}
        </div>
    </div>
    @php
        //Number of Months R9*12
        $number_of_months = $term_insurance_period*12;
         //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return2 = (1+$rate_of_return/100)**(1/12)-1;
         //Monthly SIP Amount (R7*AV28)/((1+AV28)*((1+AV28)^(AV27)-1))
        $monthly_sip_amount = ($goal_amount*$rate_of_return2)/((1+$rate_of_return2)*((1+$rate_of_return2)**($number_of_months)-1));

    @endphp

    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h5 class="midheading">Term Insurance + SIP (Goal Based) Comparison @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @else  @endif</h5>
                            
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Current Age</strong>
                                    </td>
                                    <td>
                                        {{$current_age?$current_age:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Term Insurance / Goal Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($goal_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Term Insurance Period</strong>
                                    </td>
                                    <td>
                                        {{$term_insurance_period?$term_insurance_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Term Insurance Annual Premium</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($term_insurance_annual_premium)}}
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">
                                Monthly SIP Required <br>
                                To Achieve Goal @ {{number_format($rate_of_return, 2, '.', '')}} %
                            </h1>
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        ₹ {{custome_money_format($monthly_sip_amount)}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p>
                                If you take Term Cover of<b> ₹ {{custome_money_format($goal_amount)}}</b> and do Monthly SIP of  <b>₹ {{custome_money_format($monthly_sip_amount)}}</b> you may be
                                assured of minimum payout of <b>₹ {{custome_money_format($goal_amount)}}</b> either on survival at age <b>{{$term_insurance_period+$current_age}}</b> or unfortunate event of death, subject to fund performance at Assumed rate of return mentioned herewith.
                            </p>
                            <p style="text-align: left">
                                * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                                * Returns are not guaranteed. The above is for illustration purpose only.
                            </p>

                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Yearwise Projected Value</h1>
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <th style="vertical-align: middle;">Age</th>
                                    <th style="vertical-align: middle;width: 20%;">Life Cover</th>
                                    <th>Annual Investment (Insurance Premium + SIP)</th>
                                    <th>Year End SIP Value @ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                                    <th>In case of Death<br> (Life Cover + SIP Value)</th>
                                </tr>
                                @for($i=1;$i<=$term_insurance_period;$i++)
                                    @php
                                        //Annual Investment (Insurance Premium + SIP) AS59+AT59*12
                                        $annual_investment = $term_insurance_annual_premium+$monthly_sip_amount*12;
                                        //Year End Value  (1+AW59)*AT59*(((1+AW59)^(AV59*12)-1)/AW59)
                                        $year_end_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($i*12)-1)/$rate_of_return2);
                                        $current_age++;
                                    @endphp
                                    <tr>
                                        <td>{{$current_age}}</td>
                                        <td>₹ {{custome_money_format($goal_amount)}}</td>
                                        <td>₹ {{custome_money_format($annual_investment)}}</td>
                                        <td>₹ {{custome_money_format($year_end_value)}}</td>
                                        <td>₹ {{custome_money_format($goal_amount+$year_end_value)}}</td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>

                            
                        </div>
                                
                            @if($is_note == 1 && isset($note) && !empty($note))
                                <h1 class="midheading">Comments</h1>
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center">
                                        <tbody>
                                            <tr>
                                                <td>{{$note}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            
    
                            @include('frontend.calculators.suggested.output')
                        </div>
                    </div>
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.lumsumInvestmentRequiredForTargetFutureValuePdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput" style="width: 320px;">Save & Merge with Sales Presenters</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.termInsuranceSIPgoalBase_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

    <div class="modal fade" id="mergeSalesPresentersOutput" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SALES PRESENTER SOFTCOPY SAVED LIST</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form target="_blank" action="{{route('frontend.termInsuranceSIPgoalBase_merge_download')}}" method="get">
                        <input type="hidden" name="save_file_id" value="{{$id}}">
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>List Name</th>
                                <th>Valid Till</th>
                            </tr>
                            @if(isset($savelists) && count($savelists)>0)
                                @foreach($savelists as $svlist)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="saved_sp_list_id[]" value="{{$svlist['id']}}">
                                        </td>
                                        <td>{{$svlist['created_at']->format('d/m/Y - h:i A')}}</td>
                                        <td>{{$svlist['title']}} ({{$svlist->softcopies->count()}} images)</td>
                                        <td>{{date('d/m/Y - h:i A',strtotime($svlist['validate_at']))}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">SUGGESTED PRESENTATION LIST</h5>
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th style="text-align: left">List Name</th>
                            </tr>
                            @if(isset($suggestedlists) && count($suggestedlists)>0)
                                @foreach($suggestedlists as $sglist)
                                    <tr>
                                        <td>
                                            <input type="radio" name="saved_list_id" value="{{$sglist['id']}}">
                                        </td>
                                        <td style="text-align: left" >{{$sglist['title']}} ({{$sglist->softcopies->count()}} images)</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">WHERE YOU WANT TO MERGE?</h5>
                        <table>
                            <tbody>
                            <tr>
                                <td style="text-align: left">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="before" name="mergeposition">Before
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="after" name="mergeposition" checked>After
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @if($permission['is_cover'])
                            <h5 class="modal-title">&nbsp;</h5>
                            <table>
                                <tbody>
                                <tr>
                                    <td style="text-align: left">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="1" name="is_cover" onchange="changeCover(1);">With Cover
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="0" name="is_cover"  onchange="changeCover(0);" checked>Without Cover
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                        <h5 class="modal-title">&nbsp;</h5>
                        <div id="pdf_title_line_id" style="display: none;">
                            <div class="form-group">
                                <input type="text" name="pdf_title_line1" class="form-control" id="pdf_title_line1" placeholder="PDF Title Line 1" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="pdf_title_line2" class="form-control" id="pdf_title_line2" placeholder="PDF Title Line 2" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_name" class="form-control" id="client_name" placeholder="Client Name" value="" maxlength="22">
                            </div>
                        </div>
                        <p></p>
                        <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary btn-round" >Merge & Download</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection

