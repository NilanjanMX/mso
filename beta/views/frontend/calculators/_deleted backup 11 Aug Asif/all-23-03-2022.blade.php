@extends('layouts.frontend')

@section('js_after')
    <script>
        $('#client_list').dataTable({
            "searching": true,   
            "order": [[ 1, "asc" ]]
        });
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
                        <h2 class="headline">ALL &nbsp;</h2>
                        <div class="rt-btn-prt">
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive mt-2">
                                    <table class="tablecalall" id="client_list">
                                        <thead>
                                        <tr>
                                            <th scope="col" data-sortable="false">#</th>
                                            <th scope="col">Name of Calculator</th>
                                            <th scope="col">Type of Calculator</th>
                                            <th scope="col">Available in</th>
                                            <th scope="col" data-sortable="false">Sample Reports</th>
                                            <th scope="col" data-sortable="false">How to Use</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($calculator_list) && count($calculator_list)>0)
                                            @foreach($calculator_list as $key => $value)
                                                <tr>
                                                    <td><b>{{$key+1}}.<b></td>
                                                    <td>{{$value->name}}</td>
                                                    <td class="text-center">
                                                        @foreach($value->category_list as $k1 => $v1)
                                                            <div>
                                                                {{$v1->name}}
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">{{$value->type}}</td>
                                                    <td class="text-center">
                                                        <a href="{{route('frontend.calculatorSampleReport',['type'=>'calculator','id'=>$value->id])}}" target="_blank">
                                                        <img class="img-fluid" src="{{asset('')}}/img/pdf_ic.png" alt="" />
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{url('how_to_use/')}}/{{$value->how_to_use}}" target="_blank">Link</a>
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

    @include('frontend.calculators.modal')
    

@endsection
