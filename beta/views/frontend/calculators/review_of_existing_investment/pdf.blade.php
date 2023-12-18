<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result</title>
    @include('frontend.calculators.common.pdf_style')
    <style>
    .styleApril table th {
        border-bottom: 1px solid #458ff6 !important;
    }
    .styleApril table th.performanceBorder {
        border-bottom: none;
    }
    .styleApril table tr.performanceBorder2 th {
        border-top: none;
    }
    </style>
</head>
<body class="styleApril">
    @include('frontend.calculators.common.header')
    <main class="mainPdf">
        
        <div style="padding: 0px 0px;">
            
            <h1 class="pdfTitie">
                Review of Investments @if(isset($client_name) && !empty($client_name)) <br> For {{$client_name?$client_name:''}}  @else  @endif
            </h1>
            
            @php $page_change_count = 0; @endphp

            @if($mutual_fund)
                <h1 class="pdfTitie">Mutual Fund</h1>
                <?php foreach($mutual_fund_list as $key => $value) { 
                    $width_per = 40/$value['return_count'];
                    ?>
                    @php $page_change_count = $page_change_count + 2; @endphp
                    <?php if($key && ($key)%4 == 0){ ?>
                        @php $page_change_count = 0; @endphp
                        </div>
                        
                            @php
                                $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','review_of_existing_investment')->first();
                                if(!empty($note_data2)){
                                @endphp
                                {!!$note_data2->description!!}
                            @php } @endphp
                            
            </main>

                        @include('frontend.calculators.common.watermark')
                        @if($footer_branding_option == "all_pages")
                            @include('frontend.calculators.common.footer')
                        @endif
                        <div class="page-break"></div>
                
                        @include('frontend.calculators.common.header')
                    <main class="mainPdf">
                        <div style="padding: 0px 0px;">
                        <h1 class="pdfTitie">Mutual Fund</h1>
                    <?php } ?>
                    <div class="roundBorderHolder" style="margin-bottom: 30px;">
                        <table>       
                            <thead>
                              <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;width: 12%;">Asset&nbsp;Class</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;width: 13%;">Category</th>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;width: 30%;">Scheme</th>
                                <th class="performanceBorder" colspan="<?php echo $value['return_count'];?>" style="text-align: center;vertical-align: middle;width: 45%;">Performance
                                </th>
                              </tr>
                              <tr class="performanceBorder2">
                                <?php if($value['day1']){ ?> 
                                <th style="text-align: center;vertical-align: middle;">1&nbsp;Day</th>
                                <?php } ?>
                                <?php if($value['day7']){ ?>
                                <th style="text-align: center;vertical-align: middle;">7&nbsp;Day</th>
                                <?php } ?>
                                <?php if($value['month1']){ ?>
                                <th style="text-align: center;vertical-align: middle;">1&nbsp;Month</th>
                                <?php } ?>
                                <?php if($value['month3']){ ?>
                                <th style="text-align: center;vertical-align: middle;">3&nbsp;Month</th>
                                <?php } ?>
                                <?php if($value['month6']){ ?>
                                <th style="text-align: center;vertical-align: middle;">6&nbsp;Month</th>
                                <?php } ?>
                                <?php if($value['year1']){ ?>
                                <th style="text-align: center;vertical-align: middle;">1&nbsp;Year</th>
                                <?php } ?>
                                <?php if($value['year3']){ ?>
                                <th style="text-align: center;vertical-align: middle;">3&nbsp;Year</th>
                                <?php } ?>
                                <?php if($value['year5']){ ?>
                                <th style="text-align: center;vertical-align: middle;">5&nbsp;Year</th>
                                <?php } ?>
                                <?php if($value['year10']){ ?>
                                <th style="text-align: center;vertical-align: middle;">10&nbsp;Year</th>
                                <?php } ?>
                                
                              </tr>
                            </thead>
                            <tbody>                                
                                <tr>
                                    <td style="text-align: left;vertical-align: middle;width: 12%;">
                                        <?php echo isset($value["asset_type"])?$value["asset_type"]:"";?>
                                    </td>
                                    <td style="text-align: left;vertical-align: middle;width: 13%;"><?php echo isset($value["classname"])?$value["classname"]:"";?></td>
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
                                    <td colspan="12" style="text-align: left;vertical-align: middle;">
                                         <?php echo isset($value["comments"])?$value["comments"]:"";?>
                                    </td>
                                </tr>
                            </tbody>
                        
                        </table>
                    </div>
                <?php } ?>
                
                    
                

                <p style="text-align: left;margin-top: -15px;">*Mutual Fund investments are subject to market risk, read all scheme related document carefully.</p>

            @endif

            @if($non_mutual_fund)
                <h1 class="pdfTitie">Non Mutual Fund</h1>
                <div class="roundBorderHolder">
                    <table>
                        <thead>
                          <tr>
                            <th style="text-align: center;vertical-align: middle;width: 28%;">Product</th>
                            <th style="text-align: center;vertical-align: middle;">Comments</th>
                          </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($non_mutual_fund_list as $key => $value)
                                <?php if($page_change_count && ($page_change_count)%9 == 0){ ?>
                                    @php $page_change_count = 0; @endphp
                                        
                                        
                                    
                                    
                                    </tbody>
                                    </table>
                    </div>
                    @php
                                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','review_of_existing_investment')->first();
                                            if(!empty($note_data2)){
                                            @endphp
                                            {!!$note_data2->description!!}
                                        @php } @endphp
                </div>
                </main>
                                        @include('frontend.calculators.common.watermark')
                                        @if($footer_branding_option == "all_pages")
                                            @include('frontend.calculators.common.footer')
                                        @endif
                                    
                                    
                                        <main class="mainPdf">
                                            
                                
                                <div class="page-break"></div>
                                @include('frontend.calculators.common.header')
                                
                                <div style="padding: 0px 0px;">
                                <h1 class="pdfTitie">Non Mutual Fund</h1>
                                
                                <div class="roundBorderHolder withBluebarMrgn">
                                    <table>
                                        <thead>
                                          <tr>
                                            <th style="text-align: center;vertical-align: middle;width: 28%;">Product</th>
                                            <th style="text-align: center;vertical-align: middle;">Comments</th>
                                          </tr>
                                        </thead>
                                        
                                        <tbody>
                                <?php } ?>
                                @php $page_change_count = $page_change_count + 1; @endphp
                                <tr>
                                    <td style="text-align: left;vertical-align: middle;width: 28%;">{{isset($value['name'])?$value['name']:""}}</td>
                                    <td style="text-align: left;vertical-align: middle;min-height: 40px;">{{$value['comments']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                                </div>

            @endif

            @if($insurance)
                <h1 class="pdfTitie">Insurance</h1>
                <div class="roundBorderHolder">
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: center;vertical-align: middle;width: 24%;">Product Type</th>
                                <th style="text-align: center;vertical-align: middle;width: 18%;">Product Name</th>
                                <th style="text-align: center;vertical-align: middle;">Comments</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($insurance_list as $key => $value)
                                <?php if($page_change_count && ($page_change_count)%9 == 0){ ?>
                                    @php $page_change_count = 0; @endphp
                                    
    
                                    @include('frontend.calculators.common.footer')
                                    
                                    </tbody>
                                    </table>
                </div>
                 @php
                                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','review_of_existing_investment')->first();
                                            if(!empty($note_data2)){
                                            @endphp
                                            {!!$note_data2->description!!}
                                        @php } @endphp
                                </div>
                                </main>
                                        @include('frontend.calculators.common.watermark')
                                        @if($footer_branding_option == "all_pages")
                                            @include('frontend.calculators.common.footer')
                                        @endif
                                    
                                    
                                        <main class="mainPdf">
                                            
                                
                                <div class="page-break"></div>
                                @include('frontend.calculators.common.header')
                                
                                
                                <div style="padding: 0px 0px;">
                                <h1 class="pdfTitie">Insurance</h1>
                                <div class="roundBorderHolder withBluebarMrgn">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;vertical-align: middle;width: 24%;">Product Type</th>
                                                <th style="text-align: center;vertical-align: middle;width: 18%;">Product Name</th>
                                                <th style="text-align: center;vertical-align: middle;">Comments</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                <?php } ?>
                                @php $page_change_count = $page_change_count + 1; @endphp
                                <tr>
                                    <td style="text-align: left;vertical-align: middle;width: 24%;">{{isset($value['name'])?$value['name']:""}}</td>
                                    <td style="text-align: left;vertical-align: middle;width: 18%;">{{$value['user']}}</td>
                                    <td style="text-align: left;vertical-align: middle;min-height: 40px;">{{$value['comments']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </div>
        
            @php
                $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','review_of_existing_investment')->first();
                if(!empty($note_data2)){
                @endphp
                {!!$note_data2->description!!}
            @php } @endphp
        </main>
            
        
        @include('frontend.calculators.common.footer')
</body>
</html>