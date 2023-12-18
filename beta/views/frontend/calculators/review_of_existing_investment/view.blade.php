
@extends('layouts.frontend')
@section('js_after')
    
    @include('frontend.calculators.common.view_style')
    
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
                <div class="col-md-12 offset-md-0 text-center">
                                        
                    <div class="outputTableHolder">
                        <h1 class="midheading">Review of Investments @if(isset($client_name) && !empty($clientname)) <br> For {{$client_name}} @endif</h1>
    
    
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
                    
                    <div class="text-center viewBelowBtn">
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

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput" style="width: 320px;">Save & Merge with Sales Presenters</a>
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
                    <form target="_blank" action="{{route('frontend.lreview_of_existing_investment_MergeDownload')}}" method="get">
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

