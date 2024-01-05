@extends('layouts.frontend')

@section('js_after')
    
    <script type="text/javascript">
       
        var global_result = <?php echo json_encode($result);?>;
        var temp_result = global_result;

        function renderList(){
            var iHtml = ``;
            var i = 1;
            temp_result.forEach(function(val){
                var created_at = "";
                var created_at_a = val.created_at.split(" ");
                var created_at_a = created_at_a[0].split("-");
                created_at = created_at_a[2]+"/"+created_at_a[1]+"/"+created_at_a[0];
                console.log(created_at_a);
                var range = "<=";
                if(val.trigger_condition==1){
                    range = ">=";
                }

                var iHtml1 = ``;
                var iHtml2 = ``;

                if(val.mso_id){
                    if(val.is_email_hit){
                        iHtml1 = `<div class="tiggerTableEdit">
                            <a href="{{ url('/trigger/edit') }}?id=`+val.id+`"><img class="img-fluid" src="{{asset('')}}img/tiggerTableEdit.png" alt="" /></a>
                        </div>`;
                    }
                }
                
                if(val.is_email_hit){
                    iHtml2 = iHtml2+`<button type="button" class="btn btn-danger" style="padding: 2px 5px;font-size: 11px;">Hit</button>`;
                }

                iHtml = iHtml + `<tr>
                            <td>
                                <div class="tiggerListSel">
                                  <div class="form-group form-check">
                                      <span>
                                          <label class="membershipPt">
                                              <input type="checkbox" name="trigger[]" id="trigger_`+val.id+`" value="`+val.id+`">
                                              <span class="checkmark"></span>
                                          </label>
                                      </span>
                                  </div>
                              </div>
                            </td>
                            <td>
                                <div class="tiggerTableTitle1">`+val.trigger_name+`</div>
                                <div class="tiggerTableTitle1 tiggerTableTitle2">`+val.s_name+` - `+val.triggers_name+`</div>
                                <div class="tiggerTableText">
                                  `+val.remarks+`
                                </div>
                            </td>
                            <td>
                                <div class="tiggerTableValue">Value : `+val.navrs+` Range: `+range+`</div>
                                <div class="tiggerTableValue">Set Date : `+created_at+`</div>
                                `+iHtml2+`
                            </td>
                            <td>
                                <div class="tiggerTableEditMore">
                                    `+iHtml1+`
                                    <div class="tiggerTableEdit">
                                        <a href="{{ url('/trigger/edit') }}?id=`+val.id+`"><img class="img-fluid" src="{{asset('')}}img/tiggerTableEdit.png" alt="" /></a>
                                    </div>
                                    <div class="tiggerTableMore">
                                        <a href="{{ url('/trigger/delete') }}?id=`+val.id+`" onclick="return confirm('Are you sure?')"><img class="img-fluid" src="{{asset('')}}img/tiggerDel.png" alt="" /></a>
                                    </div>
                                </div>
                            </td>
                          </tr>`;

                i = i+1;
            });
            document.getElementById("tbody_list").innerHTML = iHtml;
        }

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


        function searchValue(){
            var search = document.getElementById("search").value;
            console.log(search);
            if(search){
                search = search.toLowerCase();
                console.log(search);
                temp_result = [];
                global_result.forEach((val)=>{
                    var s_name = val.s_name.toLowerCase();
                    var trigger_name = val.trigger_name.toLowerCase();
                    var trigger_type = val.trigger_type.toLowerCase();
                    var remarks = val.remarks.toLowerCase();
                    var navrs = val.navrs.toLowerCase();
                    var range = 0;
                    if(search == ">="){
                        if(val.trigger_condition == 1){
                            range = 1;
                        }
                    }else if(search == ">"){
                        if(val.trigger_condition == 1){
                            range = 1;
                        }
                    }else if(search == "<="){
                        if(val.trigger_condition == 2){
                            range = 1;
                        }
                    }else if(search == "<"){
                        if(val.trigger_condition == 2){
                            range = 1;
                        }
                    }else if(search == "="){
                        range = 1;
                    }
                    console.log(trigger_name.search(search));
                    console.log(trigger_type.search(search));
                    console.log(remarks.search(search));
                    console.log(s_name.search(search));
                    console.log(navrs.search(search));
                    if(trigger_name.search(search) !=-1 || trigger_type.search(search) !=-1 || remarks.search(search) !=-1 || s_name.search(search) !=-1 || navrs.search(search) !=-1 || range){
                        temp_result.push(val);
                    }
                })
            }else{
                temp_result = global_result;
            }
            renderList();
        }

        renderList();

        @if($message_text)
            document.getElementById("trigger_success_model_body").innerHTML = "{{$message_text}}";
            $("#triggerAlertModal").modal("show");
        @endif

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
                    <h2 class="py-4">Create your own Custom MSO Triggers</h2>
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
                            <input type="text" name="search" id="search" class="form-control" placeholder="Looking for something? Type here for searching.." value="" onkeyup="searchValue();">
                            <button type="button" name="" class="btn btnSearchItemGlass" onclick="searchValue();"><img class="img-fluid" src="{{asset('')}}img/searchItemGlass.png" alt=""></button>
                        </div>
                    </form>  
                    <div>
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
                                
                                <!--<div class="tiggerListDrop tiggerListSel">-->
                                <!--    <label class="form-check-label ml-0" for="">View results</label>-->
                                <!--    <select class="form-control" id="" name="">-->
                                <!--        <option>25 per page</option>-->
                                <!--        <option>50 per page</option>-->
                                <!--    </select>-->
                                <!--</div>-->
                            </div>
                            <div class="tiggerListTable table-responsive">
                                <table class="table">
                                  <tbody id="tbody_list">

                                  </tbody>
                                </table>
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<div class="modal fade" id="triggerAlertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="trigger_success_model_body" style="text-align: center;font-size: 16px;"></p>
            </div>
            <div class="modal-footer text-center" style="justify-content: center;">
                <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">OK</button>
            </div>
        </div>
      </div>
    </div>
@endsection
