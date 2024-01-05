<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MF SCREENER</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    @include('frontend.mf_research.common.pdf_style')
</head>
<body>
<main style="width: 760px; margin-left: 20px;">
    @php
        $amf='AMFI-Registered Mutual Fund Distributor';
        $page_count = 2;
    @endphp

    @include('frontend.mf_research.common.pdf_header')
    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;"> {{$details->name}}</h1>
    </div>
    <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 5px;  padding-bottom: 14px;"><?php echo ($avg_data->class_name)?$avg_data->class_name:$avg_data->classname;?></div>

    <table class="table table-bordered text-center">
        <tbody>
            <tr>
               
                <th>
                    <strong>Scheme</strong>
                </th>
                <th>
                    <strong>1 Day </strong>
                </th>
                <th>
                    <strong>1 Week </strong>
                </th>
                <th>
                    <strong>1 Month </strong>
                </th>
                <th>
                    <strong>3 Month </strong>
                </th>
                <th>
                    <strong>6 Month </strong>
                </th>
                <th>
                    <strong>1 Year </strong>
                </th>
                <th>
                    <strong>2 Year </strong>
                </th>
                <th>
                    <strong>3 Year </strong>
                </th>
                <th>
                    <strong>5 Year </strong>
                </th>
                <th>
                    <strong>10 Year </strong>
                </th>
            </tr>
            <tr>
                <td style="width:30%;height:32px;" align="left">
                   <div style="text-align: left; font-weight: bold;">Category Average</div>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$oneday}}" align="right">
                    <?php echo ($avg_data->onedayret)?number_format((float)$avg_data->onedayret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$oneweek}}" align="right">
                    <?php echo ($avg_data->oneweekret)?number_format((float)$avg_data->oneweekret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$onemonth}}" align="right">
                    <?php echo ($avg_data->onemonthret)?number_format((float)$avg_data->onemonthret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$threemonth}}" align="right">
                    <?php echo ($avg_data->threemonthret)?number_format((float)$avg_data->threemonthret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$sixmonth}}" align="right">
                    <?php echo ($avg_data->sixmonthret)?number_format((float)$avg_data->sixmonthret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$oneyear}}" align="right">
                    <?php echo ($avg_data->oneyrret)?number_format((float)$avg_data->oneyrret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$twoyear}}" align="right">
                    <?php echo ($avg_data->twoyearret)?number_format((float)$avg_data->twoyearret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$threeyear}}" align="right">
                    <?php echo ($avg_data->threeyearret)?number_format((float)$avg_data->threeyearret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$fiveyear}}" align="right">
                    <?php echo ($avg_data->fiveyearret)?number_format((float)$avg_data->fiveyearret, 2, '.', ''):'-';?>
                </td>
                <td style="width:10%; text-align:right; font-weight: bold;{{$tenyear}}" align="right">
                    <?php echo ($avg_data->tenyret)?number_format((float)$avg_data->tenyret, 2, '.', ''):'-';?>
                </td>
            </tr>
            <?php if($criteria == 1|| $criteria == 3){ ?> 
                <?php $page_count = $page_count + 1;?>
                <tr>
                    <td colspan='11' style='text-align:center; background:#5dc563; color:#fff; font-size:13px;padding:7px;'>Best Performing Scheme</td>
                </tr>
                <?php foreach($best_data as $key=>$value){  ?>
                    <?php $page_count = $page_count + 1;?>
                    @if($page_count%22 == 0)
                        <?php $page_count = 1;?>
                            </tbody>
                        </table>
                        
                        @include('frontend.mf_research.common.watermark')
                        @if($footer_branding_option == "all_pages")
                            @include('frontend.mf_research.common.pdf_footer')
                        @endif
                        <div class="page-break"></div>
                        @include('frontend.mf_research.common.pdf_header')
                        <table class="table table-bordered text-center">
                            <tbody>
                                <tr>
                                    <td colspan='11' style='text-align:center; background:#5dc563; color:#fff; font-size:13px;padding:7px;'>Best Performing Scheme</td>
                                </tr>
                    @endif
                    <tr>
                        <td style="width:30%;height:32px;" align="left">
                           <div style="text-align: left;"> <?php echo ($value->s_name)?$value->s_name:'';?></div>
                        </td>
                        <td style="width:10%; text-align:right;{{$oneday}}" align="right">
                            <?php echo ($value->onedayret)?number_format((float)$value->onedayret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$oneweek}}" align="right">
                            <?php echo ($value->oneweekret)?number_format((float)$value->oneweekret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$onemonth}}" align="right">
                            <?php echo ($value->onemonthret)?number_format((float)$value->onemonthret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$threemonth}}" align="right">
                            <?php echo ($value->threemonthret)?number_format((float)$value->threemonthret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$sixmonth}}" align="right">
                            <?php echo ($value->sixmonthret)?number_format((float)$value->sixmonthret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$oneyear}}" align="right">
                            <?php echo ($value->oneyrret)?number_format((float)$value->oneyrret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$twoyear}}" align="right">
                            <?php echo ($value->twoyearret)?number_format((float)$value->twoyearret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$threeyear}}" align="right">
                            <?php echo ($value->threeyearret)?number_format((float)$value->threeyearret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$fiveyear}}" align="right">
                            <?php echo ($value->fiveyearret)?number_format((float)$value->fiveyearret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$tenyear}}" align="right">
                            <?php echo ($value->tenyret)?number_format((float)$value->tenyret, 2, '.', ''):'-';?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
                
            <?php if($criteria == 2|| $criteria == 3){ ?> 
                <?php $page_count = $page_count + 1;?>
                <tr>
                    <td colspan='11' style='text-align:center; background:#ff3b3b; color:#fff; font-size:13px;padding:7px;'>Worst Performing Scheme</td>
                </tr>
                <?php foreach($worst_data as $key=>$value){  ?>
                    <?php $page_count = $page_count + 1;?>
                    @if($page_count%22 == 0)
                        <?php $page_count = 1;?>
                            </tbody>
                        </table>
                        
                        @include('frontend.mf_research.common.watermark')
                        @if($footer_branding_option == "all_pages")
                            @include('frontend.mf_research.common.pdf_footer')
                        @endif
                        <div class="page-break"></div>
                        @include('frontend.mf_research.common.pdf_header')
                        <table class="table table-bordered text-center">
                            <tbody>
                                <tr>
                                    <td colspan='11' style='text-align:center; background:#ff3b3b; color:#fff; font-size:13px;padding:7px;'>Worst Performing Scheme</td>
                                </tr>
                    @endif
                    <tr>
                        <td style="width:30%;height:32px;" align="left">
                           <div style="text-align: left;"> <?php echo ($value->s_name)?$value->s_name:'';?></div>
                        </td>
                        <td style="width:10%; text-align:right;{{$oneday}}" align="right">
                            <?php echo ($value->onedayret)?number_format((float)$value->onedayret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$oneweek}}" align="right">
                            <?php echo ($value->oneweekret)?number_format((float)$value->oneweekret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$onemonth}}" align="right">
                            <?php echo ($value->onemonthret)?number_format((float)$value->onemonthret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$threemonth}}" align="right">
                            <?php echo ($value->threemonthret)?number_format((float)$value->threemonthret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$sixmonth}}" align="right">
                            <?php echo ($value->sixmonthret)?number_format((float)$value->sixmonthret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$oneyear}}" align="right">
                            <?php echo ($value->oneyrret)?number_format((float)$value->oneyrret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$twoyear}}" align="right">
                            <?php echo ($value->twoyearret)?number_format((float)$value->twoyearret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$threeyear}}" align="right">
                            <?php echo ($value->threeyearret)?number_format((float)$value->threeyearret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$fiveyear}}" align="right">
                            <?php echo ($value->fiveyearret)?number_format((float)$value->fiveyearret, 2, '.', ''):'-';?>
                        </td>
                        <td style="width:10%; text-align:right;{{$tenyear}}" align="right">
                            <?php echo ($value->tenyret)?number_format((float)$value->tenyret, 2, '.', ''):'-';?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>


    @if(isset($comments))
        @if($comments)
            @php $fsdf = str_replace("\r\n", "<br>",$comments); @endphp
            <div style="padding: 0 0%;margin-top:20px;">
                <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 15px;  padding-bottom: 14px;">Comment</div>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align:left;">{!!$fsdf!!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
                    
    <div style="margin-top:10px;">
        @php
            $note_data1 = \App\Models\Mfresearch_note::where('type','mf-best-worst')->first();
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
        @php } @endphp
    </div>
    <div style="margin-top:10px;">
        Report Date : {{date('d/m/Y')}}
    </div>

    @include('frontend.mf_research.common.watermark')
    @if($footer_branding_option == "all_pages")
        @include('frontend.mf_research.common.pdf_footer')
    @endif
</main>
</body>
</html>
