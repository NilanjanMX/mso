@extends('layouts.frontend')

@section('js_after')
<script>
    $('.remove-save-file').click(function (e) {
        e.preventDefault();
        //confirm("Are you sure want to delete this file?");
        if (confirm("Are you sure want to delete this file?")) {
            //return true;
            //alert("You pressed OK!");
            
            var uid = $(this).attr('data-uid');
            var fid = $(this).attr('data-fid');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $(this).closest("tr").hide();
            jQuery.ajax({
                url: "{{ route('frontend.remove_saved_file_details') }}",
                method: 'get',
                data: {
                    uid: uid,
                    fid: fid
                },
                success: function(result){
    
                }});
            
        }else {
            return false;
            //alert("You pressed Cancel");
        }
        
    });
</script>
@endsection

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
                    <div class="mb-4">
                        <ul class="filterby">
                            <li class=""><a class="btn active btn-round" href="{{route('frontend.view_saved_files')}}">Client Proposal</a></li>
                            <li class=""><a class="btn btn-round" href="{{route('frontend.view_categorywise_saved_files')}}">Category Wise</a></li>
                            <li class=""><a class="btn btn-round" href="{{route('frontend.view_mycustomlist_saved_files')}}">My Custom List</a></li>
                        </ul>
                    </div>
                    
                    <div class="rt-pnl">
                        <h2 class="headline">Saved Document</h2>
                      <!--   <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">SL</th>
                                            <th scope="col" style="width: 50%">TITLE</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">VALID TILL</th>
                                            <th scope="col" >ACTION</th>
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
                                                    <td>{{$save_file['title']}}</td>
                                                    <td>{{$save_file['created_at']->format('d/m/Y - h:i A')}}</td>
                                                    <td>{{date('d/m/Y', strtotime("+6 months", strtotime($save_file['created_at'])))}}</td>
                                                    <td>
                                                        <a href="{{route('frontend.view_saved_file_details',['id'=>$save_file['id']])}}" class="btn btn-primary btn-sm" title="View/Download">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm remove-save-file" title="Remove" data-uid="{{$save_file['user_id']}}" data-fid="{{$save_file['id']}}">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                        @if($save_file['data'])
                                                            <a href="{{route('frontend.lumsumInvestmentRequiredForTargetFutureValueEdit',['id'=>$save_file['id']])}}" class="btn btn-primary btn-sm" title="Edit" style="background: #666667;border-color: #666667;">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                        @endif
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

@endsection

