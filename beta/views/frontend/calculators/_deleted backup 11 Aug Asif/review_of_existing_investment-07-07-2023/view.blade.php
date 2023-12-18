
@extends('layouts.frontend')
@section('js_after')
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
    
    <div class="banner styleApril">
        <div class="container">
            <!-- @ include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Calculators Cum Client Proposals</h2>
                </div>
            </div> -->
        </div>
    </div>
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                                        
                    <div class="outputTableHolder">
                        <h1 class="midheading">Review of Investments @if(isset($client_name)) <br> For {{$client_name}} @endif</h1>
    
    
                        @if($mutual_fund)
                            <h1 class="midheading">Mutual Fund</h1>
                            <?php foreach($mutual_fund_list as $key => $value) { 
                                        $width_per = 40/$value['return_count'];
                                    ?>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                
                                    <thead>
                                      <tr>
                                        <th rowspan="2" style="vertical-align: middle;width: 12%;">Asset Class</th>
                                        <th rowspan="2" style="vertical-align: middle;width: 13%;">Category</th>
                                        <th rowspan="2" style="vertical-align: middle;width: 30%;">Scheme</th>
                                        <th colspan="<?php echo $value['return_count'];?>" style="vertical-align: middle;width: 45%;">Performance
                                        </th>
                                      </tr>
                                      <tr>
                                        <?php if($value['day1']){ ?> 
                                        <th style="vertical-align: middle;">1&nbsp;Day</th>
                                        <?php } ?>
                                        <?php if($value['day7']){ ?>
                                        <th style="vertical-align: middle;">7&nbsp;Day</th>
                                        <?php } ?>
                                        <?php if($value['month1']){ ?>
                                        <th style="vertical-align: middle;">1&nbsp;Month</th>
                                        <?php } ?>
                                        <?php if($value['month3']){ ?>
                                        <th style="vertical-align: middle;">3&nbsp;Month</th>
                                        <?php } ?>
                                        <?php if($value['month6']){ ?>
                                        <th style="vertical-align: middle;">6&nbsp;Month</th>
                                        <?php } ?>
                                        <?php if($value['year1']){ ?>
                                        <th style="vertical-align: middle;">1&nbsp;Year</th>
                                        <?php } ?>
                                        <?php if($value['year3']){ ?>
                                        <th style="vertical-align: middle;">3&nbsp;Year</th>
                                        <?php } ?>
                                        <?php if($value['year5']){ ?>
                                        <th style="vertical-align: middle;">5&nbsp;Year</th>
                                        <?php } ?>
                                        <?php if($value['year10']){ ?>
                                        <th style="vertical-align: middle;">10&nbsp;Year</th>
                                        <?php } ?>
                                        
                                      </tr>
                                    </thead>
                                    <tbody>                                
                                        <tr>
                                            <td style="vertical-align: middle;width: 12%;">
                                                <?php echo isset($value["asset_type"])?$value["asset_type"]:"";?>
                                            </td>
                                            <td style="vertical-align: middle;width: 13%;"><?php echo isset($value["classname"])?$value["classname"]:"";?></td>
                                            <td style="text-align: left;vertical-align: middle;width: 35%;"><?php echo isset($value["s_name"])?$value["s_name"]:"";?></td>
                                            <?php if($value['day1']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["oneday"])?$value["oneday"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['day7']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["oneweek"])?$value["oneweek"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['month1']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["onemonth"])?$value["onemonth"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['month3']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["threemonth"])?$value["threemonth"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['month6']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["sixmonth"])?$value["sixmonth"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['year1']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["oneyear"])?$value["oneyear"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['year3']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["threeyear"])?$value["threeyear"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['year5']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["fiveyear"])?$value["fiveyear"]:"-";?></td>
                                            <?php } ?>
                                            <?php if($value['year10']){ ?>
                                            <td style="text-align: center;vertical-align: middle;width: <?php echo $width_per;?>%;"><?php echo isset($value["tenyear"])?$value["tenyear"]:"-";?></td>
                                            <?php } ?>
                                        </tr>
                                        @if($value['category_checkbox'])                        
                                            <tr>
                                                <td colspan="3" style="text-align: left;vertical-align: middle;"><?php echo isset($value["classname"])?$value["classname"]:"";?></td>
                                                <?php if($value['day1']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_oneday"])?$value["category_oneday"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['day7']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_oneweek"])?$value["category_oneweek"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['month1']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_onemonth"])?$value["category_onemonth"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['month3']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_threemonth"])?$value["category_threemonth"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['month6']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_sixmonth"])?$value["category_sixmonth"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['year1']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_oneyear"])?$value["category_oneyear"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['year3']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_threeyear"])?$value["category_threeyear"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['year5']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_fiveyear"])?$value["category_fiveyear"]:"-";?></td>
                                                <?php } ?>
                                                <?php if($value['year10']){ ?>
                                                <td style="text-align: center;vertical-align: middle;"><?php echo isset($value["category_tenyear"])?$value["category_tenyear"]:"-";?></td>
                                                <?php } ?>
                                            </tr>
                                        @endif
                                            
                                        <tr>
                                            <td colspan="12" class="text-left" style="vertical-align: middle;">
                                                 <?php echo isset($value["comments"])?$value["comments"]:"";?>
                                            </td>
                                        </tr>
                                    </tbody>
                                
                                </table>
                            </div>
                            <?php } ?>
    
                            <p class="text-left">*Mutual Fund investments are subject to market risk, read all scheme related document carefully.</p>
    
                        @endif
    
                        @if($non_mutual_fund)
                            <h1 class="midheading">Non Mutual Fund</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <thead>
                                      <tr>
                                        <th style="text-align: center;vertical-align: middle;width: 28%;">Product</th>
                                        <th style="text-align: center;vertical-align: middle;">Comments</th>
                                      </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($non_mutual_fund_list as $key => $value)
                                            
                                            <tr>
                                                <td style="vertical-align: middle;width: 28%;">{{isset($value['name'])?$value['name']:""}}</td>
                                                <td style="vertical-align: middle;min-height: 40px;">{{$value['comments']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
    
                        @endif
    
                        @if($insurance)
                            <h1 class="midheading">Insurance</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;vertical-align: middle;width: 24%;">Product Type</th>
                                            <th style="text-align: center;vertical-align: middle;width: 18%;">Product Name</th>
                                            <th style="text-align: center;vertical-align: middle;">Comments</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($insurance_list as $key => $value)
                                            <tr>
                                                <td style="vertical-align: middle;width: 24%;">{{isset($value['name'])?$value['name']:""}}</td>
                                                <td style="vertical-align: middle;width: 18%;">{{$value['user']}}</td>
                                                <td style="vertical-align: middle;min-height: 40px;">{{$value['comments']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-left">*Mutual Fund investments are subject to market risk, read all scheme related document carefully.</p>
    
                        @endif
                        
                        
    
                        @include('frontend.calculators.suggested.output')
                    </div>
                    
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.review_of_existing_investment_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput">Save & Merge with Sales Presenters</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.review_of_existing_investment_output_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection

