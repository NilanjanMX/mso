<?php

namespace App\Http\Controllers\Frontend\MFResearch;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Membership;
use App\Models\Displayinfo;
use App\Models\Mfresearch_note;
use App\Models\History;
use Response;

class MFScannerController extends Controller
{
    
    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }
    
    public function index(){
        $data = [];

        $data['asset_type_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('accord_sclass_mst.asset_type','Commodity')
                        ->orderBy('classname', 'asc')->get();
        $data['debt_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Debt')
                        ->orderBy('classname', 'asc')->get();
        $data['equity_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Equity')
                        ->orderBy('classname', 'asc')->get();
        $data['hybrid_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Hybrid')
                        ->orderBy('classname', 'asc')->get();
        $data['other_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Other')->orWhere('asset_type','Commodity')
                        ->orderBy('classname', 'asc')->get();
        // dd($data);
        $data['fund_house_list'] = DB::table("accord_amc_mst")->select(['amc_code','fund'])->orderBy('fund', 'asc')->get();
        $data['fund_manager_list'] = DB::table("accord_fundmanager_mst")->select(['id','fundmanager'])->orderBy('fundmanager', 'asc')->get();
        $data['primary_benchmark_list'] = DB::table("accord_index_mst")->select(['IndexCode','IndexName'])->orderBy('IndexName', 'asc')->get();
        $data['fund_type_list'] = DB::table("accord_type_mst")->select(['type_code','type'])->get();
        $data['option_list'] = DB::table("accord_option_mst")->select(['opt_code','option'])->get();
        $data['plan_list'] = DB::table("accord_plan_mst")->select(['accord_plan_mst.plan_code','accord_plan_mst.plan','mf_scanner_plan.name as planname'])->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')->where('accord_plan_mst.status',1)->get();

        $is_default = false;
        $user_id = 0;
        if(Auth::user()){
            $mf_researches = DB::table("mf_researches")->where("url","mf-screener")->first();
            $package_id = Auth::user()->package_id;
            $user_id = Auth::user()->id;
            $permission = DB::table("mf_research_permissions")->where("mf_research_id",$mf_researches->id)->where("package_id",$package_id)->first();

            $is_csv = 0;
            if($package_id == 17 || $package_id == 15){
                $is_csv = 1;
            }
            if($permission){
                $data['permission'] = [
                    "is_view"=>$permission->is_view,
                    "is_download"=>$permission->is_download,
                    "is_save"=>$permission->is_save,
                    "is_csv"=>$is_csv
                ];
            }else{
                $data['permission'] = [
                    "is_view"=>0,
                    "is_download"=>0,
                    "is_save"=>0,
                    "is_csv"=>$is_csv
                ];
            }
            $data['saved_filter'] = DB::table("mf_scanner_saved_filter")->where("user_id",Auth::user()->id)->orderBy("is_default","DESC")->orderBy("created","DESC")->get();
            if(count($data['saved_filter'])){
                foreach ($data['saved_filter'] as $key => $value) {
                    $data['saved_filter'][$key]->data_array = unserialize($value->data);
                }
                $global_selected_value = DB::table("mf_scanner_saved_filter")->where("user_id",Auth::user()->id)->where("is_default",1)->first();

                $data['global_selected_value'] = '';
                
                if (isset($global_selected_value) && isset($global_selected_value->data)) {
                    $data['global_selected_value'] = unserialize($global_selected_value->data);
                }

            }else{
                $is_default = true;
            }
            
        }else{
            $data['permission'] = [
                "is_view"=>1,
                "is_download"=>0,
                "is_save"=>0,
                "is_csv"=>0
            ];
            $data['saved_filter'] = [];
            $is_default = true;
        }

        if($is_default){
            $data['global_selected_value'] = [
                "ael"=>"31",
                "adl"=>"",
                "ahl"=>"",
                "aol"=>"",
                "fhl"=>"",
                "ftl"=>"1",
                "fml"=>"",
                "pbl"=>"",
                "ol"=>"1",
                "pl"=>"6",
                "amurange"=>"1,2,3,4,5,6,7",
                "response"=>[],
                "rating"=>"5,4,3,2,1,0"
            ];

            foreach ($data['fund_house_list'] as $key => $value) {
                if($data['global_selected_value']['fhl']){
                    $data['global_selected_value']['fhl'] = $data['global_selected_value']['fhl'].",".$value->amc_code;
                }else{
                    $data['global_selected_value']['fhl'] = $value->amc_code;
                }
            }

            foreach ($data['fund_manager_list'] as $key => $value) {
                if($data['global_selected_value']['fml']){
                    $data['global_selected_value']['fml'] = $data['global_selected_value']['fml'].",".$value->id;
                }else{
                    $data['global_selected_value']['fml'] = $value->id;
                }
            }

            foreach ($data['primary_benchmark_list'] as $key => $value) {
                if($data['global_selected_value']['pbl']){
                    $data['global_selected_value']['pbl'] = $data['global_selected_value']['pbl'].",".$value->IndexCode;
                }else{
                    $data['global_selected_value']['pbl'] = $value->IndexCode;
                }
            }

            $data['global_selected_value']['response'] = [
                ['id'=>'0','name'=>'Category','is_checked'=>1,"key_name"=>'classname',"order"=>0,"type"=>0,"table_checkbox"=>1,"is_freeze"=>0],
                ['id'=>'39','name'=>'Rating','is_checked'=>1,"key_name"=>'rating',"order"=>7,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
                ['id'=>'1','name'=>'AUM (Rs Cr)','is_checked'=>1,"key_name"=>'total',"order"=>1,"type"=>1,"table_checkbox"=>1,"is_freeze"=>0],
                ['id'=>'2','name'=>'NAV','is_checked'=>1,"key_name"=>'navrs',"order"=>1,"type"=>1,"table_checkbox"=>1,"is_freeze"=>0],
                ['id'=>'3','name'=>'Expense Ratio (%)','is_checked'=>1,"key_name"=>'expratio',"order"=>3,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"10","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret',"order"=>10,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
                ["id"=>"11","name"=>"6 Month","is_checked"=>1,"key_name"=>'6monthret',"order"=>11,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
                ["id"=>"12","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret',"order"=>12,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
                ["id"=>"14","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret',"order"=>14,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
                ["id"=>"15","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret',"order"=>15,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
                ["id"=>"19","name"=>"No. of Stocks","is_checked"=>1,"key_name"=>'ASECT_CODE',"order"=>19,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"21","name"=>"PE Ratio","is_checked"=>1,"key_name"=>'PE',"order"=>21,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"24","name"=>"Large Cap (%)","is_checked"=>1,"key_name"=>'large_cap',"order"=>24,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"25","name"=>"Mid Cap (%)","is_checked"=>1,"key_name"=>'mid_cap',"order"=>25,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"26","name"=>"Small Cap (%)","is_checked"=>1,"key_name"=>'small_cap',"order"=>26,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"30","name"=>"Modified Duration","is_checked"=>1,"key_name"=>'mod_dur_num',"order"=>30,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"31","name"=>"YTM (%)","is_checked"=>1,"key_name"=>'ytm',"order"=>31,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"32","name"=>"Alpha","is_checked"=>1,"key_name"=>'alpha',"order"=>32,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"33","name"=>"Sharpe","is_checked"=>1,"key_name"=>'sharpe',"order"=>33,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
                ["id"=>"37","name"=>"Standard Deviation","is_checked"=>1,"key_name"=>'sd',"order"=>37,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ];
        }



        $data['global_compare_list'] = [
            ['id'=>'0','name'=>'Category','is_checked'=>1,"key_name"=>'classname',"order"=>0,"type"=>0,"table_checkbox"=>1,"is_freeze"=>0],
            ['id'=>'1','name'=>'AUM (Rs Cr)','is_checked'=>1,"key_name"=>'total',"order"=>1,"type"=>1,"table_checkbox"=>1,"is_freeze"=>0],
            ['id'=>'2','name'=>'NAV','is_checked'=>1,"key_name"=>'navrs',"order"=>1,"type"=>1,"table_checkbox"=>1,"is_freeze"=>0],
            ['id'=>'3','name'=>'Expense Ratio (%)','is_checked'=>1,"key_name"=>'expratio',"order"=>3,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'4','name'=>'Exit Load (%)','is_checked'=>0,"key_name"=>'EXITLOAD',"order"=>4,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'5','name'=>'Inception Date','is_checked'=>0,"key_name"=>'Incept_date',"order"=>5,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'6','name'=>'Benchmark','is_checked'=>0,"key_name"=>'IndexName',"order"=>6,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'39','name'=>'Rating','is_checked'=>1,"key_name"=>'rating',"order"=>7,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"7","name"=>"1 Day","is_checked"=>0,"key_name"=>'1dayret',"order"=>7,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"8","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret',"order"=>8,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"9","name"=>"1 Month--2","is_checked"=>0,"key_name"=>'1monthret',"order"=>9,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"10","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret',"order"=>10,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
            ["id"=>"11","name"=>"6 Month","is_checked"=>1,"key_name"=>'6monthret',"order"=>11,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
            ["id"=>"12","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret',"order"=>12,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
            ["id"=>"13","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret',"order"=>13,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"14","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret',"order"=>14,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
            ["id"=>"15","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret',"order"=>15,"type"=>2,"table_checkbox"=>1,"is_freeze"=>0],
            ["id"=>"16","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret',"order"=>16,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"17","name"=>"Since Inception","is_checked"=>0,"key_name"=>'incret',"order"=>17,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"18","name"=>"Turnover Ratio (%)","is_checked"=>0,"key_name"=>'turnover_ratio',"order"=>18,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"19","name"=>"No. of Stocks","is_checked"=>1,"key_name"=>'ASECT_CODE',"order"=>19,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"20","name"=>"Avg M-Cap (Rs Cr)","is_checked"=>0,"key_name"=>'MCAP',"order"=>20,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"21","name"=>"PE Ratio","is_checked"=>1,"key_name"=>'PE',"order"=>21,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"22","name"=>"PB Ratio","is_checked"=>0,"key_name"=>'PB',"order"=>22,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"23","name"=>"Dividend Yield","is_checked"=>0,"key_name"=>'Div_Yield',"order"=>23,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"24","name"=>"Large Cap (%)","is_checked"=>1,"key_name"=>'large_cap',"order"=>24,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"25","name"=>"Mid Cap (%)","is_checked"=>1,"key_name"=>'mid_cap',"order"=>25,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"26","name"=>"Small Cap (%)","is_checked"=>1,"key_name"=>'small_cap',"order"=>26,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"27","name"=>"Highest Sector Allocation","is_checked"=>0,"key_name"=>'highest_sector_all',"order"=>27,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"28","name"=>"Highest Sector Allocation %","is_checked"=>0,"key_name"=>'highest_sector_all_per',"order"=>28,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"29","name"=>"Average Maturity","is_checked"=>0,"key_name"=>'avg_mat_num',"order"=>29,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"30","name"=>"Modified Duration","is_checked"=>1,"key_name"=>'mod_dur_num',"order"=>30,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"31","name"=>"YTM (%)","is_checked"=>1,"key_name"=>'ytm',"order"=>31,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"32","name"=>"Alpha","is_checked"=>1,"key_name"=>'alpha',"order"=>32,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"33","name"=>"Sharpe","is_checked"=>1,"key_name"=>'sharpe',"order"=>33,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"34","name"=>"Treynor","is_checked"=>0,"key_name"=>'treynor',"order"=>34,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"35","name"=>"Sortino","is_checked"=>0,"key_name"=>'sortino',"order"=>35,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"36","name"=>"Beta","is_checked"=>0,"key_name"=>'beta',"order"=>1,"type"=>36,"table_checkbox"=>1,"is_freeze"=>0],
            ["id"=>"37","name"=>"Standard Deviation","is_checked"=>1,"key_name"=>'sd',"order"=>37,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"38","name"=>"Tracking Error","is_checked"=>0,"key_name"=>'trackingError',"order"=>38,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
        ];

        $data['activemenu'] = 'mf-scanner';
        // $data['basic_detail_list_json'] = json_encode($data['basic_detail_list'])
        // dd(session()->get('ms_mf_scenner_data'));
        
        $ip_address = $this->getIp();
    
        History::create([
            'list_count' => 1,
            'user_id' => $user_id,
            'page_type' => "MF Research",
            'page_id' => 1,
            'ip' => $ip_address
        ]);

         //dd($data);
        
        return view('frontend.mf_scanner.index',$data);
    }

    public function saveFilter(Request $request){
        $input = $request->all();

        $id = $request->id;

        $global_compare_list = json_decode($input['all_colum_list']);
            
        $all_colum_list = explode(',',$input['all_colum_list']);
        $order_by = "s_name";
        $ordering = $request->order_type;
        if($request->order_by == 1){
            $order_by = "s_name";
        }else{
            $order_index = $request->order_by - 2;
            $order_by = $global_compare_list[$order_index]->key_name;
        }

        $mf_scenner_data = [];
        $mf_scenner_data['schemecode_id'] = $request->schemecode_id;
        $mf_scenner_data['order_by'] = $order_by;
        $mf_scenner_data['ordering'] = $ordering;
        $mf_scenner_data['ael'] = $request->ael;
        $mf_scenner_data['adl'] = $request->adl;
        $mf_scenner_data['ahl'] = $request->ahl;
        $mf_scenner_data['aol'] = $request->aol;
        $mf_scenner_data['fhl'] = $request->fhl;
        $mf_scenner_data['fml'] = $request->fml;
        $mf_scenner_data['pbl'] = $request->pbl;
        $mf_scenner_data['ftl'] = $request->ftl;
        $mf_scenner_data['ol'] = $request->ol;
        $mf_scenner_data['pl'] = $request->pl;
        $mf_scenner_data['amurange'] = $request->amurange;
        $mf_scenner_data['rating'] = $request->rating;
        
        $mf_scenner_data['response'] = [];
        foreach ($global_compare_list as $key => $value) {
            array_push($mf_scenner_data['response'], (array) $value);
        }

        if($id){
            $insertData = [];
            $insertData['data'] = serialize($mf_scenner_data);
            DB::table("mf_scanner_saved_filter")->where("id",$id)->update($insertData);
        }else{
            $insertData = [];
            $insertData['user_id'] = Auth::user()->id;
            $insertData['name'] = $request->filter_title;
            $insertData['data'] = serialize($mf_scenner_data);
            DB::table("mf_scanner_saved_filter")->insert($insertData);
        }

        

        $result = DB::table("mf_scanner_saved_filter")->where("user_id",Auth::user()->id)->orderBy("is_default","DESC")->orderBy("last_updated","DESC")->get();
        foreach ($result as $key => $value) {
            $result[$key]->data_array = unserialize($value->data);
        }
        return response()->json($result);
    }

    public function deleteFilter(Request $request){
        $id = $request->id;
        $type = $request->type;
        
        if($type == 1){
            DB::table("mf_scanner_saved_filter")->where("id",$id)->delete();
        }else{
            DB::table("mf_scanner_saved_filter")->where("user_id",Auth::user()->id)->update(["is_default"=>0]);
            DB::table("mf_scanner_saved_filter")->where("id",$id)->update(["is_default"=>1]);
        }

        $result = DB::table("mf_scanner_saved_filter")->where("user_id",Auth::user()->id)->orderBy("is_default","DESC")->orderBy("last_updated","DESC")->get();

        foreach ($result as $key => $value) {
            $result[$key]->data_array = unserialize($value->data);
        }

        return response()->json($result);
    }
    
    public function update_screener($id){

        $data = DB::table("mf_scanner_saved")->where("id","=",$id)->first();

        $input = unserialize($data->data);
        $input['name'] = $data->name;
        $input['mf_scanner_saved_id'] = $data->id;

        // dd($input);
        
        $data = [];
        
        if(Auth::user()){
            $mf_researches = DB::table("mf_researches")->where("url","mf-screener")->first();
            $package_id = Auth::user()->package_id;
            $permission = DB::table("mf_research_permissions")->where("mf_research_id",$mf_researches->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                    "is_view"=>$permission->is_view,
                    "is_download"=>$permission->is_download,
                    "is_save"=>$permission->is_save
                ];
            }else{
                $data['permission'] = [
                    "is_view"=>0,
                    "is_download"=>0,
                    "is_save"=>0
                ];
            }
        }else{
            $data['permission'] = [
                "is_view"=>1,
                "is_download"=>0,
                "is_save"=>0
            ];
        }
        
        $data['ael'] = explode(',', $input['ael']);
        $data['adl'] = explode(',', $input['adl']);
        $data['ahl'] = explode(',', $input['ahl']);
        $data['aol'] = explode(',', $input['aol']);

        $data['fhl'] = $input['fhl'];
        $data['fml'] = $input['fml'];
        $data['pbl'] = $input['pbl'];
        $data['ftl'] = $input['ftl'];
        $data['ol'] = $input['ol'];
        $data['pl'] = $input['pl'];
        $data['amurange'] = $input['amurange'];
        
        $data['response'] = $input['response'];
        
        $data['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];
        $data['schemecode_id'] = $input['schemecode_id'];
        $data['shorting_id'] = isset($input['shorting_id'])?$input['shorting_id']:"";

        $data['asset_type_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('accord_sclass_mst.asset_type','Commodity')
                        ->orderBy('classname', 'asc')->get();
        $data['debt_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Debt')
                        ->orderBy('classname', 'asc')->get();
        $data['equity_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Equity')
                        ->orderBy('classname', 'asc')->get();
        $data['hybrid_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Hybrid')
                        ->orderBy('classname', 'asc')->get();
        $data['other_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->where('asset_type','Other')->orWhere('asset_type','Commodity')
                        ->orderBy('classname', 'asc')->get();
        // dd($data);
        $data['fund_house_list'] = DB::table("accord_amc_mst")->select(['amc_code','fund'])->orderBy('fund', 'asc')->get();
        $data['fund_manager_list'] = DB::table("accord_fundmanager_mst")->select(['id','fundmanager'])->orderBy('fundmanager', 'asc')->get();
        $data['primary_benchmark_list'] = DB::table("accord_index_mst")->select(['IndexCode','IndexName'])->orderBy('IndexName', 'asc')->get();
        $data['fund_type_list'] = DB::table("accord_type_mst")->select(['type_code','type'])->get();
        $data['option_list'] = DB::table("accord_option_mst")->select(['opt_code','option'])->get();
        $data['plan_list'] = DB::table("accord_plan_mst")->select(['accord_plan_mst.plan_code','accord_plan_mst.plan','mf_scanner_plan.name as planname'])->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')->where('accord_plan_mst.status',1)->get();

        $data['global_compare_list'] = [
            ['id'=>'0','name'=>'Category','is_checked'=>0,"key_name"=>'classname',"order"=>0,"type"=>0,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'1','name'=>'AUM (Rs Cr)','is_checked'=>0,"key_name"=>'total',"order"=>1,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'2','name'=>'NAV','is_checked'=>0,"key_name"=>'navrs',"order"=>1,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'3','name'=>'Expense Ratio (%)','is_checked'=>0,"key_name"=>'expratio',"order"=>3,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'4','name'=>'Exit Load (%)','is_checked'=>0,"key_name"=>'EXITLOAD',"order"=>4,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'5','name'=>'Inception Date','is_checked'=>0,"key_name"=>'Incept_date',"order"=>5,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ['id'=>'6','name'=>'Benchmark','is_checked'=>0,"key_name"=>'IndexName',"order"=>6,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"7","name"=>"1 Day","is_checked"=>0,"key_name"=>'1dayret',"order"=>7,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"8","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret',"order"=>8,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"9","name"=>"1 Month","is_checked"=>0,"key_name"=>'1monthret',"order"=>9,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"10","name"=>"3 Month","is_checked"=>0,"key_name"=>'3monthret',"order"=>10,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"11","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret',"order"=>11,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"12","name"=>"1 Year","is_checked"=>0,"key_name"=>'1yrret',"order"=>12,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"13","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret',"order"=>13,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"14","name"=>"3 Year","is_checked"=>0,"key_name"=>'3yearret',"order"=>14,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"15","name"=>"5 Year","is_checked"=>0,"key_name"=>'5yearret',"order"=>15,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"16","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret',"order"=>16,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"17","name"=>"Since Inception","is_checked"=>0,"key_name"=>'incret',"order"=>17,"type"=>2,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"18","name"=>"Turnover Ratio (%)","is_checked"=>0,"key_name"=>'turnover_ratio',"order"=>18,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"19","name"=>"No. of Stocks","is_checked"=>0,"key_name"=>'ASECT_CODE',"order"=>19,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"20","name"=>"Avg M-Cap (Rs Cr)","is_checked"=>0,"key_name"=>'MCAP',"order"=>20,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"21","name"=>"PE Ratio","is_checked"=>0,"key_name"=>'PE',"order"=>21,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"22","name"=>"PB Ratio","is_checked"=>0,"key_name"=>'PB',"order"=>22,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"23","name"=>"Dividend Yield","is_checked"=>0,"key_name"=>'Div_Yield',"order"=>23,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"24","name"=>"Large Cap (%)","is_checked"=>0,"key_name"=>'large_cap',"order"=>24,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"25","name"=>"Mid Cap (%)","is_checked"=>0,"key_name"=>'mid_cap',"order"=>25,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"26","name"=>"Small Cap (%)","is_checked"=>0,"key_name"=>'small_cap',"order"=>26,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"27","name"=>"Highest Sector Allocation","is_checked"=>0,"key_name"=>'highest_sector_all',"order"=>27,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"28","name"=>"Highest Sector Allocation %","is_checked"=>0,"key_name"=>'highest_sector_all_per',"order"=>28,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"29","name"=>"Average Maturity","is_checked"=>0,"key_name"=>'avg_mat_num',"order"=>29,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"30","name"=>"Modified Duration","is_checked"=>0,"key_name"=>'mod_dur_num',"order"=>30,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"31","name"=>"YTM (%)","is_checked"=>0,"key_name"=>'ytm',"order"=>31,"type"=>3,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"32","name"=>"Alpha","is_checked"=>0,"key_name"=>'alpha',"order"=>32,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"33","name"=>"Sharpe","is_checked"=>0,"key_name"=>'sharpe',"order"=>33,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"34","name"=>"Treynor","is_checked"=>0,"key_name"=>'treynor',"order"=>34,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"35","name"=>"Sortino","is_checked"=>0,"key_name"=>'sortino',"order"=>35,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"36","name"=>"Beta","is_checked"=>0,"key_name"=>'beta',"order"=>1,"type"=>36,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"37","name"=>"Standard Deviation","is_checked"=>0,"key_name"=>'sd',"order"=>37,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0],
            ["id"=>"38","name"=>"Tracking Error","is_checked"=>0,"key_name"=>'trackingError',"order"=>38,"type"=>4,"table_checkbox"=>0,"is_freeze"=>0]
        ];
        
        foreach($data['global_compare_list'] as $k1 => $v1){
            $value = $v1;
            $flag = false;
            $is_checked = 0;
            $table_checkbox = 0;
            $is_freeze = 0;
            $order = $v1['order'];
            foreach($data['response'] as $k2 => $v2){
                if($v2['id'] == $v1['id']){
                    $is_checked = 1;
                    $table_checkbox = $v2['table_checkbox'];
                    $is_freeze = $v2['is_freeze'];
                    $order = $v2['order'];
                    $flag = true;
                }
            }
            $data['global_compare_list'][$k1]['is_checked'] = $is_checked;
            $data['global_compare_list'][$k1]['table_checkbox'] = $table_checkbox;
            $data['global_compare_list'][$k1]['is_freeze'] = $is_freeze;
            $data['global_compare_list'][$k1]['order'] = $order;
        }

        $data['activemenu'] = 'mf-scanner';
         //dd($data);
        return view('frontend.mf_scanner.update_screener',$data);
    }

    public function list(Request $request){
        $input = $request->all();

        $result = DB::table("mf_scanner")->select(['mf_scanner.*','mf_scanner_classcode.name as class_name','mf_ratting.rate as rating'])
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_ratting', 'mf_ratting.schemecode', '=', 'mf_scanner.schemecode');

        if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
            $classcode = $input['ael'];
            if($classcode){ $classcode .= ","; }
            $classcode .= $input['adl'];
            if($classcode){ $classcode .= ","; }
            $classcode .= $input['ahl'];
            if($classcode){ $classcode .= ","; }
            $classcode .= $input['aol'];

            $classcodeArr = explode(',', $classcode);
            $result = $result->whereIn('mf_scanner.classcode', $classcodeArr);
        }else{
            $result = $result->where('mf_scanner.classcode','=', 0);
        }

        if(isset($input['fhl'])){
            $input['fhl'] = explode(',', $input['fhl']);
            $result = $result->whereIn('mf_scanner.amc_code', $input['fhl']);
        }else{
            $result = $result->where('mf_scanner.amc_code','=', 0);
        }
        if(isset($input['fml'])){
            $input['fml'] = explode(',', $input['fml']);
            $result = $result->whereIn('mf_scanner.fund_mgr_code1', $input['fml']);
        }else{
            $result = $result->where('mf_scanner.fund_mgr_code1','=', 0);
        }
        if(isset($input['pbl'])){
            $input['pbl'] = explode(',', $input['pbl']);
            $result = $result->whereIn('mf_scanner.INDEXCODE', $input['pbl']);
        }else{
            $result = $result->where('mf_scanner.INDEXCODE','=', 0);
        }
        if(isset($input['ftl'])){
            $input['ftl'] = explode(',', $input['ftl']);
            $result = $result->whereIn('mf_scanner.type_code', $input['ftl']);
        }else{
            $result = $result->where('mf_scanner.type_code','=', 0);
        }
        if(isset($input['ol'])){
            $input['ol'] = explode(',', $input['ol']);
            $result = $result->whereIn('mf_scanner.opt_code', $input['ol']);
        }else{
            $result = $result->where('mf_scanner.opt_code','=', 0);
        }
        if(isset($input['pl'])){
            $input['pl'] = explode(',', $input['pl']);
            $result = $result->whereIn('mf_scanner.plan', $input['pl']);
        }else{
            $result = $result->where('mf_scanner.plan','=', 0);
        }
        if(isset($input['amurange'])){
            $input['amurange'] = explode(',', $input['amurange']);
            $amurange = $input['amurange'];
            $result = $result->where(function($query) use ($amurange){
                foreach ($amurange as $key => $value) {
                    if($value == 1){
                        $min_price = 0;
                        $max_price = 50000;
                    }else if($value == 2){
                        $min_price = 50000;
                        $max_price = 75000;
                    }else if($value == 3){
                        $min_price = 75000;
                        $max_price = 200000;                
                    }else if($value == 4){
                        $min_price = 200000;
                        $max_price = 500000;
                    }else if($value == 5){
                        $min_price = 500000;
                        $max_price = 1000000;
                    }else if($value == 6){
                        $min_price = 1000000;
                        $max_price = 5000000;
                    }else if($value == 7){
                        $min_price = 5000000;
                        $max_price = 9999999900;
                    }
                    $query->orWhereBetween('mf_scanner.total', [$min_price, $max_price]);
                }
            });
        }


        if(isset($input['rating'])){
            $rating = explode(',', $input['rating']);
            // $ratings = [];
            $input['rating'] = $rating;
            $rating_flag = false;
            foreach ($rating as $key => $value) {
                if($value == 0){
                    $rating_flag = true;
                }
            }

            if($rating_flag){
                $result = $result->where(function ($query) use ($rating) {
                    $query->whereIn('mf_ratting.rate',$rating)->orWhereNull('rate');                  
                });
            }else{
                $result = $result->whereIn('mf_ratting.rate', $input['rating']);
            }
            
        }else{
            // $result = $result->where('mf_ratting.rating','=', 0);
        }

        session()->put('ms_mf_scenner_data',$input);
        // 
        $result = $result->where("mf_scanner.status",1)->get();

        return response()->json($result);
    }
    
    public function list_test(Request $request){
        
    }

    public function scanner_about(Request $request){
        $data['activemenu'] = 'about';
        return view('frontend.mf_scanner.about',$data);
    }

    public function scanner_saved_files(Request $request){
        session()->put('ms_mf_scenner_schemecode_id',"");
        $data['activemenu'] = 'savefile';
        $data['saved_file_lists'] = DB::table("mf_scanner_saved")->where('user_id',Auth::user()->id)->orderBy('id','desc')->paginate(10);
        return view('frontend.mf_scanner.savefile',$data);
    }
    
    public function mf_download_saved_file(Request $request){
        $id = $request->id;
        $data = DB::table("mf_scanner_saved")->where("id","=",$id)->first();
        // dd($data);
        if($data->type == 1){
            $input = unserialize($data->data);
            $input['name'] = $data->name;
            $input['mf_scanner_saved_id'] = $data->id;
            
            $data = [];
            // $result = DB::table("mf_scanner");
            $result = DB::table("mf_scanner")->select(['mf_scanner.*','mf_scanner_classcode.name as class_name','mf_ratting.rate as rating'])
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_ratting', 'mf_ratting.schemecode', '=', 'mf_scanner.schemecode');
                            
            if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
                $classcode = $input['ael'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['adl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['ahl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['aol'];
    
                $classcodeArr = explode(',', $classcode);
                $result = $result->whereIn('mf_scanner.classcode', $classcodeArr);
            }else{
                $result = $result->where('mf_scanner.classcode','=', 0);
            }
    
            if(isset($input['fhl'])){
                // $input['fhl'] = explode(',', $input['fhl']);
                $result = $result->whereIn('mf_scanner.amc_code', $input['fhl']);
            }else{
                $result = $result->where('mf_scanner.amc_code','=', 0);
            }
            if(isset($input['fml'])){
                // $input['fml'] = explode(',', $input['fml']);
                $result = $result->whereIn('mf_scanner.fund_mgr_code1', $input['fml']);
            }else{
                $result = $result->where('mf_scanner.fund_mgr_code1','=', 0);
            }
            if(isset($input['pbl'])){
                // $input['pbl'] = explode(',', $input['pbl']);
                $result = $result->whereIn('mf_scanner.INDEXCODE', $input['pbl']);
            }else{
                $result = $result->where('mf_scanner.INDEXCODE','=', 0);
            }
            if(isset($input['ftl'])){
                // $input['ftl'] = explode(',', $input['ftl']);
                $result = $result->whereIn('mf_scanner.type_code', $input['ftl']);
            }else{
                $result = $result->where('mf_scanner.type_code','=', 0);
            }
            if(isset($input['ol'])){
                // $input['ol'] = explode(',', $input['ol']);
                $result = $result->whereIn('mf_scanner.opt_code', $input['ol']);
            }else{
                $result = $result->where('mf_scanner.opt_code','=', 0);
            }
            if(isset($input['pl'])){
                // $input['pl'] = explode(',', $input['pl']);
                $result = $result->whereIn('mf_scanner.plan', $input['pl']);
            }else{
                $result = $result->where('mf_scanner.plan','=', 0);
            }
            
            if(isset($input['amurange'])){
                // $input['amurange'] = explode(',', $input['amurange']);
                $amurange = $input['amurange'];
                $result = $result->where(function($query) use ($amurange){
                    foreach ($amurange as $key => $value) {
                        if($value == 1){
                            $min_price = 0;
                            $max_price = 50000;
                        }else if($value == 2){
                            $min_price = 50000;
                            $max_price = 75000;
                        }else if($value == 3){
                            $min_price = 75000;
                            $max_price = 200000;                
                        }else if($value == 4){
                            $min_price = 200000;
                            $max_price = 500000;
                        }else if($value == 5){
                            $min_price = 500000;
                            $max_price = 1000000;
                        }else if($value == 6){
                            $min_price = 1000000;
                            $max_price = 5000000;
                        }else if($value == 7){
                            $min_price = 5000000;
                            $max_price = 9999999900;
                        }
                        $query->orWhereBetween('mf_scanner.total', [$min_price, $max_price]);
                    }
                });
            }
    
    
            if(isset($input['rating'])){
                // $rating = explode(',', $input['rating']);
                // $ratings = [];
                $rating = $input['rating'];
                $rating_flag = false;
                foreach ($rating as $key => $value) {
                    if($value == 0){
                        $rating_flag = true;
                    }
                }
    
                if($rating_flag){
                    $result = $result->where(function ($query) use ($rating) {
                        $query->whereIn('mf_ratting.rate',$rating)->orWhereNull('rate');                  
                    });
                }else{
                    $result = $result->whereIn('mf_ratting.rate', $input['rating']);
                }
                
            }else{
                // $result = $result->where('mf_ratting.rating','=', 0);
            }
            
            if($input['schemecode_id']){
                $schemecode_ids = explode(",", $input['schemecode_id']);
                $result = $result->whereIn('mf_scanner.schemecode',$schemecode_ids);
            }
    
            $result = $result->where('mf_scanner.status',1)->orderBy($input['order_by'], $input['ordering'])->groupBy('mf_scanner.schemecode')->get();
            // dd($input);
            $data['result'] = $result;
            $data['response'] = $input['response'];
            
            $data['name'] = $input['name'];
            $data['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];
            
            if (Auth::check()){
                // dd($data);
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $data['amfi_registered'] = $displayInfo->amfi_registered;
                $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $data['watermark'] = 0;
                }else{
                    $data['watermark'] = 1;
                }
            }else{
                $data['name'] = "";
                $data['company_name'] = "";
                $data['amfi_registered'] = "";
                $data['phone_no'] = "";
                $data['email'] = "";
                $data['website'] = "";
                $data['company_logo'] = "";
                $data['watermark'] = "";
            }
            // dd($data);
            // return view('frontend.mf_scanner.pdf',$data);
            if(count($data['response']) <= 8){
                $pdf = PDF::loadView('frontend.mf_scanner.pdf', $data);
            }else{
                $pdf = PDF::loadView('frontend.mf_scanner.landscape_pdf', $data)->setPaper('a4', 'landscape');
            }
            return $pdf->download('MF Screener.pdf');
        } else if($data->type == 2){
            $input = unserialize($data->data);
            // dd($input);
            $pdf_data = [];
            $pdf_data['file_name'] = $data->name;
            // $pdf_data['checkbox'] = $input['checkbox'];
            $schemecode_id = $input['schemecode_id'];
            $pdf_data['result'] = $this->getCompareScheamDetail($schemecode_id);
            if($input['basic_detail_checkbox']){
                $pdf_data['basic_detail_checkbox'] = explode(',',$input['basic_detail_checkbox']);
            }else{
                $pdf_data['basic_detail_checkbox'] = [];
            }
            if($input['return_checkbox']){
                $pdf_data['return_checkbox'] = explode(',',$input['return_checkbox']);
            }else{
                $pdf_data['return_checkbox'] = [];
            }
            if($input['mf_ratios_checkbox']){
                $pdf_data['mf_ratios_checkbox'] = explode(',',$input['mf_ratios_checkbox']);
            }else{
                $pdf_data['mf_ratios_checkbox'] = [];
            }
            if($input['portfolio_checkbox']){
                $pdf_data['portfolio_checkbox'] = explode(',',$input['portfolio_checkbox']);
            }else{
                $pdf_data['portfolio_checkbox'] = [];
            }
            if($input['sector_checkbox']){
                $pdf_data['sector_checkbox'] = explode(',',$input['sector_checkbox']);
            }else{
                $pdf_data['sector_checkbox'] = [];
            }
            $pdf_data['holding_checkbox'] = $input['holding_checkbox'];
            $pdf_data['rating_checkbox'] = isset($input['rating_checkbox'])?$input['rating_checkbox']:0;
            
            // dd($pdf_data);
            if (Auth::check()){
                // dd($pdf_data);
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $pdf_data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $pdf_data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $pdf_data['amfi_registered'] = $displayInfo->amfi_registered;
                $pdf_data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $pdf_data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $pdf_data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $pdf_data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $pdf_data['watermark'] = 0;
                }else{
                    $pdf_data['watermark'] = 1;
                }
            }else{
                $pdf_data['name'] = "";
                $pdf_data['company_name'] = "";
                $pdf_data['amfi_registered'] = "";
                $pdf_data['phone_no'] = "";
                $pdf_data['email'] = "";
                $pdf_data['website'] = "";
                $pdf_data['company_logo'] = "";
                $pdf_data['watermark'] = "";
            }
            
            
            // print_r(serialize($pdf_data));
            
            // exit;
            // return view('frontend.mf_scanner.compare_pdf',$pdf_data);
            $pdf = PDF::loadView('frontend.mf_scanner.compare_pdf', $pdf_data);
            return $pdf->download('mf_comparison.pdf');
        } else if($data->type == 3){
            $input = unserialize($data->data);
            $input['name'] = $data->name;
            $input['mf_scanner_saved_id'] = $data->id;
            
            $data = [];
            $result = DB::table("mf_scanner_avg")
                    ->select(["mf_scanner_avg.*","mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner_avg.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner_avg.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode');
            // dd($input);
            if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
                $classcode = $input['ael'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['adl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['ahl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['aol'];

                $classcodeArr = explode(',', $classcode);
                $result = $result->whereIn('mf_scanner_avg.classcode', $classcodeArr);
            }else{
                $result = $result->where('mf_scanner_avg.classcode','=', 0);
            }
            
            if($input['schemecode_id']){
                $schemecode_ids = explode(",", $input['schemecode_id']);
                $result = $result->whereIn('mf_scanner_avg.classcode',$schemecode_ids);
            }
            
            // dd($data);


            if(isset($input['plan'])){
                if($input['plan']){
                    $plan_code = explode(',', $input['plan']);
                    $result = $result->whereIn('mf_scanner_avg.plan_code', $plan_code);
                }
            }
            $result = $result->get();
            // dd($input);
            // dd($input);
            $data['result'] = $result;
            $data['crkey'] = $input['crkey'];
            $data['cbd'] = $input['cbd'];
            $data['name'] = $input['name'];
            $data['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];

            if (Auth::check()){
                // dd($data);
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $data['amfi_registered'] = $displayInfo->amfi_registered;
                $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $data['watermark'] = 0;
                }else{
                    $data['watermark'] = 1;
                }
            }else{
                $data['name'] = "";
                $data['company_name'] = "";
                $data['amfi_registered'] = "";
                $data['phone_no'] = "";
                $data['website'] = "";
                $data['company_logo'] = "";
                $data['watermark'] = "";
                $data['email'] = "";
            }
            // dd($data);
            // return view('frontend.mf_scanner.category_wise_performance_pdf',$data);
            $pdf = PDF::loadView('frontend.mf_scanner.category_wise_performance_pdf', $data);
            return $pdf->download('mf_category_wise_performance.pdf');
        } else if($data->type == 4){
            
        } else if($data->type == 5){
            $input = unserialize($data->data);
            $input['name'] = $data->name;
            $input['mf_scanner_saved_id'] = $data->id;

            $input['oneday'] = "";
            $input['oneweek'] = "";
            $input['onemonth'] = "";
            $input['threemonth'] = "";
            $input['sixmonth'] = "";
            $input['oneyear'] = "";
            $input['twoyear'] = "";
            $input['threeyear'] = "";
            $input['fiveyear'] = "";
            $input['tenyear'] = "";

            if($input['query_period'] == "1dayret"){
                $input['oneday'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "1weekret"){
                $input['oneweek'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "1monthret"){
                $input['onemonth'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "3monthret"){
                $input['threemonth'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "6monthret"){
                $input['sixmonth'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "1yrret"){
                $input['oneyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "2yearret"){
                $input['twoyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "3yearret"){
                $input['threeyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "5yearret"){
                $input['fiveyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "10yret" || $input['query_period'] == "10yearret"){
                $input['tenyear'] = "background:#a2d2f7;";
            }
            
            $result = DB::table("mf_scanner_avg")
                    ->select(['1dayret as onedayret','1weekret as oneweekret', '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret', '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yearret as tenyret',"mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner_avg.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner_avg.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode');

            $result = $result->where('mf_scanner_avg.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner_avg.plan_code', $plan_code);
            }

            $input['avg_data'] = $result->first();

            $result = DB::table("mf_scanner")
                        ->select(['schemecode','primary_fd_code','scheme_name','s_name','1dayret as onedayret','1weekret as oneweekret', '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret', '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yret as tenyret','short_categories.short_name','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('short_categories', 'short_categories.category_name', '=', 'accord_sclass_mst.classname');

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }

            $input['best_data'] = $result->orderBy($input['query_period'],'desc')->take($input['no_of_funds'])->get();

            $result = DB::table("mf_scanner")
                        ->select(['schemecode','primary_fd_code','scheme_name','s_name','1dayret as onedayret','1weekret as oneweekret', '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret', '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yret as tenyret','short_categories.short_name','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('short_categories', 'short_categories.category_name', '=', 'accord_sclass_mst.classname');

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }

            $input['worst_data'] = $result->orderBy($input['query_period'],'asc')->take($input['no_of_funds'])->get();
            $input['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];

            if (Auth::check()){
                // dd($input);
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $input['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $input['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $input['amfi_registered'] = $displayInfo->amfi_registered;
                $input['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $input['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $input['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $input['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $input['watermark'] = 0;
                }else{
                    $input['watermark'] = 1;
                }
            }else{
                $input['name'] = "";
                $input['company_name'] = "";
                $input['amfi_registered'] = "";
                $input['phone_no'] = "";
                $input['website'] = "";
                $input['company_logo'] = "";
                $input['watermark'] = "";
                $input['email'] = "";
            }
            // dd($input);
            // return view('frontend.mf_scanner.category_wise_performance_pdf',$input);
            $pdf = PDF::loadView('frontend.categorywiseperformance.best_worst_pdf', $input);
            return $pdf->download('mf_best_worst.pdf');
        } else if($data->type == 6){
            $input = unserialize($data->data);
            $input['name'] = $data->name;
            $input['mf_scanner_saved_id'] = $data->id;
            if($input['schemecode_id']){
                $last_date = DB::table('accord_mf_portfolio')->where('fincode','=',$input['schemecode_id'])->groupBy('invdate')->orderBy('invdate','DESC')->take(4)->get();
                
                if(count($last_date)){
                    $companymaster_detail = DB::table('accord_companymaster')->where('fincode','=',$input['schemecode_id'])->first();
                    $current_date = isset($last_date[0])?$last_date[0]->invdate:'';
                    $one_month_date = isset($last_date[1])?$last_date[1]->invdate:'';
                    $two_month_date = isset($last_date[2])?$last_date[2]->invdate:'';
                    $three_month_date = isset($last_date[3])?$last_date[3]->invdate:'';
                    
                    $number_of_fund = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$current_date)->count();
                    $current_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$current_date)->sum('holdpercentage');
                    $one_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$one_month_date)->sum('holdpercentage');
                    $two_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$two_month_date)->sum('holdpercentage');
                    $three_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$three_month_date)->sum('holdpercentage');

                    $input['detail'] = [
                        "sector"=>$last_date[0]->sect_name,
                        "compname"=>$companymaster_detail->compname,
                        "number_of_fund"=>$number_of_fund,
                        "current_month"=>$current_month,
                        "one_month"=>$one_month,
                        "two_month"=>$two_month,
                        "three_month"=>$three_month,
                        "current_date"=>$current_date,
                        "one_month_date"=>$one_month_date,
                        "two_month_date"=>$two_month_date,
                        "three_month_date"=>$three_month_date
                    ];
                    $input['list'] = DB::table('mf_stock_helds')->where('fincode','=',$companymaster_detail->fincode)->get();
                }else{
                    $input['detail'] = [
                        "sector"=>"",
                        "compname"=>"",
                        "number_of_fund"=>"",
                        "current_month"=>"",
                        "one_month"=>"",
                        "two_month"=>"",
                        "three_month"=>"",
                        "current_date"=>"",
                        "one_month_date"=>"",
                        "two_month_date"=>"",
                        "three_month_date"=>""
                    ];
                    $input['list'] = [];
                }
                
            }else{
                $input['detail'] = [
                    "sector"=>"",
                    "compname"=>"",
                    "number_of_fund"=>"",
                    "current_month"=>"",
                    "one_month"=>"",
                    "two_month"=>"",
                    "three_month"=>"",
                    "current_date"=>"",
                    "one_month_date"=>"",
                    "two_month_date"=>"",
                    "three_month_date"=>""
                ];
                $input['list'] = [];
            }
            if (Auth::check()){
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $input['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $input['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $input['amfi_registered'] = $displayInfo->amfi_registered;
                $input['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $input['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $input['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $input['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $input['watermark'] = 0;
                }else{
                    $input['watermark'] = 1;
                }
            }else{
                $input['name'] = "";
                $input['company_name'] = "";
                $input['amfi_registered'] = "";
                $input['phone_no'] = "";
                $input['website'] = "";
                $input['company_logo'] = "";
                $input['watermark'] = "";
                $input['email'] = "";
            }
            // dd($data);
            // return view('frontend.mf_scanner.stocks_held_pdf',$data);
            $pdf = PDF::loadView('frontend.mf_scanner.stocks_held_pdf', $input);
            return $pdf->download('stocks_held_pdf.pdf');
        }
    }

    public function mf_view_saved_file_details(Request $request){
        $input = $request->all();
        $data = DB::table("mf_scanner_saved")->where("id","=",$input['id'])->first();
        $input = unserialize($data->data);
        $input['name'] = $data->name;
        $input['mf_scanner_saved_id'] = $data->id;
        
        if($data->type == 1){
            $result = DB::table("mf_scanner")->select(['mf_scanner.*','mf_scanner_classcode.name as class_name','mf_ratting.rate as rating'])
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_ratting', 'mf_ratting.schemecode', '=', 'mf_scanner.schemecode');
                            
            if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
                $classcode = $input['ael'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['adl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['ahl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['aol'];
    
                $classcodeArr = explode(',', $classcode);
                $result = $result->whereIn('mf_scanner.classcode', $classcodeArr);
            }else{
                $result = $result->where('mf_scanner.classcode','=', 0);
            }
    
            if(isset($input['fhl'])){
                // $input['fhl'] = explode(',', $input['fhl']);
                $result = $result->whereIn('mf_scanner.amc_code', $input['fhl']);
            }else{
                $result = $result->where('mf_scanner.amc_code','=', 0);
            }
            if(isset($input['fml'])){
                // $input['fml'] = explode(',', $input['fml']);
                $result = $result->whereIn('mf_scanner.fund_mgr_code1', $input['fml']);
            }else{
                $result = $result->where('mf_scanner.fund_mgr_code1','=', 0);
            }
            if(isset($input['pbl'])){
                // $input['pbl'] = explode(',', $input['pbl']);
                $result = $result->whereIn('mf_scanner.INDEXCODE', $input['pbl']);
            }else{
                $result = $result->where('mf_scanner.INDEXCODE','=', 0);
            }
            if(isset($input['ftl'])){
                // $input['ftl'] = explode(',', $input['ftl']);
                $result = $result->whereIn('mf_scanner.type_code', $input['ftl']);
            }else{
                $result = $result->where('mf_scanner.type_code','=', 0);
            }
            if(isset($input['ol'])){
                // $input['ol'] = explode(',', $input['ol']);
                $result = $result->whereIn('mf_scanner.opt_code', $input['ol']);
            }else{
                $result = $result->where('mf_scanner.opt_code','=', 0);
            }
            if(isset($input['pl'])){
                // $input['pl'] = explode(',', $input['pl']);
                $result = $result->whereIn('mf_scanner.plan', $input['pl']);
            }else{
                $result = $result->where('mf_scanner.plan','=', 0);
            }
            
            if(isset($input['amurange'])){
                // $input['amurange'] = explode(',', $input['amurange']);
                $amurange = $input['amurange'];
                $result = $result->where(function($query) use ($amurange){
                    foreach ($amurange as $key => $value) {
                        if($value == 1){
                            $min_price = 0;
                            $max_price = 50000;
                        }else if($value == 2){
                            $min_price = 50000;
                            $max_price = 75000;
                        }else if($value == 3){
                            $min_price = 75000;
                            $max_price = 200000;                
                        }else if($value == 4){
                            $min_price = 200000;
                            $max_price = 500000;
                        }else if($value == 5){
                            $min_price = 500000;
                            $max_price = 1000000;
                        }else if($value == 6){
                            $min_price = 1000000;
                            $max_price = 5000000;
                        }else if($value == 7){
                            $min_price = 5000000;
                            $max_price = 9999999900;
                        }
                        $query->orWhereBetween('mf_scanner.total', [$min_price, $max_price]);
                    }
                });
            }
    
    
            if(isset($input['rating'])){
                // $rating = explode(',', $input['rating']);
                // $ratings = [];
                $rating = $input['rating'];
                $rating_flag = false;
                foreach ($rating as $key => $value) {
                    if($value == 0){
                        $rating_flag = true;
                    }
                }
    
                if($rating_flag){
                    $result = $result->where(function ($query) use ($rating) {
                        $query->whereIn('mf_ratting.rate',$rating)->orWhereNull('rate');                  
                    });
                }else{
                    $result = $result->whereIn('mf_ratting.rate', $input['rating']);
                }
                
            }else{
                // $result = $result->where('mf_ratting.rating','=', 0);
            }
            if($input['schemecode_id']){
                $schemecode_ids = explode(",", $input['schemecode_id']);
                $result = $result->whereIn('mf_scanner.schemecode',$schemecode_ids);
            }
    
            $result = $result->where('mf_scanner.status',1)->orderBy($input['order_by'], $input['ordering'])->groupBy('mf_scanner.schemecode')->get();
            // dd($result);
            // dd(DB::getQueryLog());
            $input['result'] = $result;
            // dd($input);
            return view('frontend.mf_scanner.view_save_file',$input);
        }else if($data->type == 2){
            
            $schemecode_id = $input['schemecode_id'];
            $input['result'] = $this->getCompareScheamDetail($schemecode_id);
            if($input['basic_detail_checkbox']){
                $input['basic_detail_checkbox'] = explode(',',$input['basic_detail_checkbox']);
            }else{
                $input['basic_detail_checkbox'] = [];
            }
            if($input['return_checkbox']){
                $input['return_checkbox'] = explode(',',$input['return_checkbox']);
            }else{
                $pdf_data['return_checkbox'] = [];
            }
            if($input['mf_ratios_checkbox']){
                $input['mf_ratios_checkbox'] = explode(',',$input['mf_ratios_checkbox']);
            }else{
                $input['mf_ratios_checkbox'] = [];
            }
            if($input['portfolio_checkbox']){
                $input['portfolio_checkbox'] = explode(',',$input['portfolio_checkbox']);
            }else{
                $input['portfolio_checkbox'] = [];
            }
            if($input['sector_checkbox']){
                $input['sector_checkbox'] = explode(',',$input['sector_checkbox']);
            }else{
                $input['sector_checkbox'] = [];
            }
            if($input['holding_checkbox']){
                $input['holding_checkbox'] = $input['holding_checkbox'];
            }else{
                $input['holding_checkbox'] = 0;
            }
            
            // dd($input);
            
            return view('frontend.mf_scanner.view_save_file_compare',$input);
        }else if($data->type == 3){
            
            // dd($input);
            $result = DB::table("mf_scanner_avg")
                    ->select(["mf_scanner_avg.*","mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner_avg.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner_avg.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode');

            if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
                $classcode = $input['ael'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['adl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['ahl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['aol'];

                $classcodeArr = explode(',', $classcode);
                $result = $result->whereIn('mf_scanner_avg.classcode', $classcodeArr);
            }else{
                $result = $result->where('mf_scanner_avg.classcode','=', 0);
            }

            if($input['schemecode_id']){
                $schemecode_ids = explode(",", $input['schemecode_id']);
                $result = $result->whereIn('mf_scanner_avg.classcode',$schemecode_ids);
            }

            if(isset($input['plan'])){
                if($input['plan']){
                    $plan_code = explode(',', $input['plan']);
                    $result = $result->whereIn('mf_scanner_avg.plan_code', $plan_code);
                }
            }
            // dd($input);
            $result = $result->orderBy($input['order_by'], $input['ordering'])->get();
            
            // dd(DB::getQueryLog());
            $input['result'] = $result;
            return view('frontend.mf_scanner.view_save_file_cwp',$input);
        }else if($data->type == 4){
            
            $data = [];

            // dd($input);
            // DB::enableQueryLog();
            // $result = DB::table("mf_scanner");
            $result = DB::table("mf_scanner")
                    ->select(['mf_scanner.schemecode','mf_scanner.s_name','mf_scanner.classcode','mf_scanner.plan','mf_scanner.1dayret','mf_scanner.1weekret','mf_scanner.1monthret','mf_scanner.3monthret','mf_scanner.6monthret','mf_scanner.1yrret','mf_scanner.2yearret','mf_scanner.3yearret','mf_scanner.5yearret','mf_scanner.10yret']);

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }

            if($input['schemecode_id']){
                $schemecode_ids = explode(",", $input['schemecode_id']);
                $result = $result->whereIn('schemecode',$schemecode_ids);
            }

            $result = $result->orderBy($input['order_by'], $input['ordering'])->get();

            foreach ($result as $k1 => $v1) {
                $v1 = (array) $v1;
                if(isset($input['calender'])){
                    $calender = explode(",", $input['calender']);
                    foreach ($calender as $k2 => $v2) {
                        $category_wise_performance = DB::table("mf_category_wise_performance")->where('schemecode',$v1['schemecode'])->where('year',$v2)->first();
                        if($category_wise_performance){
                            $v1[$v2] = $category_wise_performance->aum;
                        }else{
                            $v1[$v2] = "";
                        }
                    }
                }
                if(isset($input['point_to_point'])){
                    foreach ($input['point_to_point'] as $k2 => $v2) {
                        $category_wise_performance = DB::table("mf_category_wise_performance")->where('schemecode',$v1['schemecode'])->where('year',$v2)->first();
                        if($category_wise_performance){
                            $v1[$v2['start_date']."-".$v2['end_date']] = $category_wise_performance->aum;
                        }else{
                            $v1[$v2['start_date']."-".$v2['end_date']] = "";
                        }
                    }
                }
                $result[$k1] = $v1;
            }
            
            // dd(DB::getQueryLog());
            $input['result'] = $result;
            // dd($input);
            return view('frontend.categorywiseperformance.view',$input);
        }else if($data->type == 5){

            $input['oneday'] = "";
            $input['oneweek'] = "";
            $input['onemonth'] = "";
            $input['threemonth'] = "";
            $input['sixmonth'] = "";
            $input['oneyear'] = "";
            $input['twoyear'] = "";
            $input['threeyear'] = "";
            $input['fiveyear'] = "";
            $input['tenyear'] = "";

            if($input['query_period'] == "1dayret"){
                $input['oneday'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "1weekret"){
                $input['oneweek'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "1monthret"){
                $input['onemonth'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "3monthret"){
                $input['threemonth'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "6monthret"){
                $input['sixmonth'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "1yrret"){
                $input['oneyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "2yearret"){
                $input['twoyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "3yearret"){
                $input['threeyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "5yearret"){
                $input['fiveyear'] = "background:#a2d2f7;";
            }else if($input['query_period'] == "10yret" || $input['query_period'] == "10yearret"){
                $input['tenyear'] = "background:#a2d2f7;";
            }

            $result = DB::table("mf_scanner_avg")
                    ->select(['1dayret as onedayret','1weekret as oneweekret', '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret', '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yearret as tenyret',"mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner_avg.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner_avg.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode');

            $result = $result->where('mf_scanner_avg.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner_avg.plan_code', $plan_code);
            }

            $input['avg_data'] = $result->first();

            $result = DB::table("mf_scanner")
                        ->select(['schemecode','primary_fd_code','scheme_name','s_name','1dayret as onedayret','1weekret as oneweekret', '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret', '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yret as tenyret','short_categories.short_name','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('short_categories', 'short_categories.category_name', '=', 'accord_sclass_mst.classname');

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }

            $input['best_data'] = $result->orderBy($input['query_period'],'desc')->take($input['no_of_funds'])->get();

            $result = DB::table("mf_scanner")
                        ->select(['schemecode','primary_fd_code','scheme_name','s_name','1dayret as onedayret','1weekret as oneweekret', '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret', '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yret as tenyret','short_categories.short_name','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('short_categories', 'short_categories.category_name', '=', 'accord_sclass_mst.classname');

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }

            $input['worst_data'] = $result->orderBy($input['query_period'],'asc')->take($input['no_of_funds'])->get();
            
            // dd(DB::getQueryLog());
            // $input['result'] = $result;
            // dd($input);
            return view('frontend.categorywiseperformance.best_worst_view',$input);
        } else if($data->type == 6){
            $input = unserialize($data->data);
            // dd($input);
            $input['name'] = $data->name;
            $input['mf_scanner_saved_id'] = $data->id;
            if($input['schemecode_id']){
                $last_date = DB::table('accord_mf_portfolio')->where('fincode','=',$input['schemecode_id'])->groupBy('invdate')->orderBy('invdate','DESC')->take(4)->get();
                
                if(count($last_date)){
                    $companymaster_detail = DB::table('accord_companymaster')->where('fincode','=',$input['schemecode_id'])->first();
                    $current_date = isset($last_date[0])?$last_date[0]->invdate:'';
                    $one_month_date = isset($last_date[1])?$last_date[1]->invdate:'';
                    $two_month_date = isset($last_date[2])?$last_date[2]->invdate:'';
                    $three_month_date = isset($last_date[3])?$last_date[3]->invdate:'';
                    
                    $number_of_fund = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$current_date)->count();
                    $current_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$current_date)->sum('holdpercentage');
                    $one_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$one_month_date)->sum('holdpercentage');
                    $two_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$two_month_date)->sum('holdpercentage');
                    $three_month = DB::table('accord_mf_portfolio')->where('fincode','=',$companymaster_detail->fincode)->where('invdate','=',$three_month_date)->sum('holdpercentage');

                    $input['detail'] = [
                        "sector"=>$last_date[0]->sect_name,
                        "number_of_fund"=>$number_of_fund,
                        "current_month"=>$current_month,
                        "one_month"=>$one_month,
                        "two_month"=>$two_month,
                        "three_month"=>$three_month,
                        "current_date"=>$current_date,
                        "one_month_date"=>$one_month_date,
                        "two_month_date"=>$two_month_date,
                        "three_month_date"=>$three_month_date
                    ];
                    $input['list'] = DB::table('mf_stock_helds')->where('fincode','=',$companymaster_detail->fincode)->get();
                }else{
                    $input['detail'] = [
                        "sector"=>"",
                        "number_of_fund"=>"",
                        "current_month"=>"",
                        "one_month"=>"",
                        "two_month"=>"",
                        "three_month"=>"",
                        "current_date"=>"",
                        "one_month_date"=>"",
                        "two_month_date"=>"",
                        "three_month_date"=>""
                    ];
                    $input['list'] = [];
                }
                
            }else{
                $input['detail'] = [
                    "sector"=>"",
                    "number_of_fund"=>"",
                    "current_month"=>"",
                    "one_month"=>"",
                    "two_month"=>"",
                    "three_month"=>""
                ];
                $input['list'] = [];
            }
            return view('frontend.mf_scanner.stocks_held_by_mutual_fund_view',$input);
        }

    }

    public function mf_scanner_scheme_list(Request $request){
        $input = $request->all();
        $schemecode_id = session()->get('ms_mf_scenner_schemecode_id');
        $schemecode_ids = explode(",", $schemecode_id);

        if($input['compare_assert_type']){
            $categoryList = DB::table("accord_sclass_mst")->select(['classcode','classname'])->where('asset_type',$input['compare_assert_type'])->orderBy('classname', 'asc')->get();
        }else{
            $categoryList = DB::table("accord_sclass_mst")->select(['classcode','classname'])->orderBy('classname', 'asc')->get();
        }
        if($input['compare_category']){
            $schemeList = DB::table("mf_scanner")->select(['schemecode','s_name'])->where("classcode","=",$input['compare_category'])->whereNotIn('schemecode',$schemecode_ids)->get();
        }else{
            if($input['compare_assert_type']){
                $schemeList = DB::table("mf_scanner")->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')->select(['mf_scanner.schemecode','mf_scanner.s_name'])->where('accord_sclass_mst.asset_type',$input['compare_assert_type'])->whereNotIn('mf_scanner.schemecode',$schemecode_ids)->get();
            }else{
                $schemeList = DB::table("mf_scanner")->select(['schemecode','s_name'])->whereNotIn('schemecode',$schemecode_ids)->get();
            }
        }
        return ["categoryList"=>$categoryList,"schemeList"=>$schemeList];
        // $iHtml = `<option value=""> </option>`;
        // foreach ($result as $key => $value) {
        //     $iHtml .= `<option value="`.$value->schemecode.`">`.$value->s_name.`</option>`;
        // }
        // echo $iHtml; exit;
    }

    public function submit(Request $request){
        $input = $request->all();
        
        $page_type = isset($input['page_type'])?$input['page_type']:'';
        $schemecode_id = isset($input['schemecode_id'])?$input['schemecode_id']:'';
        $save_title = isset($input['save_title'])?$input['save_title']:'';
        $all_colum_list = isset($input['all_colum_list'])?$input['all_colum_list']:'';
        $download_type = isset($input['download_type'])?$input['download_type']:'';
        session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
        if($page_type == "COMPARE"){
            return redirect()->route('frontend.mf_scanner_compare');
        }else if($page_type == "DOWNLOAD"){
            if(!Auth::check()){
                return redirect('login');
            }
            // dd($input);
            // $expry = Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
            // if(empty($expry)){
            //     return redirect('membership');
            // }
            
            $input = session()->get('ms_mf_scenner_data');
            $global_compare_list = json_decode($all_colum_list);
            
            $all_colum_list = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else{
                $order_by = "total";
                $ordering = $randhir2[1];
                $order_index = $randhir2[0] - 2;
                // dd($global_compare_list[$order_index]);
                $order_by = $global_compare_list[$order_index]->key_name;
            }
            
            $data = [];

            $result = DB::table("mf_scanner")->select(['mf_scanner.*','mf_scanner_classcode.name as class_name','mf_ratting.rate as rating'])
                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                    ->LeftJoin('mf_ratting', 'mf_ratting.schemecode', '=', 'mf_scanner.schemecode');

            if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
                $classcode = $input['ael'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['adl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['ahl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['aol'];

                $classcodeArr = explode(',', $classcode);
                $result = $result->whereIn('mf_scanner.classcode', $classcodeArr);
            }else{
                $result = $result->where('mf_scanner.classcode','=', 0);
            }

            if(isset($input['fhl'])){
                // $input['fhl'] = explode(',', $input['fhl']);
                $result = $result->whereIn('mf_scanner.amc_code', $input['fhl']);
            }else{
                $result = $result->where('mf_scanner.amc_code','=', 0);
            }

            if(isset($input['fml'])){
                // $input['fml'] = explode(',', $input['fml']);
                $result = $result->whereIn('mf_scanner.fund_mgr_code1', $input['fml']);
            }else{
                $result = $result->where('mf_scanner.fund_mgr_code1','=', 0);
            }
            if(isset($input['pbl'])){
                // $input['pbl'] = explode(',', $input['pbl']);
                $result = $result->whereIn('mf_scanner.INDEXCODE', $input['pbl']);
            }else{
                $result = $result->where('mf_scanner.INDEXCODE','=', 0);
            }
            if(isset($input['ftl'])){
                // $input['ftl'] = explode(',', $input['ftl']);
                $result = $result->whereIn('mf_scanner.type_code', $input['ftl']);
            }else{
                $result = $result->where('mf_scanner.type_code','=', 0);
            }
            if(isset($input['ol'])){
                // $input['ol'] = explode(',', $input['ol']);
                $result = $result->whereIn('mf_scanner.opt_code', $input['ol']);
            }else{
                $result = $result->where('mf_scanner.opt_code','=', 0);
            }
            if(isset($input['pl'])){
                // $input['pl'] = explode(',', $input['pl']);
                $result = $result->whereIn('mf_scanner.plan', $input['pl']);
            }else{
                $result = $result->where('mf_scanner.plan','=', 0);
            }
            if(isset($input['amurange'])){
                // $input['amurange'] = explode(',', $input['amurange']);
                $amurange = $input['amurange'];
                $result = $result->where(function($query) use ($amurange){
                    foreach ($amurange as $key => $value) {
                        if($value == 1){
                            $min_price = 0;
                            $max_price = 50000;
                        }else if($value == 2){
                            $min_price = 50000;
                            $max_price = 75000;
                        }else if($value == 3){
                            $min_price = 75000;
                            $max_price = 200000;                
                        }else if($value == 4){
                            $min_price = 200000;
                            $max_price = 500000;
                        }else if($value == 5){
                            $min_price = 500000;
                            $max_price = 1000000;
                        }else if($value == 6){
                            $min_price = 1000000;
                            $max_price = 5000000;
                        }else if($value == 7){
                            $min_price = 5000000;
                            $max_price = 9999999900;
                        }
                        $query->orWhereBetween('mf_scanner.total', [$min_price, $max_price]);
                    }
                });
            }

            if(isset($input['rating'])){
                $rating = $input['rating'];
                $rating_flag = false;
                foreach ($rating as $key => $value) {
                    if($value == 0){
                        $rating_flag = true;
                    }
                }

                if($rating_flag){
                    $result = $result->where(function ($query) use ($rating) {
                        $query->whereIn('mf_ratting.rate',$rating)->orWhereNull('rate');                  
                    });
                }else{
                    $result = $result->whereIn('mf_ratting.rate', $input['rating']);
                }
                
            }

            if($schemecode_id){
                $schemecode_ids = explode(",", $schemecode_id);
                $result = $result->whereIn('mf_scanner.schemecode',$schemecode_ids);
            }
            
            $result = $result->orderBy($order_by, $ordering)->groupBy('mf_scanner.schemecode')->get();
            // dd($result);
            
            // dd(DB::getQueryLog());
            
            $data['result'] = $result;

            $data['response'] = [];

            foreach ($global_compare_list as $key => $value) {
                array_push($data['response'], (array) $value);
            }
            // dd($all_colum_list);
            // foreach ($all_colum_list as $k1 => $v1) {
            //     $colum_detail = [];
            //     foreach ($global_compare_list as $k2 => $v2) {
            //         if($v2['id'] == $v1){
            //             $colum_detail = $v2;
            //         }
            //     }
            //     array_push($data['response'], $colum_detail);
            // }
            if (Auth::check()){
                // dd($data);
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $data['amfi_registered'] = $displayInfo->amfi_registered;
                $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $data['watermark'] = 0;
                }else{
                    $data['watermark'] = 1;
                }
            }else{
                $data['name'] = "";
                $data['company_name'] = "";
                $data['amfi_registered'] = "";
                $data['phone_no'] = "";
                $data['website'] = "";
                $data['company_logo'] = "";
                $data['watermark'] = "";
                $data['email'] = "";
            }
            if($download_type == "Portrait"){
                $pdf = PDF::loadView('frontend.mf_scanner.pdf', $data);
            }else{
                // return view('frontend.mf_scanner.landscape_pdf',$data);
                $pdf = PDF::loadView('frontend.mf_scanner.landscape_pdf', $data)->setPaper('a4', 'landscape');
            }
            
            return $pdf->download('MF Screener.pdf');
        }else if($page_type == "CSV"){
            if(!Auth::check()){
                return redirect('login');
            }
            
            $input = session()->get('ms_mf_scenner_data');
            $global_compare_list = json_decode($all_colum_list);
            
            $all_colum_list = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else{
                $order_by = "total";
                $ordering = $randhir2[1];
                $order_index = $randhir2[0] - 2;
                $order_by = $global_compare_list[$order_index]->key_name;
            }
            
            $data = [];
            $result = DB::table("mf_scanner")->select(['mf_scanner.*','mf_scanner_classcode.name as short_name','mf_ratting.rate as rating'])
                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                    ->LeftJoin('mf_ratting', 'mf_ratting.schemecode', '=', 'mf_scanner.schemecode');

            if(isset($input['ael']) || isset($input['adl']) || isset($input['ahl']) || isset($input['aol'])){
                $classcode = $input['ael'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['adl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['ahl'];
                if($classcode){ $classcode .= ","; }
                $classcode .= $input['aol'];

                $classcodeArr = explode(',', $classcode);
                $result = $result->whereIn('mf_scanner.classcode', $classcodeArr);
            }else{
                $result = $result->where('mf_scanner.classcode','=', 0);
            }

            if(isset($input['fhl'])){
                $result = $result->whereIn('mf_scanner.amc_code', $input['fhl']);
            }else{
                $result = $result->where('mf_scanner.amc_code','=', 0);
            }

            if(isset($input['fml'])){
                $result = $result->whereIn('mf_scanner.fund_mgr_code1', $input['fml']);
            }else{
                $result = $result->where('mf_scanner.fund_mgr_code1','=', 0);
            }
            if(isset($input['pbl'])){
                $result = $result->whereIn('mf_scanner.INDEXCODE', $input['pbl']);
            }else{
                $result = $result->where('mf_scanner.INDEXCODE','=', 0);
            }
            if(isset($input['ftl'])){
                $result = $result->whereIn('mf_scanner.type_code', $input['ftl']);
            }else{
                $result = $result->where('mf_scanner.type_code','=', 0);
            }
            if(isset($input['ol'])){
                $result = $result->whereIn('mf_scanner.opt_code', $input['ol']);
            }else{
                $result = $result->where('mf_scanner.opt_code','=', 0);
            }
            if(isset($input['pl'])){
                $result = $result->whereIn('mf_scanner.plan', $input['pl']);
            }else{
                $result = $result->where('mf_scanner.plan','=', 0);
            }
            if(isset($input['amurange'])){
                $amurange = $input['amurange'];
                $result = $result->where(function($query) use ($amurange){
                    foreach ($amurange as $key => $value) {
                        if($value == 1){
                            $min_price = 0;
                            $max_price = 50000;
                        }else if($value == 2){
                            $min_price = 50000;
                            $max_price = 75000;
                        }else if($value == 3){
                            $min_price = 75000;
                            $max_price = 200000;                
                        }else if($value == 4){
                            $min_price = 200000;
                            $max_price = 500000;
                        }else if($value == 5){
                            $min_price = 500000;
                            $max_price = 1000000;
                        }else if($value == 6){
                            $min_price = 1000000;
                            $max_price = 5000000;
                        }else if($value == 7){
                            $min_price = 5000000;
                            $max_price = 9999999900;
                        }
                        $query->orWhereBetween('mf_scanner.total', [$min_price, $max_price]);
                    }
                });
            }

            if(isset($input['rating'])){
                $rating = $input['rating'];
                $rating_flag = false;
                foreach ($rating as $key => $value) {
                    if($value == 0){
                        $rating_flag = true;
                    }
                }
                if($rating_flag){
                    $result = $result->where(function ($query) use ($rating) {
                        $query->whereIn('mf_ratting.rate',$rating)->orWhereNull('rate');                  
                    });
                }else{
                    $result = $result->whereIn('mf_ratting.rate', $input['rating']);
                }
            }

            if($schemecode_id){
                $schemecode_ids = explode(",", $schemecode_id);
                $result = $result->whereIn('mf_scanner.schemecode',$schemecode_ids);
            }
            
            $result = $result->orderBy($order_by, $ordering)->groupBy('mf_scanner.schemecode')->get();
            
            
            $data['result'] = $result;

            $data['response'] = [];

            foreach ($global_compare_list as $key => $value) {
                array_push($data['response'], (array) $value);
            }
            
            $filename = "mf-screener";
            $handle = fopen("./storage/app/".$filename, 'w');
            $insertData = [];
            $insertData[] = "SN";
            $insertData[] = "Fund";
            foreach($data['response'] as $value){
                if($value['table_checkbox'] == 1){
                    $insertData[] = $value['name'];
                }
            }
            fputcsv($handle, $insertData);

            foreach($result as $key=>$value) {
                $res = (array) $value;
                $insertData = [];
                $insertData[] = $key+1;
                $insertData[] = $value->s_name;
                foreach ($data['response'] as $key1 => $val) {
                    if($val['table_checkbox'] == 1){
                        if($val['key_name'] == "rating"){
                            if($res[$val['key_name']] == null){
                                $insertData[] = "Unrated";
                            }else{
                                $insertData[] = $res[$val['key_name']];
                            }
                        } else if($res[$val['key_name']] == "0" || !$res[$val['key_name']]){
                            $insertData[] = "-";
                        } else if($val['key_name'] == "total"){
                            $insertData[] = custome_money_format((int) ($res[$val['key_name']] /100));
                        } else if($val['key_name'] == "Incept_date"){
                            $insertData[] = date('d-m-Y', strtotime($res[$val['key_name']]));
                        } else if($val['key_name'] == "classname"){
                            if($res[$val['key_name']]){
                                $insertData[] = $res[$val['key_name']];
                            }else{
                                $insertData[] = $res['class_name'];
                            }
                        }else if($val['key_name'] == "IndexName"){
                            $insertData[] = $res[$val['key_name']];
                        } else if($val['key_name'] == "MCAP"){
                            $insertData[] = custome_money_format((int) $res[$val['key_name']] /100);
                        } else if($val['key_name'] == "ASECT_CODE" || $val['key_name'] == "short_name"){
                            $insertData[] = $res[$val['key_name']];
                        } else if($val['key_name'] == "highest_sector_all"){
                            $insertData[] = ucfirst(strtolower($res[$val['key_name']]));
                        } else if($val['key_name'] == "avg_mat_num"){
                            $insertData[] = $res[$val['key_name']]." ".$res['avg_mat_days'];
                        } else if($val['key_name'] == "mod_dur_num"){
                            $insertData[] = $res[$val['key_name']]." ".$res['mod_dur_days'];
                        } else if($val['key_name'] == "turnover_ratio"){
                            if($res['tr_mode'] == "times"){
                                $insertData[] = custome_money_format((int) ($res['turnover_ratio'] * 100));
                            }else{
                                $insertData[] = $res[$val['key_name']];
                            }
                        } else {
                            $insertData[] = number_format((float) $res[$val['key_name']], 2, '.', '');
                        }
                    }
                }
                fputcsv($handle, $insertData);
            }
            
            $mfresearch_note = Mfresearch_note::where('type',"mf-screener")->first();
            $description = "";
            if($mfresearch_note){
                $description = $mfresearch_note->description;
                $description = strip_tags($description);
            }
            $insertData = [];
            $insertData[] = "";
            $insertData[] = "";
            foreach($data['response'] as $value){
                if($value['table_checkbox'] == 1){
                    $insertData[] = "";
                }
            }
            fputcsv($handle, $insertData);
            fputcsv($handle, $insertData);
            $insertData[1] = $description;
            fputcsv($handle, $insertData);
            $insertData[1] = "Report Date";
            $insertData[2] = date('d/m/Y');
            fputcsv($handle, $insertData);

            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }else if($page_type == "SAVE"){
            // dd($global_all_filed);
            if(!Auth::check()){
                return redirect('login');
            }
            
            $expry = Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
            if(empty($expry)){
                return redirect('membership');
            }
            
            $global_compare_list = json_decode($all_colum_list);
            
            $all_colum_list = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else{
                // dd($randhir2);
                $order_by = "total";
                $ordering = $randhir2[1];
                $order_index = $randhir2[0] - 2;
                $order_by = $global_compare_list[$order_index]->key_name;
            }

            $mf_scenner_data = session()->get('ms_mf_scenner_data');
            $mf_scenner_data['schemecode_id'] = $schemecode_id;
            $mf_scenner_data['order_by'] = $order_by;
            $mf_scenner_data['ordering'] = $ordering;
            $mf_scenner_data['shorting_id'] = $request->shorting_id;
            
            $mf_scenner_data['response'] = [];
            foreach ($global_compare_list as $key => $value) {
                array_push($mf_scenner_data['response'], (array) $value);
            }

            // dd($mf_scenner_data);

            $insertData = [];
            $insertData['user_id'] = Auth::user()->id;
            $insertData['type'] = 1;
            $insertData['mf_researche_id'] = 1;
            $insertData['name'] = $save_title;
            $insertData['data'] = serialize($mf_scenner_data);
            DB::table("mf_scanner_saved")->insert($insertData);
            return redirect()->route('frontend.scanner_saved_files');
        }else if($page_type == "UPDATE"){

            $basic_detail_list = [
                ['id'=>'1','name'=>'AUM (Rs Cr)','is_checked'=>1,"key_name"=>'total'],
                ['id'=>'2','name'=>'NAV','is_checked'=>1,"key_name"=>'navrs'],
                ['id'=>'3','name'=>'Expense Ratio (%)','is_checked'=>1,"key_name"=>'expratio'],
                ['id'=>'4','name'=>'Exit Load (%)','is_checked'=>0,"key_name"=>'EXITLOAD'],
                ['id'=>'5','name'=>'Inception Date','is_checked'=>0,"key_name"=>'Incept_date'],
                ['id'=>'6','name'=>'Benchmark','is_checked'=>0,"key_name"=>'INDEXCODE']
            ];

            // ["id"=>"10","name"=>"10 Year Return (%)"],
            $retuen_list = [
                ["id"=>"1","name"=>"1 Day Return (%)","is_checked"=>0,"key_name"=>'1dayret'],
                ["id"=>"2","name"=>"7 Day Return (%)","is_checked"=>0,"key_name"=>'1weekret'],
                ["id"=>"3","name"=>"1 Month Return (%)","is_checked"=>0,"key_name"=>'1monthret'],
                ["id"=>"4","name"=>"3 Month Return (%)","is_checked"=>0,"key_name"=>'3monthret'],
                ["id"=>"5","name"=>"6 Month Return (%)","is_checked"=>0,"key_name"=>'6monthret'],
                ["id"=>"6","name"=>"1 Year Return (%)","is_checked"=>1,"key_name"=>'1yrret'],
                ["id"=>"7","name"=>"2 Year Return (%)","is_checked"=>0,"key_name"=>'2yearret'],
                ["id"=>"8","name"=>"3 Year Return (%)","is_checked"=>1,"key_name"=>'3yearret'],
                ["id"=>"9","name"=>"5 Year Return (%)","is_checked"=>1,"key_name"=>'5yearret'],
                ["id"=>"11","name"=>"Since Inception Return (%)","is_checked"=>0,"key_name"=>'incret']
            ];

            $portfolio_attribute_list = [
                ["id"=>"1","name"=>"Turnover Ratio (%)","is_checked"=>0,"key_name"=>'turnover_ratio'],
                ["id"=>"2","name"=>"No. of Stocks","is_checked"=>0,"key_name"=>'ASECT_CODE'],
                ["id"=>"3","name"=>"Avg M-Cap (Rs Cr)","is_checked"=>0,"key_name"=>'MCAP'],
                ["id"=>"4","name"=>"PE Ratio","is_checked"=>0,"key_name"=>'PE'],
                ["id"=>"5","name"=>"PB Ratio","is_checked"=>0,"key_name"=>'PB'],
                ["id"=>"6","name"=>"Dividend Yield","is_checked"=>0,"key_name"=>'Div_Yield'],
                ["id"=>"7","name"=>"Large Cap (%)","is_checked"=>0,"key_name"=>'large_cap'],
                ["id"=>"8","name"=>"Mid Cap (%)","is_checked"=>0,"key_name"=>'mid_cap'],
                ["id"=>"9","name"=>"Small Cap (%)","is_checked"=>0,"key_name"=>'small_cap'],
                ["id"=>"10","name"=>"Highest Sector Allocation","is_checked"=>0,"key_name"=>'highest_sector_all'],
                ["id"=>"11","name"=>"Highest Sector Allocation %","is_checked"=>0,"key_name"=>'highest_sector_all_per'],
                ["id"=>"12","name"=>"Average Maturity (Yrs)","is_checked"=>0,"key_name"=>'avg_mat_num'],
                ["id"=>"13","name"=>"Modified Duration (Yrs)","is_checked"=>0,"key_name"=>'mod_dur_num'],
                ["id"=>"14","name"=>"YTM (%)","is_checked"=>0,"key_name"=>'ytm']
            ];

            $mf_ratios_list = [
                ["id"=>"1","name"=>"Alpha","is_checked"=>0,"key_name"=>'alpha'],
                ["id"=>"2","name"=>"Sharpe","is_checked"=>0,"key_name"=>'sharpe'],
                ["id"=>"3","name"=>"Treynor","is_checked"=>0,"key_name"=>'treynor'],
                ["id"=>"4","name"=>"Sortino","is_checked"=>0,"key_name"=>'sortino'],
                ["id"=>"5","name"=>"Beta","is_checked"=>0,"key_name"=>'beta'],
                ["id"=>"6","name"=>"Standard Deviation","is_checked"=>0,"key_name"=>'sd'],
                ["id"=>"7","name"=>"Tracking Error","is_checked"=>0,"key_name"=>'trackingError']
            ];
            $randhir1 = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else if($randhir2[0] == 2){
                $order_by = "short_name";
            }else{
                $randhir3 = $randhir1[$randhir2[0]-3];
                // dd($randhir1);
                $randhir3 = explode('_',$randhir3);
                $order_by = "total";
                $ordering = $randhir2[1];
                if($randhir3[1] == "BD"){
                    $order_by = $basic_detail_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "R"){
                    $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "PA"){
                    $order_by = $portfolio_attribute_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "MFR"){
                    $order_by = $mf_ratios_list[$randhir3[0] - 1]['key_name'];
                }
            }
            $mf_scenner_data = session()->get('ms_mf_scenner_data');
            $mf_scenner_data['schemecode_id'] = $schemecode_id;
            $mf_scenner_data['cbd'] = [];
            $mf_scenner_data['cr'] = [];
            $mf_scenner_data['cpa'] = [];
            $mf_scenner_data['cmfr'] = [];
            $mf_scenner_data['cbdkey'] = [];
            $mf_scenner_data['crkey'] = [];
            $mf_scenner_data['cpakey'] = [];
            $mf_scenner_data['cmfrkey'] = [];
            $mf_scenner_data['order_by'] = $order_by;
            $mf_scenner_data['ordering'] = $ordering;
            $mf_scenner_data['shorting_id'] = $request->shorting_id;
            $all_colum_list = explode(',', $all_colum_list);
            // dd($all_colum_list);
            foreach ($all_colum_list as $key => $value) {
                $all_colum = explode('_', $value);
                if($all_colum[1] == "BD"){
                    $val1 = $basic_detail_list[$all_colum[0]-1];
                    array_push($mf_scenner_data['cbd'], $val1);
                    array_push($mf_scenner_data['cbdkey'], $val1['key_name']);
                }else if($all_colum[1] == "R"){
                    $val1 = $retuen_list[$all_colum[0]-1];
                    array_push($mf_scenner_data['cbd'], $val1);
                    array_push($mf_scenner_data['crkey'], $val1['key_name']);
                }else if($all_colum[1] == "PA"){
                    $val1 = $portfolio_attribute_list[$all_colum[0]-1];
                    array_push($mf_scenner_data['cbd'], $val1);
                    array_push($mf_scenner_data['cpakey'], $val1['key_name']);
                }else if($all_colum[1] == "MFR"){
                    $val1 = $mf_ratios_list[$all_colum[0]-1];
                    array_push($mf_scenner_data['cbd'], $val1);
                    array_push($mf_scenner_data['cmfrkey'], $val1['key_name']);
                }
            }
            // dd($mf_scenner_data);
            $insertData = [];
            $insertData['user_id'] = Auth::user()->id;
            $insertData['type'] = 1;
            $insertData['mf_researche_id'] = 1;
            $insertData['data'] = serialize($mf_scenner_data);
            DB::table("mf_scanner_saved")->where('id',$input['save_file_id'])->update($insertData);
            return redirect()->route('frontend.UpdateMFScanner',$input['save_file_id']);
        }else{
            return redirect()->route('frontend.MFScanner');
        }
    }

    public function mf_page_one(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_scanner.mf_page_one',$data);
    }

    public function mf_page_two(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_scanner.mf_page_two',$data);
    }

    public function mf_page_three(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_scanner.mf_page_three',$data);
    }

    public function mf_page_four(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_scanner.mf_page_four',$data);
    }

}
