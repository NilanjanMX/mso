@extends('layouts.frontend')

@section('js_after')

@endsection

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Debt held by Mutual Funds</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row" >
                <div class="col-md-12">
                    <div class="rt-pnl" style="box-shadow: none; padding-left: 0px; padding-right: 0px;">
                        <h2 class="headline1">{{$name}}</h2>
                        <div class="rt-btn-prt">
                            <a href="javascript:history.back()"><i class="fa fa-angle-left"></i> Back</a>
                            <a href="{{route('frontend.debt_held_pdf',['id'=>$mf_scanner_saved_id,'type'=>'D'])}}">Download</a>
                        </div>
                        <form id="save_form_data">
                          <div class="allocationChartAll">
                              <div class="row">
                                    <div class="col-lg-12" style="text-align:right;">
                                        As On {{date('M d, Y', strtotime($detail['date']))}}
                                    </div>
                                    <div class="col-lg-12">
                                        <table class="table text-center mfliketbl">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        <strong>Debt Issuing Company</strong>
                                                    </th>
                                                    <th>
                                                        <strong>No. of Funds</strong>
                                                    </th>
                                                    <th>
                                                        <strong>Total Market Value (In ₹ Cr)</strong>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center;">{{$detail['company']}}</td>
                                                    <td style="text-align:center;">{{$detail['number_of_fund']}}</td>
                                                    <td style="text-align:center;">{{custome_money_format($detail['total_mktval']/100)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                              </div>
                              <div class="row">
                                  <div class="col-lg-12">
                                        @if($type == "Stock")
                                            <table class="table text-center mfliketbl" style="margin-top:10px;">
                                                <tbody>
                                                    <tr>
                                                        <th><strong>Scheme Name</strong></th>
                                                        <th><strong>Fund Manager</strong></th>
                                                        <th><strong>AUM (in ₹ Cr)</strong></th>
                                                        <th><strong>% of AUM</strong></th>
                                                        <th colspan="1"><strong>Market Value (In ₹ Cr)</strong></th>
                                                    </tr>
                                                    <?php foreach($list as $key=>$value){  ?>
                                                        <tr>
                                                            <td style="text-align:left; height:30px;">{{$value['s_name']}}</td>
                                                            <td style="text-align:left;">{{$value['fund_mgr1']}}</td>
                                                            <td style="text-align:right;">{{custome_money_format($value['aum']/100)}}</td>
                                                            <td style="text-align:right;">{{number_format((float)($value['mktval']*100/$value['aum']), 4, '.', '')}}</td>
                                                            <td style="text-align:right;">{{number_format((float)($value['mktval']/100), 2, '.', '')}}</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            
                                        @else
                                            <table class="table text-center mfliketbl" style="margin-top:10px;">
                                                <tbody>
                                                    <tr>
                                                        <th rowspan="1" style="width: 190px;"><strong>AMC Name</strong></th>
                                                        <th><strong>AUM (in ₹ Cr)</strong></th>
                                                        <th><strong>% of AUM</strong></th>
                                                        <th colspan="1"><strong>Market Value (In ₹ Cr)</strong></th>
                                                    </tr>
                                                    <?php foreach($list as $key=>$value){ 
                                                        $res = (array) $value; ?>
                                                        <tr>
                                                            <td style="text-align:left; height:30px;">{{$value['fund_name']}}</td>
                                                            <td style="text-align:right;">{{custome_money_format($value['totalaum']/100)}}</td>
                                                            <td style="text-align:right;">{{number_format((float)($value['mktval']*100/$value['totalaum']), 4, '.', '')}}</td>
                                                            <td style="text-align:right;">{{number_format((float)($value['mktval']/100), 2, '.', '')}}</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        @endif
                                  </div>
                              </div>
                          </div>
                          
                          
                          <div class="row mt-5">
                              <div class="col-md-12">
                                  @php
                                    $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-debt-held")->first();
                                    if(!empty($note_data1)){
                                    @endphp
                                    {!!$note_data1->description!!}
                                @php } @endphp
                              </div>
                              <div class="col-md-12">
                                  Report Date : {{date('d/m/Y')}}
                              </div>
                          </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
