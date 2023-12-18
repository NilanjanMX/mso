@extends('layouts.frontend')
@section('js_after')

    <script type="text/javascript">
        function changeCover(type){
            if(type == 0){
                document.getElementById('pdf_title_line_id').style.display = "none";
            }else{
                document.getElementById('pdf_title_line_id').style.display = "block";
            }
        }
    </script>

@endsection
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">

                </div>
            </div>
        </div>
    </div>
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
</style>


    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    {!! $fileDetails !!}
                    
                    <div class="text-center">
                        <a href="{{route('frontend.download_saved_file_details',['id'=>$id])}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download</a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput">Save & Merge with Sales Presenters</a>
                    </div>
                    <p></p><p></p>
                </div>
            </div>
        </div>
        
    </section>

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
                    <form target="_blank" action="{{route('frontend.merge_sp_download_save_file')}}" method="get">
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
                        @if($is_cover)
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
