<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Models\Trigger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use Session;


class TriggerController extends Controller
{
    
    public function getQuantsName($key_name){
        $quants = [
            ["id"=>"1","name"=>"YTM","is_checked"=>1,"key_name"=>'ytm'],
            ["id"=>"2","name"=>"Mod Duration","is_checked"=>0,"key_name"=>'mod_dur_num'],
            ["id"=>"3","name"=>"Expense Ratio","is_checked"=>1,"key_name"=>'expratio'],
            ["id"=>"4","name"=>"PE Ratio","is_checked"=>1,"key_name"=>'PE'],
            ["id"=>"5","name"=>"PB Ratio","is_checked"=>0,"key_name"=>'PB'],
            ["id"=>"6","name"=>"Alpha","is_checked"=>1,"key_name"=>'alpha'],
            ["id"=>"7","name"=>"Sharpe","is_checked"=>0,"key_name"=>'sharpe'],
            ["id"=>"8","name"=>"Treynor","is_checked"=>1,"key_name"=>'treynor'],
            ["id"=>"9","name"=>"Sortino","is_checked"=>1,"key_name"=>'sortino'],
            ["id"=>"10","name"=>"Standard Deviation","is_checked"=>0,"key_name"=>'sd']
        ];
        
        $quantsName = "";
        
        foreach($quants as $value){
            if($value['key_name'] == $key_name){
                $quantsName = $value['name'];
            }
        }
        return $quantsName;
    }
    
