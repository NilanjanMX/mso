@extends('layouts.frontend')

@section('js_after')
    
    <script type="text/javascript">
       
        


        function changeSelectAll(){
            if($("#select_all").attr('checked')){
                var tbody_list = document.getElementById("tbody_list").querySelectorAll('input[type=checkbox]');
                tbody_list.forEach(function(val){
                    $(val).attr('checked',true);
                });
            }else{
                var tbody_list = document.getElementById("tbody_list").querySelectorAll('input[type=checkbox]');
                tbody_list.forEach(function(val){
                    $(val).removeAttr('checked');
                });
            }
        }

        function clickDelete(){
            if(confirm("Are you sure?")){
                var tbody_list = document.getElementById("tbody_list").querySelectorAll('input[type=checkbox]:checked');

                if(tbody_list.length){
                    document.getElementById("trigger_form").submit();
                }else{
                    alert("Please select atleast one");
                }
            }
        }

    </script>

@endsection

@section('content')
<style type="text/css">
    .top-tab {
        margin-bottom: 61px;
    }
    /*.newsletter {*/
    /*    margin-top: 104px;*/
    /*    margin-bottom: -24px;*/
    /*}*/
    .stationery-btn .banner-btn {
        padding: 10px 15px !important;
    }
    

    .vidpos02 {
        left: -20px;
        top: 187px;
        width: 100px;
        }
    .vidpos04 {
        left: -53px;
        top: 580px;
    }
    .vidpos03 {
        right: 0;
        left: -30px;
        top: 1000px;
        width: 130px;
    }
    .vidpos05 {
        right: -65px;
        top: 1089px;
        width: 150px;
    }
    .vidpos06 {
        right: -65px;
        top: 530px;
        width: 150px;
    }
    .visp {
        right: -30px;
        top: 520px;
        width: 660px;
    }
    .conferencesTable .table tr:hover {
        background-color: #468ff61c;
        transition: all 0.5s;
    }
</style>
<!--<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />-->
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />-->
<!--<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />-->
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />-->

<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item shoppingCartBannaer">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">MSO Triggers</h2>
                    <p>Serve your clients with precision. Set triggers and get reminders for profit booking, buying, selling , switch, etc., based on various parameters.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/tiggerBanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div>

<section class="main-sec bodyResponsive">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="category-box categoryList">
                        @include('frontend.trigger.common')
                    </div>
                </div>
                
                <div class="col-md-12">
                    <form action="" method="get" id="trigger_form1">
                        <div class="input-group searchItemField mt-0">
                            <input type="text" name="search" class="form-control" placeholder="Looking for something? Type here for searching.." value="{{$search}}">
                            <button type="submit" name="" class="btn btnSearchItemGlass"><img class="img-fluid" src="{{asset('')}}img/searchItemGlass.png" alt=""></button>
                        </div>
                    </form>                
                    <form action="{{url('trigger/delete-all')}}" method="get" id="trigger_form">                    
                        <div class="tiggerListFilter">
                            <div class="tiggerListSelDel">
                                <div class="tiggerListSel">
                                    <div class="form-group form-check">
                                        <span>
                                            <label class="membershipPt">
                                                <input type="checkbox" name="select_all" id="select_all" onchange="changeSelectAll();">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="form-check-label" for="">Select All</label>
                                        </span>
                                    </div>
                                </div>
                                <div class="tiggerListSel tiggerListDel">
                                    <div class="form-group form-check">
                                        <a href="javascript:void(0);" onclick="clickDelete();">
                                            <img class="img-fluid" src="{{asset('')}}img/tiggerDel.png" alt="" style="margin-top: -2px;" />
                                            <span class="form-check-label ml-1" for="">Delete</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tiggerListTable table-responsive">
                            <table class="table">
                              <tbody id="tbody_list">
                                @foreach($result as $key => $value)
                                    @php
                                        $range = "<=";
                                        if($value->trigger_condition==1){
                                            $range = ">=";
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="tiggerListSel">
                                              <div class="form-group form-check">
                                                  <span>
                                                      <label class="membershipPt">
                                                          <input type="checkbox" name="trigger[]" id="trigger_{{$value->id}}" value="{{$value->id}}">
                                                          <span class="checkmark"></span>
                                                      </label>
                                                  </span>
                                              </div>
                                          </div>
                                        </td>
                                        <td>
                                            <div class="tiggerTableTitle1">{{$value->trigger_name}}</div>
                                            <div class="tiggerTableTitle1 tiggerTableTitle2">
                                                {{$value->s_name}} - {{$value->triggers_name}}
                                            </div>
                                            <div class="tiggerTableText">
                                              {{$value->remarks}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="tiggerTableValue">Trigger Value : {{$value->navrs}} Range: {{$range}}</div>
                                            <div class="tiggerTableValue">Set Date : {{date('d/m/Y', strtotime($value->created_at))}}</div>
                                            <div class="tiggerTableValue">Hit Date : {{($value->trigger_hit_date)?date('d/m/Y', strtotime($value->trigger_hit_date)):""}}</div>
                                        </td>
                                        <td>
                                            <div class="tiggerTableMore">
                                                <a href="{{ url('/trigger/delete') }}?id={{$value->id}}" onclick="return confirm('Are you sure?')"><img class="img-fluid" src="{{asset('')}}img/tiggerDel.png" alt="" /></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                    </form>
                </div>                
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="https://masterstroke.5gsoftware.net/public/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>


@endsection
