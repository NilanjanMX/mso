@extends('layouts.frontend')

@section('js_after')
    <script>
        var global_star_checked = "{{asset('')}}/img/star_icon-checked.png";
        var global_star_unchecked = "{{asset('')}}/img/star_icon-unchecked.png";

        var client_list = $('#client_list').dataTable({
            "searching": true,   
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10,50,100, 200, 500, -1], [10,50,100, 200, 500, "All"]],
            'iDisplayLength': -1
        });


        function checkStarImage(type,index){
            var star_value;
            if(type == 1){
                star_value = 1;
                document.getElementById("star_img_1_"+index).style.display = "block";
                document.getElementById("star_img_2_"+index).style.display = "none";
            }else{
                star_value = 0;
                document.getElementById("star_img_1_"+index).style.display = "none";
                document.getElementById("star_img_2_"+index).style.display = "block";
            }

            document.getElementById("span_"+index).innerHTML = star_value;

            $.ajax({
                url: "{{ url('/calculators/star') }}",
                method: 'get',
                data: {
                    "id":index,
                    "star":star_value
                },
                success: function (result) {
                    var table = $('#client_list').dataTable();
                    table.fnDestroy();
                    table = $('#client_list').dataTable({
                        "searching": true,   
                        "order": [[ 0, "desc" ]],
                        "lengthMenu": [[10,50,100, 200, 500, -1], [10,50,100, 200, 500, "All"]],
                        'iDisplayLength': -1
                    });

                    renderFilter();
                }
            });
        }

        function renderFilter(){
            var roleDropdown = "";
            roleDropdown = roleDropdown+"<div class='dataTables_filter' style='margin-right: 20px;'>";
            roleDropdown = roleDropdown+"<select style='background: #fff;border-radius: 0;border: 1px solid #dce1e4;box-shadow: none!important;font-size: 13px;padding: 6px 10px!important;' name='star_id' onchange='onChangeStarFilter();' id='star_id' >";
            roleDropdown = roleDropdown+"<option value=''>All</option>";
            roleDropdown = roleDropdown+"<option value='1'>Star</option>";
            roleDropdown = roleDropdown+"<option value='2'>Not Star</option>";
            roleDropdown = roleDropdown+"</select>";
            roleDropdown = roleDropdown+"</div>";
            $( ".dataTables_filter" ).after(roleDropdown);
        }

        $( document ).ready(function() {
            console.log( "ready!" );
            renderFilter();
        });



        function onChangeStarFilter(){
          var val = document.getElementById('star_id').value;
          console.log(val);
          if(val != 0){
            if(val == 1){
                client_list.api().column(0)
                    .search('1')
                    .draw();
            }else{
                client_list.api().column(0)
                    .search('0')
                    .draw();
            }
          }else{
            client_list.api().column(0)
                .search('', true, false )
                .draw();
          }
        }

        function openYouTudeVideo(id,name) {
            document.getElementById("calculator_video_header").innerHTML = name;
            document.getElementById("calculator_video_body").src = "https://www.youtube.com/embed/"+id;
            $("#calculator_video_modal").modal("show");
        }

        function closeYouTudeVideo(){
            document.getElementById("calculator_video_body").src = "";
            $("#calculator_video_modal").modal("hide");

        }
    </script>
@endsection
<style>
    .tablecalall {
        border: 1px solid #b5b3b3 !important;
        border-bottom: 0;
        width: 99% !important;
    }
    .tablecalall thead tr th {
        background: #25a8e0;
        color:#fff;
        font-size: 14px;
        padding: 15px 18px !important;
    }
    .tablecalall tbody tr:nth-child(even) {background: #f0f1f6}
    .tablecalall tbody tr td, .tablecalall thead tr th {
        vertical-align: middle;
        border-bottom: 1px solid #b5b3b3;
        font-size: 13px;
    }
    .tablecalall tbody tr td + td, .tablecalall thead tr th + th {
        border-left: 1px solid #b5b3b3;
    }
    #client_list_filter input {
        border: 1px solid #ccc;
        margin-right: 6px;
    }