    public function getReturnName($key_name){
        $quants = [
            ["id"=>"1","name"=>"1 Day","is_checked"=>1,"key_name"=>'1dayret'],
            ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
            ["id"=>"3","name"=>"1 Month","is_checked"=>1,"key_name"=>'1monthret'],
            ["id"=>"4","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret'],
            ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
            ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
            ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
            ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
            ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
            ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yearret']
        ];
        
        $quantsName = "";
        
        foreach($quants as $value){
            if($value['key_name'] == $key_name){
                $quantsName = $value['name'];
            }
        }
        return $quantsName;
    }
    
    public function list(Request $request){
        $data = [];
        $data['search'] = $request->search;

        $data['result'] = DB::table("trigger_users")
                            ->select(["trigger_users.*","triggers.name as triggers_name"])
                            ->LeftJoin('triggers', 'trigger_users.trigger_type', '=', 'triggers.type')
                            ->where('user_id',Auth::user()->id)
                            ->where('is_email_hit',0)->orderBy('created_at','DESC')->get();


        foreach ($data['result'] as $key => $value) {
            // echo "<br>".$value->id; 
            $data['result'][$key]->navrs = number_format($value->navrs, 2, '.', '');
            if($value->trigger_type == "nav-trigger"){
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name"])->where("schemecode",$value->scheme)->first();
                // dd($accord_scheme_details);
                $data['result'][$key]->s_name = $accord_scheme_details->s_name;
            }else if($value->trigger_type == "index-trigger"){
                $accord_scheme_details = DB::table("accord_indicesmaster")->select(["INDEX_LNAME"])->where("INDEX_CODE",$value->select_index)->first();

                $data['result'][$key]->s_name = $accord_scheme_details->INDEX_LNAME;
            }else if($value->trigger_type == "aum-trigger"){
                if($value->specific_aum == "Scheme AUM"){
                    $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name"])->where("schemecode",$value->scheme)->first();
                    // dd($accord_scheme_details);
                    $data['result'][$key]->s_name = $accord_scheme_details->s_name;
                }else{
                    $accord_scheme_details = DB::table("accord_amc_mst")->select(["fund"])->where("amc_code",$value->select_amc)->first();
                    $data['result'][$key]->s_name = $accord_scheme_details->fund;
                }
            }else if($value->trigger_type == "category-performance-trigger"){
                $mf_scheme = DB::table("accord_sclass_mst")
                        ->select(['classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.classcode',$value->category_id)->first();
                // dd($mf_scheme);
                $data['result'][$key]->s_name = ($mf_scheme->classname)?$mf_scheme->classname:$mf_scheme->class_name;
                $data['result'][$key]->s_name = $data['result'][$key]->s_name." - ".$this->getReturnName($value->period_id);
            }else if($value->trigger_type == "scheme-performance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $data['result'][$key]->s_name = $mf_scheme->s_name." - ".$this->getReturnName($value->period_id);
            }else if($value->trigger_type == "quants-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $data['result'][$key]->s_name = $mf_scheme->s_name." - ".$this->getQuantsName($value->select_quant);
            }else if($value->trigger_type == "scheme-performance-advance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                // dd($value);
                $select_quant = ($value->select_quant == 1)?"Absolute":"CAGR";
                $s_name = $mf_scheme->s_name." - ".$select_quant." - ".$value->specific_aum;
                $data['result'][$key]->s_name = $s_name;
            }else {
                $data['result'][$key]->s_name = "";
            }

            $data['result'][$key]->navrs = ($value->navrs)?number_format($value->navrs, 2, '.', ''):"";
            
        }

        // dd($data['result']);
        $data['message_type'] = "";
        $data['message_text'] = "";

        $mso_trigger_message = Session::get('mso_trigger_message');

        if($mso_trigger_message){
            $data['message_type'] = $mso_trigger_message['type'];
            $data['message_text'] = $mso_trigger_message['message'];
            Session::put('mso_trigger_message',"");
        }

        // dd($data['result']);

        $data['menu_action'] = "saved-trigger";
        
        return view('frontend.trigger.list',$data);
    }

    public function default(Request $request){
        $data = [];
        $data['search'] = $request->search;

        if($data['search']){
            $data['result'] = DB::table("trigger_users")
                    ->select(["trigger_users.*","triggers.name as triggers_name"])
                    ->LeftJoin('triggers', 'trigger_users.trigger_type', '=', 'triggers.type')
                    ->where('user_id',0)
                    ->where(function ($query) use ($data) {
                        $query->where('trigger_users.trigger_name', "like", "%" . $data['search'] . "%");
                        $query->orWhere('triggers.name', "like", "%" . $data['search'] . "%");
                        $query->orWhere('trigger_users.remarks', "like", "%" . $data['search'] . "%");
                    })
                    ->orderBy('created_at','DESC')->get();
        }else{
            $data['result'] = DB::table("trigger_users")
                    ->select(["trigger_users.*","triggers.name as triggers_name"])
                    ->LeftJoin('triggers', 'trigger_users.trigger_type', '=', 'triggers.type')
                    ->where('user_id',0)
                    ->orderBy('created_at','DESC')->get();
        }

        foreach ($data['result'] as $key => $value) {
            $data['result'][$key]->navrs = number_format($value->navrs, 2, '.', '');
            if($value->trigger_type == "nav-trigger"){
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name"])->where("schemecode",$value->scheme)->first();
                // dd($accord_scheme_details);
                $data['result'][$key]->s_name = $accord_scheme_details->s_name;
            }else if($value->trigger_type == "index-trigger"){
                $accord_scheme_details = DB::table("accord_indicesmaster")->select(["INDEX_LNAME"])->where("INDEX_CODE",$value->select_index)->first();

                $data['result'][$key]->s_name = $accord_scheme_details->INDEX_LNAME;
            }else if($value->trigger_type == "aum-trigger"){
                if($value->specific_aum == "Scheme AUM"){
                    $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name"])->where("schemecode",$value->scheme)->first();

                    $data['result'][$key]->s_name = $accord_scheme_details->s_name;
                }else{
                    $accord_scheme_details = DB::table("accord_amc_mst")->select(["fund"])->where("amc_code",$value->select_amc)->first();
                    $data['result'][$key]->s_name = $accord_scheme_details->fund;
                }
            }else if($value->trigger_type == "category-performance-trigger"){
                $mf_scheme = DB::table("accord_sclass_mst")
                        ->select(['classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.classcode',$value->category_id)->first();
                // dd($mf_scheme);
                $data['result'][$key]->s_name = ($mf_scheme->classname)?$mf_scheme->classname:$mf_scheme->class_name;
            }else if($value->trigger_type == "scheme-performance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $data['result'][$key]->s_name = $mf_scheme->s_name;
            }else if($value->trigger_type == "quants-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $data['result'][$key]->s_name = $mf_scheme->s_name;
            }else if($value->trigger_type == "scheme-performance-advance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                // dd($mf_scheme);
                $data['result'][$key]->s_name = $mf_scheme->s_name;
            }else {
                $data['result'][$key]->s_name = "";
            }
            
        }

        // dd($data['result']);

        $data['menu_action'] = "default-trigger";
        
        return view('frontend.trigger.default',$data);
    }

    public function completed(Request $request){
        $data = [];
        $data['search'] = $request->search;

        if($data['search']){
            $data['result'] = DB::table("trigger_users")
                    ->select(["trigger_users.*","triggers.name as triggers_name"])
                    ->LeftJoin('triggers', 'trigger_users.trigger_type', '=', 'triggers.type')
                    ->where('user_id',Auth::user()->id)
                    ->where(function ($query) use ($data) {
                        $query->where('trigger_name', "like", "%" . $data['search'] . "%");
                        $query->orWhere('trigger_type', "like", "%" . $data['search'] . "%");
                        $query->orWhere('remarks', "like", "%" . $data['search'] . "%");
                    })
                    ->where('is_email_hit',1)->orderBy('created_at','DESC')->get();
        }else{
            $data['result'] = DB::table("trigger_users")
                    ->select(["trigger_users.*","triggers.name as triggers_name"])
                    ->LeftJoin('triggers', 'trigger_users.trigger_type', '=', 'triggers.type')
                    ->where('user_id',Auth::user()->id)
                    ->where('is_email_hit',1)->orderBy('created_at','DESC')->get();
        }

        foreach ($data['result'] as $key => $value) {
            // echo "<br>";
            // echo $value->id;
            $data['result'][$key]->navrs = number_format($value->navrs, 2, '.', '');
            if($value->trigger_type == "nav-trigger"){
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name"])->where("schemecode",$value->scheme)->first();
                // dd($accord_scheme_details);
                $data['result'][$key]->s_name = $accord_scheme_details->s_name;
            }else if($value->trigger_type == "index-trigger"){
                $accord_scheme_details = DB::table("accord_indicesmaster")->select(["INDEX_LNAME"])->where("INDEX_CODE",$value->select_index)->first();

                $data['result'][$key]->s_name = $accord_scheme_details->INDEX_LNAME;
            }else if($value->trigger_type == "aum-trigger"){
                if($value->specific_aum == "Scheme AUM"){
                    $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name"])->where("schemecode",$value->scheme)->first();

                    $data['result'][$key]->s_name = $accord_scheme_details->s_name;
                }else{
                    $accord_scheme_details = DB::table("accord_amc_mst")->select(["fund"])->where("amc_code",$value->select_amc)->first();
                    $data['result'][$key]->s_name = $accord_scheme_details->fund;
                }
            }else if($value->trigger_type == "category-performance-trigger"){
                $mf_scheme = DB::table("accord_sclass_mst")
                        ->select(['classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.classcode',$value->category_id)->first();
                // dd($mf_scheme);
                
                $data['result'][$key]->s_name = ($mf_scheme->classname)?$mf_scheme->classname:$mf_scheme->class_name;
                $data['result'][$key]->s_name = $data['result'][$key]->s_name." - ".$this->getReturnName($value->period_id);
            }else if($value->trigger_type == "scheme-performance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $data['result'][$key]->s_name = $mf_scheme->s_name." - ".$this->getReturnName($value->period_id);
            }else if($value->trigger_type == "quants-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $data['result'][$key]->s_name = $mf_scheme->s_name." - ".$this->getQuantsName($value->select_quant);
            }else if($value->trigger_type == "scheme-performance-advance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                // dd($value);
                $select_quant = ($value->select_quant == 1)?"Absolute":"CAGR";
                $s_name = $mf_scheme->s_name." - ".$select_quant." - ".$value->specific_aum;
                $data['result'][$key]->s_name = $s_name;
            }else {
                $data['result'][$key]->s_name = "";
            }
            
        }

        // dd($data['result']);

        $data['menu_action'] = "completed-trigger";
        
        return view('frontend.trigger.completed',$data);
    }

    public function subscribe(Request $request){
        $id = $request->id;

        $trigger_user = DB::table("trigger_users")->where("user_id",0)->where("id",$id)->first();

        if($trigger_user){
            $insertData = [];
            $insertData['trigger_name'] = $trigger_user->trigger_name;
            $insertData['trigger_type'] = $trigger_user->trigger_type;
            $insertData['scheme'] = $trigger_user->scheme;
            $insertData['current_nav'] = $trigger_user->current_nav;
            $insertData['trigger_condition'] = $trigger_user->trigger_condition;
            $insertData['trigger_value'] = $trigger_user->trigger_value;
            $insertData['amount'] = $trigger_user->amount;
            $insertData['base_nav'] = $trigger_user->base_nav;
            $insertData['increase_decrease'] = $trigger_user->increase_decrease;
            $insertData['appreciation'] = $trigger_user->appreciation;
            $insertData['navrs'] = $trigger_user->navrs;
            $insertData['remarks'] = $trigger_user->remarks;
            $insertData['status'] = $trigger_user->status;
            $insertData['user_id'] = Auth::user()->id;
            $insertData['mso_id'] = $trigger_user->id;

            DB::table("trigger_users")->insert($insertData);
        }
        
        return redirect()->route('frontend.trigger_list');
    }

    public function getData(Request $request){
        $type = $request->type;
        if($type == 1){
            $data['scheme_list'] = DB::table("accord_scheme_details")
                    ->select(["accord_scheme_details.schemecode","accord_scheme_details.s_name","accord_currentnav.navrs","accord_currentnav.navdate"])
                    ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                    ->where('accord_scheme_details.status','=','Active')->orderBy("accord_scheme_details.s_name","ASC")->get();

        }else if($type == 2){
            $data['index_list'] = DB::table("accord_indicesmaster")
                    ->select(["accord_indicesmaster.INDEX_CODE as index_code","accord_indicesmaster.INDEX_NAME as index_n","accord_indicesmaster.EXCHANGE as EXCHANGE","accord_indicesmaster.INDEX_LNAME as index_name"])
                    ->where('accord_indicesmaster.flag','=','A')->orderBy("accord_indicesmaster.INDEX_NAME","ASC")->get();

            foreach ($data['index_list'] as $key => $value) {
                $data['index_list'][$key]->VALUE = "";
                if($value->EXCHANGE == "BSE"){
                    $indices_hst = DB::table("accord_indices_hst_bsc")->where("SCRIPCODE",$value->index_code)->orderBy("DATE","DESC")->first();
                    if($indices_hst){
                        $data['index_list'][$key]->VALUE = $indices_hst->CLOSE;
                    }
                    
                }else if($value->EXCHANGE == "NSE"){
                    $indices_hst = DB::table("accord_indices_hst_nsc")->where("SYMBOL",$value->index_n)->orderBy("DATE","DESC")->first();
                    if($indices_hst){
                        $data['index_list'][$key]->VALUE = $indices_hst->CLOSE;
                    }
                }
            }
        }else if($type == 3){
            $data['fund_house_list'] = DB::table("accord_amc_mst")->select(['amc_code','fund'])->orderBy('fund', 'asc')->get();

            foreach ($data['fund_house_list'] as $key => $value) {
                $accord_amc_paum = DB::table("accord_amc_paum")->where("amc_code",$value->amc_code)->orderBy("aumdate","DESC")->first();
                if($accord_amc_paum){
                    $data['fund_house_list'][$key]->aumdate = date('d/m/Y', strtotime($accord_amc_paum->aumdate));
                    $data['fund_house_list'][$key]->totalaum = $accord_amc_paum->totalaum;
                }else{
                    $data['fund_house_list'][$key]->aumdate = "";
                    $data['fund_house_list'][$key]->totalaum = "";
                }
            }
        }else if($type == 4){
            $data['category_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->whereIn('accord_sclass_mst.asset_type',['Commodity','Debt','Equity','Hybrid','Other'])
                        ->orderBy('classname', 'asc')->get();
        }else if($type == 5){
            $data['scheme_list'] = DB::table("mf_scanner")->select(["s_name","schemecode","Incept_date"])->where("status",1)->get();
        }else if($type == 6){

        }else if($type == 10){
            $plan_id = $request->plan_id;
            $category_id = $request->category_id;
            $period_id = $request->period_id;

            if($plan_id && $category_id && $period_id){
                $mf_scanner_avg = DB::table("mf_scanner_avg")->where("classcode",$category_id)->where("plan_code",$plan_id)->first();

                $mf_scanner_avg = (array) $mf_scanner_avg;

                $data['current'] = $mf_scanner_avg[$period_id];
            }else{
                $data['current'] = "";
            }                
        }else if($type == 11){
            $scheme_id = $request->scheme_id;
            $period_id = $request->period_id;

            if($scheme_id && $period_id){
                $mf_scanner_avg = DB::table("mf_scanner")->where("schemecode",$scheme_id)->first();

                $mf_scanner_avg = (array) $mf_scanner_avg;

                $data['current'] = $mf_scanner_avg[$period_id];
            }else{
                $data['current'] = "";
            }
        }else if($type == 12){
            $scheme_id = $request->scheme_id;
            $accord_scheme_details = DB::table("accord_scheme_details")->select(['primary_fd_code'])->where('schemecode','=',$scheme_id)->first();
            $accord_scheme_aum = DB::table("accord_scheme_aum")
                    ->where('schemecode','=',$accord_scheme_details->primary_fd_code)->orderBy("date","DESC")->first();

            if($accord_scheme_aum){
                $data['current'] = $accord_scheme_aum->total;
            }else{
                $data['current'] = "";
            }
        }else{
            $data = [];
        }

        return response()->json($data);
    }

    public function add(){
        $data = [];
        $data['scheme_list'] = [];
        $data['index_list'] = [];
        $data['fund_house_list'] = [];
        $data['category_list'] = [];

        $data['plan_list'] = DB::table("accord_plan_mst")->select(['accord_plan_mst.plan_code','accord_plan_mst.plan','mf_scanner_plan.name as planname'])->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')->where('accord_plan_mst.status',1)->get();

        $data['return_list'] = [
            ["id"=>"1","name"=>"1 Day","is_checked"=>1,"key_name"=>'1dayret'],
            ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
            ["id"=>"3","name"=>"1 Month","is_checked"=>1,"key_name"=>'1monthret'],
            ["id"=>"4","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret'],
            ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
            ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
            ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
            ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
            ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
            ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yearret']
        ];

        $data['quant_list'] = [
            ["id"=>"1","name"=>"YTM","is_checked"=>1,"key_name"=>'ytm'],
            ["id"=>"2","name"=>"Mod Duration","is_checked"=>0,"key_name"=>'mod_dur_num'],
            ["id"=>"3","name"=>"Expense Ratio","is_checked"=>1,"key_name"=>'expratio'],
            ["id"=>"4","name"=>"PE Ratio","is_checked"=>1,"key_name"=>'PE'],
            ["id"=>"5","name"=>"PB Ratio","is_checked"=>0,"key_name"=>'PB'],
            ["id"=>"6","name"=>"Alpha","is_checked"=>1,"key_name"=>'alpha'],
            ["id"=>"7","name"=>"Sharpe","is_checked"=>0,"key_name"=>'sharpe'],
            ["id"=>"8","name"=>"Treynor","is_checked"=>1,"key_name"=>'treynor'],
            ["id"=>"9","name"=>"Sortino","is_checked"=>1,"key_name"=>'sortino'],
            ["id"=>"10","name"=>"Standard Deviation","is_checked"=>0,"key_name"=>'sd']
        ];


        $data['trigger_list'] = Trigger::where("status",1)->orderBy("position","ASC")->get();
        
        $data['count_flag'] = 1;
        
        $total_trigger_count = DB::table("trigger_users")->where('user_id',Auth::user()->id)->where('is_email_hit',0)->count();
        $trigger_settings = DB::table("trigger_settings")->where('package_id',Auth::user()->package_id)->first();
        if($total_trigger_count >= $trigger_settings->number_of_trigger){
            $data['count_flag'] = 0;
        }

        $data['current_date'] = date("d-m-Y");

        $data['menu_action'] = "new-trigger";
        return view('frontend.trigger.add',$data);
    }

    public function save(Request $request){
        $input = $request->all();
        // dd($input);
        $insertData = [];

        $insertData['trigger_name'] = $request->trigger_name;
        $insertData['trigger_type'] = $request->trigger_type;
        $insertData['trigger_value'] = $request->trigger_value;
        $insertData['remarks'] = ($request->remarks)?$request->remarks:"";

        if($insertData['trigger_type'] == "nav-trigger"){
            $insertData['scheme'] = $request->scheme;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "index-trigger"){
            $insertData['select_index'] = $request->select_index;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "aum-trigger"){
            $insertData['specific_aum'] = $request->specific_aum;
            if($insertData['specific_aum'] == "AMC AUM"){
                $insertData['select_amc'] = $request->select_amc;
                $insertData['current_nav'] = $request->current_nav;
            }else{
                $insertData['scheme'] = $request->scheme;
                $insertData['current_nav'] = $request->current_nav;
            }                
        }else if($insertData['trigger_type'] == "category-performance-trigger"){
            $insertData['plan_id'] = $request->plan_id;
            $insertData['category_id'] = $request->category_id;
            $insertData['period_id'] = $request->period_id;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "scheme-performance-trigger"){
            $insertData['mf_scheme'] = $request->mf_scheme;
            $insertData['period_id'] = $request->period_id;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "quants-trigger"){
            $insertData['mf_scheme'] = $request->mf_scheme;
            $insertData['select_quant'] = $request->select_quant;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "scheme-performance-advance-trigger"){
            $insertData['mf_scheme'] = $request->mf_scheme;
            $insertData['select_quant'] = $request->return_type;
            $insertData['specific_aum'] = $request->investment_date;
        }

        if($insertData['trigger_value'] == 2){
            $insertData['amount'] = $request->amount;
            $insertData['navrs'] = $request->amount;
            $insertData['trigger_condition'] = $request->trigger_condition;
            $insertData['user_id'] = Auth::user()->id;
            // dd($insertData);
            DB::table("trigger_users")->insert($insertData);

        }else if($insertData['trigger_value'] == 1){
            $insertData['base_nav'] = (float) ($request->base_nav);
            $insertData['increase_decrease'] = $request->increase_decrease;
            $insertData['trigger_condition'] = $request->increase_decrease;
            $insertData['appreciation'] = (float) ($request->appreciation);
            $insertData['user_id'] = Auth::user()->id;
            if($insertData['increase_decrease'] == 1){
                $insertData['navrs'] = $insertData['base_nav'] + ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }else{
                $insertData['navrs'] = $insertData['base_nav'] - ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }
            DB::table("trigger_users")->insert($insertData);
        }


        Session::put('mso_trigger_message',["type"=>"success","message"=>"Your Trigger is successfully added."]);

        return redirect()->route('frontend.trigger_list');
    }

    public function edit(Request $request){
        $data = [];
        $data['id'] = $request->id;

        $data['detail'] = DB::table("trigger_users")->where("id",$data['id'])->first();

        $data['current_date'] = date('d-m-Y');
        
        $data['scheme_list'] = [];
        $data['index_list'] = [];
        $data['fund_house_list'] = [];
        $data['category_list'] = [];

        $data['plan_list'] = DB::table("accord_plan_mst")->select(['accord_plan_mst.plan_code','accord_plan_mst.plan','mf_scanner_plan.name as planname'])->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')->where('accord_plan_mst.status',1)->get();

        $data['return_list'] = [
            ["id"=>"1","name"=>"1 Day","is_checked"=>1,"key_name"=>'1dayret'],
            ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
            ["id"=>"3","name"=>"1 Month","is_checked"=>1,"key_name"=>'1monthret'],
            ["id"=>"4","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret'],
            ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
            ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
            ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
            ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
            ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
            ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yearret']
        ];

        $data['quant_list'] = [
            ["id"=>"1","name"=>"YTM","is_checked"=>1,"key_name"=>'ytm'],
            ["id"=>"2","name"=>"Mod Duration","is_checked"=>0,"key_name"=>'mod_dur_num'],
            ["id"=>"3","name"=>"Expense Ratio","is_checked"=>1,"key_name"=>'expratio'],
            ["id"=>"4","name"=>"PE Ratio","is_checked"=>1,"key_name"=>'PE'],
            ["id"=>"5","name"=>"PB Ratio","is_checked"=>0,"key_name"=>'PB'],
            ["id"=>"6","name"=>"Alpha","is_checked"=>1,"key_name"=>'alpha'],
            ["id"=>"7","name"=>"Sharpe","is_checked"=>0,"key_name"=>'sharpe'],
            ["id"=>"8","name"=>"Treynor","is_checked"=>1,"key_name"=>'treynor'],
            ["id"=>"9","name"=>"Sortino","is_checked"=>1,"key_name"=>'sortino'],
            ["id"=>"10","name"=>"Standard Deviation","is_checked"=>0,"key_name"=>'sd']
        ];
        

        $data['trigger_list'] = Trigger::where("status",1)->orderBy("position","ASC")->get();

        $data['menu_action'] = "edit-trigger";
        return view('frontend.trigger.edit',$data);
    }

    public function update(Request $request){

        $id = $request->id;
        
        $insertData = [];

        $insertData['trigger_name'] = $request->trigger_name;
        $insertData['trigger_type'] = $request->trigger_type;
        $insertData['trigger_value'] = $request->trigger_value;
        $insertData['remarks'] = ($request->remarks)?$request->remarks:"";

        if($insertData['trigger_type'] == "nav-trigger"){
            $insertData['scheme'] = $request->scheme;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "index-trigger"){
            $insertData['select_index'] = $request->select_index;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "aum-trigger"){
            $insertData['specific_aum'] = $request->specific_aum;
            if($insertData['specific_aum'] == "AMC AUM"){
                $insertData['select_amc'] = $request->select_amc;
                $insertData['current_nav'] = $request->current_nav;
            }else{
                $insertData['scheme'] = $request->scheme;
                $insertData['current_nav'] = $request->current_nav;
            }                
        }else if($insertData['trigger_type'] == "category-performance-trigger"){
            $insertData['plan_id'] = $request->plan_id;
            $insertData['category_id'] = $request->category_id;
            $insertData['period_id'] = $request->period_id;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "scheme-performance-trigger"){
            $insertData['mf_scheme'] = $request->mf_scheme;
            $insertData['period_id'] = $request->period_id;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "quants-trigger"){
            $insertData['mf_scheme'] = $request->mf_scheme;
            $insertData['select_quant'] = $request->select_quant;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "scheme-performance-advance-trigger"){
            $insertData['mf_scheme'] = $request->mf_scheme;
            $insertData['select_quant'] = $request->return_type;
            $insertData['specific_aum'] = $request->investment_date;
        }

        if($insertData['trigger_value'] == 2){
            $insertData['amount'] = $request->amount;
            $insertData['navrs'] = $request->amount;
            $insertData['trigger_condition'] = $request->trigger_condition;

            // dd($id);
            
            DB::table("trigger_users")->where("id",$id)->update($insertData);

        }else if($insertData['trigger_value'] == 1){
            $insertData['base_nav'] = (float) ($request->base_nav);
            $insertData['increase_decrease'] = $request->increase_decrease;
            $insertData['trigger_condition'] = $request->increase_decrease;
            $insertData['appreciation'] = (float) ($request->appreciation);
            if($insertData['increase_decrease'] == 1){
                $insertData['navrs'] = $insertData['base_nav'] + ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }else{
                $insertData['navrs'] = $insertData['base_nav'] - ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }

            DB::table("trigger_users")->where("id",$id)->update($insertData);
        }
        
        
        Session::put('mso_trigger_message',["type"=>"success","message"=>"Your Trigger is successfully edited."]);

        return redirect()->route('frontend.trigger_list');
    }

    public function delete(Request $request){
        $id = $request->id;

        DB::table("trigger_users")->where("id",$id)->delete();

        return redirect()->route('frontend.trigger_list');
    }

    public function delete_all(Request $request){
        $trigger = $request->trigger;

        DB::table("trigger_users")->whereIn("id",$trigger)->delete();

        return redirect()->route('frontend.trigger_list');
    }

    public function setting(){
        $data = [];
        $details = DB::table("trigger_user_settings")->where('user_id',Auth::user()->id)->first();

        if($details){
            $data['notification'] = $details->notification;
            $data['subscribe'] = $details->subscribe;
        }else{
            $data['notification'] = "E-mail";
            $data['subscribe'] = "No";
        }

        $data['total_trigger_used'] = DB::table("trigger_users")
                            ->select(["trigger_users.id"])
                            ->where('user_id',Auth::user()->id)
                            ->where('is_email_hit',0)->count();

        $trigger_settings = DB::table("trigger_settings")->where('package_id',Auth::user()->package_id)->first();

        $data['total_trigger_count'] = $trigger_settings->number_of_trigger;

        $data['menu_action'] = "setting-trigger";

        return view('frontend.trigger.setting',$data);
    }

    public function setting_update(Request $request){
        $input = $request->all();

        // dd($input);
        $insertData = [];
        $insertData['notification'] = $request->notification;
        $insertData['subscribe'] = $request->subscribe;



        $details = DB::table("trigger_user_settings")->where('user_id',Auth::user()->id)->first();

        if($details){
            DB::table("trigger_user_settings")->where('user_id',Auth::user()->id)->update($insertData);
        }else{
            $insertData['user_id'] = Auth::user()->id;
            DB::table("trigger_user_settings")->insert($insertData);
        }

        return redirect()->route('frontend.trigger_setting');
    }
    
}