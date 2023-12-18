<div class="form-group row">
    <div class="col-sm-5">
        <div class="form-check form-check-inline">
            <label class="sqarecontainer">Add Suggested schemes for investment
                <input id="is_suggest" class="form-check-input" type="checkbox" name="suggest" value="1" @if(old('suggest')=='1') checked  @endif>
                <span class="checkmark"></span>
            </label>
        </div>
    </div>
    <div class="col-sm-7 include-performance-container">
        <div class="form-check form-check-inline">
            
            
            <!-- <label class="form-check-label" for="inlineRadio3"></label> -->
            <label class="checkLinecontainer">Create New
                <input class="form-check-input" type="radio" name="suggestedlist_type" id="inlineRadio3" value="createlist" checked>
                <span class="checkmark"></span>
            </label>   
        </div>
        <div class="form-check form-check-inline">
            <label class="checkLinecontainer">My Custom List
                <input class="form-check-input" type="radio" name="suggestedlist_type" id="inlineRadio4" value="customlist" >
                <span class="checkmark"></span>
            </label> 
        </div>
        <div class="form-check form-check-inline">
            <label class="checkLinecontainer">My Category Wise List
                <input class="form-check-input" type="radio" name="suggestedlist_type" id="inlineRadio5" value="categorylist">
                <span class="checkmark"></span>
            </label> 
        </div>
        <div class="form-check form-check-inline">
            <label class="checkLinecontainer">With Performance
                <input class="form-check-input" type="radio" name="include_performance" id="inlineRadio11" value="with_performance" @if(old('include_performance')=='without_performance') @else checked @endif>
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="checkLinecontainer">Without Performance
                <input class="form-check-input" type="radio" name="include_performance" id="inlineRadio12" value="without_performance" @if(old('include_performance')=='without_performance') checked @endif >
                <span class="checkmark"></span>
            </label>
        </div>
    </div>