</style>
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
                    <div class="rt-pnl">
                        <h2 class="headline">All &nbsp;</h2>
                        <div class="rt-btn-prt">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive mt-2">
                                    <table class="tablecalall tablecalallHideLastCol" id="client_list">
                                        <thead>
                                        <tr>
                                            <th scope="col" style="text-align: left;">
                                                <img src="{{asset('')}}/img/star_icon-header.png" style="width: 20px;">
                                            </th>
                                            <th scope="col">Name of Calculator</th>
                                            <th scope="col">Type of Calculator</th>
                                            <th scope="col">Available in</th>
                                            <th scope="col" data-sortable="false">Sample Reports</th>
                                            <th scope="col" data-sortable="false">How to Use</th>
                                            <th scope="col" data-sortable="false">Case Study</th>
                                            <th scope="col" data-sortable="false">How to use  Video</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($calculator_list) && count($calculator_list)>0)
                                            @foreach($calculator_list as $key => $value)
                                                <tr>
                                                    <td style="text-align: left;">
                                                        <span id="span_{{$value->id}}" style="display:none;">{{$value->is_checked}}</span>
                                                        @if($value->is_checked)
                                                            <img id="star_img_1_{{$value->id}}" src="{{asset('')}}/img/star_icon-checked.png" onclick="checkStarImage(2,'{{$value->id}}');" style="width: 20px;">
                                                            <img id="star_img_2_{{$value->id}}" src="{{asset('')}}/img/star_icon-unchecked.png" onclick="checkStarImage(1,'{{$value->id}}');" style="width: 20px;display: none;">
                                                        @else
                                                            <img id="star_img_1_{{$value->id}}" src="{{asset('')}}/img/star_icon-checked.png" onclick="checkStarImage(2,'{{$value->id}}');" style="width: 20px;display: none;">
                                                            <img id="star_img_2_{{$value->id}}" src="{{asset('')}}/img/star_icon-unchecked.png" onclick="checkStarImage(1,'{{$value->id}}');" style="width: 20px;">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{url('')}}/{{$value->url}}">
                                                            {{$value->name}}
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        @foreach($value->category_list as $k1 => $v1)
                                                            @if($k1 ==0)
                                                                {{$v1->name}}
                                                            @else
                                                                , {{$v1->name}}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">{{$value->type}}</td>
                                                    <td class="text-center">
                                                        <a href="{{route('frontend.calculatorSampleReport',['type'=>'calculator','id'=>$value->id])}}" target="_blank">
                                                        <img class="img-fluid" src="{{asset('')}}/img/pdf_ic.png" alt="" />
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($value->how_to_use)
                                                            <a href="{{asset('')}}uploads/how_to_use/{{$value->how_to_use}}" target="_blank">
                                                                <img class="img-fluid" src="{{asset('')}}/img/pdf_ic.png" alt="" />
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($value->case_study_pdf)
                                                            <a href="{{asset('')}}uploads/case_study_pdf/{{$value->case_study_pdf}}" target="_blank">
                                                                <img class="img-fluid" src="{{asset('')}}/img/pdf_ic.png" alt="" />
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($value->youtube_video)
                                                          <a href="javascript:void(0);" onclick="openYouTudeVideo('{{$value->youtube_video}}','{{ str_ireplace( array( '\'', '"',
    ',' , ';', '<', '>' ), ' ', $value->name);}}')">
                                                                <img class="img-fluid" src="{{asset('')}}/img/video_icon-2.png" alt="" style="width: 25px;"/>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($value->status == 1)
                                                            <span class="badge badge-success" style="padding: 5px 10px;font-size: 11px;">Active</span>
                                                        @else
                                                            <span class="badge badge-danger" style="padding: 5px 10px;font-size: 11px;">Inactive</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div> -->
    </section>


    <div class="modal fade" id="calculator_video_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calculator_video_header"></h5>
                <button type="button" class="close" onclick="closeYouTudeVideo();">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="calculator_video_body" width="100%" height="292" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
      </div>
    </div>

    @include('frontend.calculators.modal')
    

@endsection
