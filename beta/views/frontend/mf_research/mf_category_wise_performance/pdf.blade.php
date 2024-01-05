<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$details->name}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    @include('frontend.mf_research.common.pdf_style')
</head>
<body>
<main style="width: 760px; margin-left: 20px;">
    @php
    $amf='AMFI-Registered Mutual Fund Distributor';
    @endphp

    @include('frontend.mf_research.common.pdf_header')

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">{{$details->name}}</h1>
    </div>
    <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 5px;  padding-bottom: 14px;">Category : <?php echo ($category_detail->class_name)?$category_detail->class_name:$category_detail->classname;?></div>
    <table class="mfliketbl text-center">
        <tbody>
            <tr>
               
                <th>
                    <strong>Scheme</strong>
                </th>
                <?php foreach($cr as $value){ ?>
                    <th>
                        <strong><?php echo $value['name'];?></strong>
                    </th>
                <?php } ?>
                <?php foreach($yr as $value){ ?>
                    <th>
                        <strong><?php echo $value['name'];?></strong>
                    </th>
                <?php } ?>
            </tr>
            <?php 
            $total_count = count($result);
            $row_count = 0;
            foreach($result as $key=>$value){ 
                $res = (array) $value;
                foreach($yrkey as $k1=>$val){
                    if($res[$val] == "0" || !$res[$val]){

                    }else{
                        $yr[$k1]['total'] = $yr[$k1]['total'] + $res[$val];
                        $yr[$k1]['count'] = $yr[$k1]['count'] + 1;
                    }
                }
                foreach($crkey as $k1=>$val){
                    if($res[$val] == "0" || !$res[$val]){

                    }else{
                        $cr[$k1]['total'] = $cr[$k1]['total'] + $res[$val];
                        $cr[$k1]['count'] = $cr[$k1]['count'] + 1;
                    }
                }
                if(in_array($value['schemecode'],$schemecode_ids) || count($schemecode_ids) == 0){
                    $row_count = $row_count +1; ?>
                    <?php if($row_count && ($row_count)%20 == 0){ ?>
                        </tbody>
                        </table>

                        @include('frontend.mf_research.common.watermark')
                        @if($footer_branding_option == "all_pages")
                            @include('frontend.mf_research.common.pdf_footer')
                        @endif
                        <div class="page-break"></div>

                        @include('frontend.mf_research.common.pdf_header')

                        <div style="padding: 0 15%;">
                            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">{{$details->name}}</h1>
                        </div>

                        <table class="mfliketbl text-center">
                            <tbody>
                                <tr>
                                   
                                    <th>
                                        <strong>Scheme</strong>
                                    </th>
                                    <?php foreach($cr as $value){ ?>
                                        <th>
                                            <strong><?php echo $value['name'];?></strong>
                                        </th>
                                    <?php } ?>
                                    <?php foreach($yr as $value){ ?>
                                        <th>
                                            <strong><?php echo $value['name'];?></strong>
                                        </th>
                                    <?php } ?>
                                </tr>
                    <?php } ?>
                    <tr>
                        <td style="width:30%;height:32px;" align="left">
                           <div style="text-align: left;"> <?php echo $res['s_name'];?></div>
                        </td>
                        <?php foreach($crkey as $k1=>$val){ ?>
                            <td align="right" style="text-align:right;">
                                <?php if($res[$val] == "0" || !$res[$val]){ ?>
                                    -
                                <?php } else { ?>
                                    {{number_format($res[$val], 2, '.', '')}}
                                <?php } ?>
                            </td>
                        <?php } ?>
                        <?php foreach($yrkey as $k1=>$val){ ?>
                            <td align="right" style="text-align:right;">
                                <?php if($res[$val] == "0" || !$res[$val]){ ?>
                                    -
                                <?php } else { ?>
                                    {{number_format($res[$val], 2, '.', '')}}
                                <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                    
            <?php } ?>
            <tr>
                <th style="text-align:left;"><strong>Category Average</strong></th>
                <?php foreach($cr as $value){ ?>
                    <th style="text-align:right;">
                        <strong><?php 
                            if($value['total']){
                                $avg = $value['total'] / $value['count'];
                                $avg = number_format($avg, 2, '.', '');
                            }else{
                                $avg = "-";
                            }
                            echo $avg;
                        ?></strong>
                    </th>
                <?php } ?>
                <?php foreach($yr as $value){ ?>
                    <th style="text-align:right;">
                        <strong><?php 
                            if($value['total']){
                                $avg = $value['total'] / $value['count'];
                                $avg = number_format($avg, 2, '.', '');
                            }else{
                                $avg = "-";
                            }
                            echo $avg;
                        ?></strong>
                    </th>
                <?php } ?>
                
            </tr>
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
            $note_data1 = \App\Models\Mfresearch_note::where('type','mf-category-wise-performance')->first();
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
        @php } @endphp
    </div>
    <div style="margin-top:10px;">
        Report Date : {{date('d/m/Y')}}
    </div>

    @if(count($schemecode_ids) > 1 && count($schemecode_ids) <= 5 && $is_graph)

        @include('frontend.mf_research.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.mf_research.common.pdf_footer')
        @endif

        <div class="page-break"></div>

        @include('frontend.mf_research.common.pdf_header')
        <h1 class="pdfTitie">Graphic Representation</h1>

        @if(isset($pie_chart2) && !empty($pie_chart2))
            <div style="text-align: center;">
                <img src="{{$pie_chart2}}" style="width: 800px">
            </div>
        @endif
       <div style="margin-top:10px;">
            @php
                $note_data1 = \App\Models\Mfresearch_note::where('type','mf-category-wise-performance')->first();
                if(!empty($note_data1)){
                @endphp
                {!!$note_data1->description!!}
            @php } @endphp
        </div>
        <div style="margin-top:10px;">
            Report Date : {{date('d/m/Y')}}
        </div>
    @endif

    @include('frontend.mf_research.common.watermark')
    @include('frontend.mf_research.common.pdf_footer')

</main>
</body>
</html>