</div>
<div class="include-performance-container">
    <div class="row">
        <div class="col-sm-12 createlist-suggested-scheme-container">
            <div class="table-responsive mt-3">
                
                <div class="roundTable">
                <table class="table table-bordered suggested-scheme-details">
                    <tbody>
                    <tr>
                        <th style="width: 30%;"><!-- <input type="checkbox" id="schemelist" class="rcheck_a" value="schemelist" checked> --></th>
                        <th style="width: 10%;">&nbsp;</th>
                        <th style="width: 5%;"></th>
                        <th>
                            <label class="checkcontainer">
                                <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="AUM">
                                <span class="checkmark"></span>
                            </label>    
                        </th>
                        <th>
                            <label class="checkcontainer">
                                <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="PERCHANGE">
                                <span class="checkmark"></span>
                            </label>  
                        </th>
                        <th>
                            <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="1WEEKRET"> 
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th>
                            <label class="checkcontainer">
                                <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="1MONTHRET"> 
                                <span class="checkmark"></span>
                            </label>
                            </th>
                        <th>
                            <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="3MONTHRET" checked> 
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th>
                            <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="6MONTHRET" checked> 
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th>
                            <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="1YEARRET" checked>
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th>
                            <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="3YEARRET" checked>
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <th>
                        <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="5YEARRET" checked>
                                <span class="checkmark"></span>
                        </label>
                        </th>
                        <th>
                        <label class="checkcontainer">
                        <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="10YEARRET">
                                <span class="checkmark"></span>
                        </label>
                         </th>
                        <th style="width: 9.2%;">
                        <label class="checkcontainer">
                            <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="INCRET">
                                <span class="checkmark"></span>
                        </label>
                        </th>
                        <th style="width: 18.4%;" colspan="2">
                        <label class="checkcontainer">
                        <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="TYPE">
                                <span class="checkmark"></span>
                        </label>
                        </th>
                        <!--<th style="width: 9.2%;">-->
                        <!--<label class="checkcontainer">-->
                        <!--    <input class="rcheck_a rcheck_c_n" type="checkbox" name="duration[]" value="AMOUNT">-->
                        <!--        <span class="checkmark"></span>-->
                        <!--</label>-->
                        <!--</th>-->
                        <th></th>
                    </tr>

                    <tr>
                        <th style="width: 20%;" rowspan="2">Scheme Name</th>
                        <th style="width: 10%;" rowspan="2">Asset Type</th>
                        <th style="width: 10%;" rowspan="2">Category</th>
                        <th style="width: 5%;" rowspan="2">AUM <br>(₹ Cr)</th>
                        <th style="text-align: center; width: 50%;" colspan="10">Return</th>
                        <th style="width: 5%;" rowspan="2">Type</th>
                        <th style="width: 5%; text-align: center;" rowspan="2">Amount <br>(<span style="color: #458ff6"> ₹ </span>)</th>
                        <th style="width: 5%;" rowspan="2">Action</th>
                    </tr>
                    
                    <tr>
                        <th>1 day</th>
                        <th>7 day</th>
                        <th>1 mth</th>
                        <th>3 mth</th>
                        <th>6 mth</th>
                        <th>1 Yr</th>
                        <th>3 Yr</th>
                        <th>5 Yr</th>
                        <th>10 Yr</th>
                        <th>SinIncep</th>
                    </tr>

                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 createlist-suggested-scheme-container">
            <h1 class="blue-bg-heading"> SUGGESTED SCHEMES FOR INVESTMENT</h1>
            <div class="table-responsive">
            <div class="roundTable">
                <table class="table table-bordered bigthheading">
                    <tbody>
                    <tr>
                        <td style="width: 10%;">Direct&nbsp;/&nbsp;Regular</td>
                        <td style="width: 15%;">Asset&nbsp;Type</td>
                        <td style="width: 15%;">AMC Name</td>
                        <td style="width: 12%;">Category</td>
                        <td style="width: 12%;">Type</td>
                        <td>Scheme Name</td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control" name="option" id="option">
                                <option value="regular"> Regular </option>
                                <option value="direct"> Direct </option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="asset" id="asset" onchange="getCategory()">

                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="fund" id="fund" onchange="getCategory()">

                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="category" id="category">
                                <option value=""> All </option>
                                <option value="15">Capital Protection</option>
                                <option value="72">Debt - Banking and PSU Fund</option>
                            </select>
                        </td>

                        <td>
                            <select class="form-control" name="type" id="type">
                                <option value="growth">Growth</option>
                                <option value="dividend">Dividend</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control js-example-basic-single" name="scheme" id="scheme">

                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <div class="col-sm-12 customlist-suggested-scheme-container" style="display: none">
            <h1 class="blue-bg-heading">MY CUSTOM LIST</h1>
            <div class="table-responsive mt-3">
            
                <table class="table table-bordered" id="mycustomelist">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 10%;">Select</th>
                        <th scope="col" style="width: 45%;">Date</th>
                        <th scope="col" style="width: 45%;">List Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $custm_cnt = 1;
                        $userid = \Illuminate\Support\Facades\Auth::user()->id;
                        $customlists = \App\Models\FundPerformanceCreateList::where('user_id',$userid)->orderBy('id','desc')->get();
                    @endphp
                    @if(isset($customlists) && count($customlists)>0)
                        @foreach($customlists as $cmlist)
                        <tr>
                            <td>
                                <label class="checkLinecontainer">Summary Report
                                    <input @if($custm_cnt==1) checked @endif type="radio" name="custom_list_input" value="{{$cmlist['id']}}">
                                    <span class="checkmark"></span>
                                </label> 
                            </td>
                            <td>{{$cmlist['created_at']->format('d/m/Y')}}</td>
                            <td>{{$cmlist['title']}}</td>
                        </tr>
                            @php
                                $custm_cnt++;
                            @endphp
                        @endforeach
                    @endif
                    </tbody>
                </table>
            
            </div>
        </div>

        <div class="col-sm-12 categorylist-suggested-scheme-container" style="display: none">
            <h1 class="blue-bg-heading">MY CATEGORY WISE LIST</h1>
            <div class="table-responsive mt-3">
                <table class="table table-bordered" id="mycategorylist">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 10%;">Select</th>
                        <th scope="col" style="width: 45%;">Date</th>
                        <th scope="col" style="width: 45%;">List Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $ct_cnt = 1;
                        $userid = \Illuminate\Support\Facades\Auth::user()->id;
                        $catlists = \App\Models\FundPerformanceCreateCategoryList::where('user_id',$userid)->orderBy('id','desc')->get();
                    @endphp
                    @if(isset($catlists) && count($catlists)>0)
                        @foreach($catlists as $ctlist)
                            <tr>
                                <td>
                                    <label class="checkLinecontainer">Summary Report
                                        <input @if($ct_cnt==1) checked @endif type="radio" name="category_list_input" value="{{$ctlist['id']}}">
                                        <span class="checkmark"></span>
                                    </label> 
                                </td>
                                <td>{{$ctlist['created_at']->format('d/m/Y')}}</td>
                                <td>{{$ctlist['title']}}</td>
                            </tr>
                            @php
                                $ct_cnt++;
                            @endphp
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group row">

    </div>
</div>
