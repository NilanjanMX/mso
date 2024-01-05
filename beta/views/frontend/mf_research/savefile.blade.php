@extends('layouts.frontend')

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">SAVED FILES</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.mf_scanner.top_sidebar')
                <div class="col-md-12">
                    <div class="rt-pnl">
                        <!-- <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" tyle="width: 5%">SL</th>
                                            <th scope="col" style="width: 30%">TITLE</th>
                                            <th scope="col" style="width: 25%" >TYPE</th>
                                            <th scope="col" style="width: 10%">DATE</th>
                                            <th scope="col" style="width: 30%">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($saved_file_lists) && count($saved_file_lists)>0)
                                            @php
                                                $i = $saved_file_lists->perPage() * ($saved_file_lists->currentPage() - 1);
                                                $i++
                                            @endphp
                                            @foreach($saved_file_lists as $save_file)
                                                <tr>
                                                    <th>{{$i++}}.</th>
                                                    <td>{{$save_file->name}}</td>
                                                    <td>{{$save_file->mf_researche_name}}</td>
                                                    <td>{{date('M d, Y', strtotime($save_file->created))}}</td>
                                                    <td>
                                                        @if($save_file->mf_researche_id == 6 || $save_file->mf_researche_id == 7)
                                                            <a href="{{$save_file->url}}-edit?id={{$save_file->id}}" class="btn btn-primary btn-sm" title="Edit" style="background: #666667;border-color: #666667;">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
    
                                                            <a href="{{$save_file->url}}-view?id={{$save_file->id}}" class="btn btn-primary btn-sm" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            
                                                            @if(isset($permission[$save_file->mf_researche_id]['is_download']))
                                                                <a href="javascript:void(0);" onclick="openModal('{{$save_file->url}}','{{$save_file->id}}');" class="btn btn-primary btn-sm" title="Download" style="background: #292f62;border-color: #292f62;">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @else
                                                                <a href="{{$save_file->url}}-download?id={{$save_file->id}}" class="btn btn-primary btn-sm" title="Download" style="background: #292f62;border-color: #292f62;">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <?php if($save_file->type == 1){ ?>
                                                                <a href="{{route('frontend.UpdateMFScanner',['id'=>$save_file->id])}}" class="btn btn-primary btn-sm" title="Edit" style="background: #666667;border-color: #666667;">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </a>
                                                            <?php }else if($save_file->type == 2){ ?>
                                                                <a href="{{route('frontend.mf_update_compare',['id'=>$save_file->id])}}" class="btn btn-primary btn-sm" title="Edit" style="background: #666667;border-color: #666667;">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </a>
                                                            <?php }else{ ?>
                                                                    
                                                            <?php } ?>
                                                            <?php if($save_file->type == "mf-investment-analysis"){ ?>
                                                                <a href="{{route('frontend.investment_analysis_pdf',['id'=>$save_file->id,'type'=>'V'])}}" class="btn btn-primary btn-sm" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            <?php }else if($save_file->type == "mf-debt-held"){ ?>
                                                                <a href="{{route('frontend.debt_held_pdf',['id'=>$save_file->id,'type'=>'V'])}}" class="btn btn-primary btn-sm" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            <?php }else{ ?>
                                                                <a href="{{route('frontend.mf_view_saved_file_details',['id'=>$save_file->id])}}" class="btn btn-primary btn-sm" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            <?php } ?>
                                                            <?php if($save_file->type == "mf-investment-analysis"){ ?>
                                                                <a href="{{route('frontend.investment_analysis_pdf',['id'=>$save_file->id,'type'=>'D'])}}" style="background: #292f62;border-color: #292f62;" class="btn btn-danger btn-sm remove-save-file" title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            <?php }else if($save_file->type == "mf-debt-held"){ ?>
                                                                <a href="{{route('frontend.debt_held_pdf',['id'=>$save_file->id,'type'=>'D'])}}" style="background: #292f62;border-color: #292f62;" class="btn btn-danger btn-sm remove-save-file" title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            <?php }else{ ?>
                                                                <a href="{{route('frontend.mf_download_saved_file',['id'=>$save_file->id])}}" style="background: #292f62;border-color: #292f62;" class="btn btn-danger btn-sm remove-save-file" title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            <?php } ?>
                                                        
                                                        @endif

                                                        
                                                        
                                                        <a href="{{route('frontend.mf_delete_saved_file',['id'=>$save_file->id])}}" class="btn btn-danger btn-sm remove-save-file" title="Remove" onclick="javascript:return confirm('Do you really want to delete?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    @if(isset($saved_file_lists) && count($saved_file_lists)>0)
                                        {{$saved_file_lists->links()}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div>
    </section>


    <style>
        #errorAfterLoginModal .modal-content {
            border-radius: 15px !important;
        }
        #errorAfterLoginModal .successHead {
            background: #16a1dc;
            text-align: center;
            padding: 20px 0 10px 0;
            border-radius: .7rem .7rem 0 0;
        }
        #errorAfterLoginModal .successHead h3 {
            margin: 0;
            padding: 0;
            color: #fff;
        }
        #errorAfterLoginModal .successHead button.close {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        #errorAfterLoginModal #success_model_body {
            color: #444444;
            font-size: 12px;
            text-align: center;
            margin-top: 0.7rem;
            margin-bottom: 0.5rem;
        }
        #errorAfterLoginModal #successModal .modal-footer {
            justify-content: center;
        }
        #errorAfterLoginModal .btnblue {
            padding: .5rem 2rem;
            border-radius: 1.5rem;
            background: #141f55;
        }
        @media (max-width: 690px) {
          #errorAfterLoginModal .btnblue {
            padding: 0.5rem 0.5rem;
            font-size: 12px;
          }
          #errorAfterLoginModal .successHead h3 {
            font-size: 20px;
          }
        }
    </style>

    <div class="modal fade" id="errorAfterLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="successHead text-center">
                <h3>Choose Download Option</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer text-center" style="justify-content: center;">
                <a href="javascript:void(0);" class="btn btn-secondary btnblue" id="with_cover_page" onclick="withCoverPage();" >With Cover Page</a>
                <a href="javascript:void(0);" class="btn btn-secondary btnblue" id="without_cover_page" onclick="withoutCoverPage();" >Without Cover Page</a>
            </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="saveList" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Enter the branding details.</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <form method="get" action="" id="save_form_data">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="form-group">
                        <input type="text" name="pdf_title_line1" class="form-control" id="pdf_title_line1" placeholder="PDF Title Line 1" value="" maxlength="22">
                        
                            <div class="invalid-feedback" id="invalid-feedback" role="alert"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="pdf_title_line2" class="form-control" id="pdf_title_line2" placeholder="PDF Title Line 2" value="" maxlength="22">
                        
                            <div class="invalid-feedback" id="invalid-feedback" role="alert"></div>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="client_name" class="form-control" id="pdf_client_name" placeholder="Client Name" value="">
                        
                            <div class="invalid-feedback" id="invalid-feedback" role="alert"></div>
                    </div>
                    <button  type="button" class="btn btn-primary btn-round" onclick="downloadWithCover();">Download</button>
                </form>
          </div>
        </div>
      </div>
    </div>



    <script type="text/javascript">
        
        function openModal(url,id){
            //alert(url+'-download?id='+id);
           document.getElementById("save_form_data").action = url+'-download?id='+id;
            document.getElementById("id").value = id;
            document.getElementById("pdf_title_line1").value = "";
            document.getElementById("pdf_title_line2").value = "";
            document.getElementById("pdf_client_name").value = "";
            $('#errorAfterLoginModal').modal('show');
        }
        
        function withCoverPage(){
            $('#errorAfterLoginModal').modal('hide');
            $('#downloadModal').modal('show');
        }

        function withoutCoverPage(){
            
            document.getElementById("save_form_data").submit();
        }

        function downloadWithCover(){
            var pdf_title_line1 = document.getElementById("pdf_title_line1").value;
            var pdf_title_line2 = document.getElementById("pdf_title_line2").value;
            var pdf_client_name = document.getElementById("pdf_client_name").value;


            document.getElementById("save_form_data").submit();
        }


    </script>

@endsection
