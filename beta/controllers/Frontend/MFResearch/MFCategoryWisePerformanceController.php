<?php

namespace App\Http\Controllers\Frontend\MFResearch;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Membership;
use App\Models\Displayinfo;
use App\Models\Mfresearch_note;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Response;

class MFCategoryWisePerformanceController extends Controller
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
        $data['plan_list'] = DB::table("accord_plan_mst")->select(['accord_plan_mst.plan_code','accord_plan_mst.plan','mf_scanner_plan.name as planname'])->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')->where('accord_plan_mst.status',1)->get();

        // ["id"=>"10","name"=>"10 Year Return (%)"],
        $data['retuen_list'] = [
            ["id"=>"1","name"=>"1 Day","is_checked"=>1,"key_name"=>'1dayret'],
            ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
            ["id"=>"3","name"=>"1 Month","is_checked"=>1,"key_name"=>'1monthret'],
            ["id"=>"4","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret'],
            ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
            ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
            ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
            ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
            ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
            ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret']
        ];
        
        $data['activemenu'] = 'mf-category-wise-performance';

        $data['details'] = $mf_researches = DB::table("mf_researches")->where("url","mf-category-wise-performance")->first();
        $user_id = 0;
        if(Auth::user()){
            $mf_researches = $data['details'];
            $package_id = Auth::user()->package_id;
            $user_id = Auth::user()->id;
            $permission = DB::table("mf_research_permissions")->where("mf_research_id",$mf_researches->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                    "is_view"=>$permission->is_view,
                    "is_download"=>$permission->is_download,
                    "is_save"=>$permission->is_save,
                    "is_cover"=>$permission->is_cover,
                    "is_csv"=>$permission->is_csv
                ];
            }else{
                $data['permission'] = [
                    "is_view"=>0,
                    "is_download"=>0,
                    "is_save"=>0,
                    "is_cover"=>0,
                    "is_csv"=>0
                ];
            }
        }else{
            $data['permission'] = [
                "is_view"=>1,
                "is_download"=>0,
                "is_save"=>0,
                "is_cover"=>0,
                "is_csv"=>0
            ];
        }

        $ip_address = $this->getIp();
    
        History::create([
            'list_count' => 1,
            'user_id' => $user_id,
            'page_type' => "MF Research",
            'page_id' => 6,
            'ip' => $ip_address
        ]);
        return view('frontend.mf_research.mf_category_wise_performance.index',$data);
    }

    public function list(Request $request){
        $input = $request->all();
        // dd($input);

        $result = DB::table("mf_scanner")
                    ->select(['mf_scanner.schemecode','mf_scanner.s_name','mf_scanner.classcode','mf_scanner.plan','mf_scanner.1dayret','mf_scanner.1weekret','mf_scanner.1monthret','mf_scanner.3monthret','mf_scanner.6monthret','mf_scanner.1yrret','mf_scanner.2yearret','mf_scanner.3yearret','mf_scanner.5yearret','mf_scanner.10yret']);

        $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

        if(isset($input['plan']) || $input['plan']){
            $plan_code = explode(',', $input['plan']);
            $result = $result->whereIn('mf_scanner.plan', $plan_code);
        }

        $result = $result->where('mf_scanner.status',1)->get();
        $schemecode_ids = [];
        $schemecode_array = "";
        foreach ($result as $k1 => $v1) {
            array_push($schemecode_ids, $v1->schemecode);
            if(!$schemecode_array){
                $schemecode_array .= "(".$v1->schemecode;
            }else{
                $schemecode_array .= ",".$v1->schemecode;
            }
            $v1 = (array) $v1;
            if($input['calender']){
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
            $result[$k1] = $v1;
        }
        $schemecode_array .= ")";
        
        $point_to_point = [];

        if(isset($input['point_to_point'])){
            // dd($schemecode_ids);
            // $schemecode_ids = [8250];
            foreach ($input['point_to_point'] as $k2 => $v2) {
                $start_dates = explode("/",$v2['start_date']);
                $start_date = $start_dates[2]."-".$start_dates[1]."-".$start_dates[0];
                $start_date = date('Y-m-d', strtotime($start_date));
                
                $end_dates = explode("/",$v2['end_date']);
                $end_date = $end_dates[2]."-".$end_dates[1]."-".$end_dates[0];
                $end_date = date('Y-m-d', strtotime($end_date));
                
                $start_date2 = date('Y-m-d', strtotime('-5 days',strtotime($start_date)));
                // $start_date = date('Y-m-d', strtotime('-1 days',strtotime($start_date)));
                
                $end_date2 = date('Y-m-d', strtotime('-5 days',strtotime($end_date)));
                $start_date1 = strtotime($start_date);
                $end_date1 = strtotime($end_date);
                $datediff = $end_date1 - $start_date1;
                
                $total_days = round($datediff / (60 * 60 * 24));
                // echo $schemecode_array;
                // $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` < ".$start_date." AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` <= '".$start_date."' AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                
                $start_accord_mf_portfolio_array = [];
                foreach ($accord_mf_portfolio as $key => $value) {
                    $start_accord_mf_portfolio_array[$value->schemecode] = $value;
                }
                
                // $accord_mf_portfolio = DB::table("mf_navhist")->whereIn('schemecode',$schemecode_ids)->whereBetween('navdate',[$start_date2,$start_date])->orderBy('navdate','DESC')->get();
                
                // echo "<pre>";
                
                // dd($accord_mf_portfolio);
                
                // $start_accord_mf_portfolio_array = [];
                // foreach ($accord_mf_portfolio as $key => $value) {
                //     if(!isset($start_accord_mf_portfolio_array[$value->schemecode])){
                //         $start_accord_mf_portfolio_array[$value->schemecode] = $value;
                //     }
                // }
                
                // $accord_mf_portfolio = DB::table("mf_navhist")->whereIn('schemecode',$schemecode_ids)->whereBetween('navdate',[$end_date2,$end_date])->orderBy('navdate','DESC')->get();
                // dd($accord_mf_portfolio);
                // $end_accord_mf_portfolio_array = [];
                // foreach ($accord_mf_portfolio as $key => $value) {
                //     if(!isset($end_accord_mf_portfolio_array[$value->schemecode])){
                //         $end_accord_mf_portfolio_array[$value->schemecode] = $value;
                //     }
                // }
                
                $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` <= '".$end_date."' AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                // dd($accord_mf_portfolio);
                $end_accord_mf_portfolio_array = [];
                foreach ($accord_mf_portfolio as $key => $value) {
                    $end_accord_mf_portfolio_array[$value->schemecode] = $value;
                }
                
                if($total_days > 365){
                    foreach($start_accord_mf_portfolio_array as $key => $value){
                        if($value && isset($end_accord_mf_portfolio_array[$value->schemecode])){
                            $navs1 = (float) $value->navrs;
                            $navs2 = (float) $end_accord_mf_portfolio_array[$value->schemecode]->navrs;
                            $navs =pow((1+(($navs2-$navs1)/$navs1)),(365/$total_days))-1;
                            $navs = $navs*100;
                            $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = $navs;
                        }else{
                            $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = "";
                        }
                    } 
                }else{
                    foreach($start_accord_mf_portfolio_array as $key => $value){
                        if($value && isset($end_accord_mf_portfolio_array[$value->schemecode])){
                            $navs1 = (float) $value->navrs;
                            $navs2 = (float) $end_accord_mf_portfolio_array[$value->schemecode]->navrs;
                            $navs = ($navs2 - $navs1)/$navs1*100;
                            // echo "---".$value->schemecode."---".$navs1."---".$navs2."---".$navs."<br>";
                            $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = $navs;
                        }else{
                            $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = "";
                        }
                    } 
                }
                
                
                      
            }
            
            // dd($point_to_point);
            
            foreach($result as $key => $value){
                foreach ($input['point_to_point'] as $k2 => $v2) {
                    $value[$v2['start_date']."-".$v2['end_date']] = isset($point_to_point[$value['schemecode']][$v2['start_date']."-".$v2['end_date']])?$point_to_point[$value['schemecode']][$v2['start_date']."-".$v2['end_date']]:"";
                }
                
                $result[$key] = $value;
            }
            
            // dd($result);
        }

        session()->put('ms_mf_category_wise_performance_data',$input);
        // // 
        // $data['mf_scanner_avg'] = $result->get();
        // dd(DB::getQueryLog());
        return response()->json($result);
    }

    public function save(Request $request){
        $input = $request->all();
        // dd($input);
        $mf_scanner_saved_id = isset($input['mf_scanner_saved_id'])?$input['mf_scanner_saved_id']:'';
        $page_type = isset($input['page_type'])?$input['page_type']:'';
        $schemecode_id = isset($input['schemecode_id'])?$input['schemecode_id']:'';
        $save_title = isset($input['save_title'])?$input['save_title']:'';
        $all_colum_list = isset($input['all_colum_list'])?$input['all_colum_list']:'';
        $is_graph = isset($input['f_graph'])?$input['f_graph']:0;
        $nav_graph = isset($input['f_nav_graph'])?$input['f_nav_graph']:0;
        session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
        if($page_type == "DOWNLOAD"){
            if(!Auth::check()){
                return redirect('login');
            }

            $retuen_list = [
                ["id"=>"1","name"=>"1 Day","is_checked"=>0,"key_name"=>'1dayret'],
                ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
                ["id"=>"3","name"=>"1 Month","is_checked"=>0,"key_name"=>'1monthret'],
                ["id"=>"4","name"=>"3 Month","is_checked"=>0,"key_name"=>'3monthret'],
                ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
                ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
                ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
                ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
                ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
                ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret']
            ];
            
            $randhir1 = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $is_sorting = "";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else{
                $randhir3 = $randhir1[$randhir2[0]-2];
                // dd($randhir1);
                $randhir3 = explode('_',$randhir3);
                $order_by = "s_name";
                $ordering = $randhir2[1];
                if($randhir3[1] == "T"){
                    $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "C"){
                    $is_sorting = $randhir3[0];
                    // $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "P"){
                    $is_sorting = $randhir3[0];
                    // $order_by = $portfolio_attribute_list[$randhir3[0] - 1]['key_name'];
                }
            }
            // echo $order_by."-".$ordering."-".$is_sorting;
            // exit;
            $input = session()->get('ms_mf_category_wise_performance_data');
            // dd($input);
            $data = [];
            // DB::enableQueryLog();
            // $result = DB::table("mf_scanner");
            $result = DB::table("mf_scanner")
                    ->select(['mf_scanner.schemecode','mf_scanner.s_name','mf_scanner.classcode','mf_scanner.plan','mf_scanner.1dayret','mf_scanner.1weekret','mf_scanner.1monthret','mf_scanner.3monthret','mf_scanner.6monthret','mf_scanner.1yrret','mf_scanner.2yearret','mf_scanner.3yearret','mf_scanner.5yearret','mf_scanner.10yret']);

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }
            $data['schemecode_ids'] = [];
            if($schemecode_id){
                $data['schemecode_ids'] = explode(",", $schemecode_id);
                // $result = $result->whereIn('schemecode',$schemecode_ids);
            }

            $result = $result->orderBy($order_by, $ordering)->get();
            
            $resultData = [];

            $schemecode_ids = [];
            $schemecode_array = "";
            foreach ($result as $k1 => $v1) {
                array_push($schemecode_ids, $v1->schemecode);
                if(!$schemecode_array){
                    $schemecode_array .= "(".$v1->schemecode;
                }else{
                    $schemecode_array .= ",".$v1->schemecode;
                }
                $v1 = (array) $v1;
                if(isset($input['calender'])){
                    $calender = explode(",", $input['calender']);
                    // dd($calender);
                    foreach ($calender as $k2 => $v2) {
                        $category_wise_performance = DB::table("mf_category_wise_performance")->where('schemecode',$v1['schemecode'])->where('year',$v2)->first();
                        if($category_wise_performance){
                            $v1[$v2] = $category_wise_performance->aum;
                        }else{
                            $v1[$v2] = "";
                        }
                    }
                }
                $v1['is_sorting'] = $is_sorting;
                $result[$k1] = $v1;
                array_push($resultData, $v1);
            }
            
            $schemecode_array .= ")";
            
            $point_to_point = [];

            if(isset($input['point_to_point'])){
                    // dd($input['point_to_point']);
                // $schemecode_ids = [447];
                foreach ($input['point_to_point'] as $k2 => $v2) {
                    $start_dates = explode("/",$v2['start_date']);
                    $start_date = $start_dates[2]."-".$start_dates[1]."-".$start_dates[0];
                    $start_date = date('Y-m-d', strtotime($start_date));
                    
                    $end_dates = explode("/",$v2['end_date']);
                    $end_date = $end_dates[2]."-".$end_dates[1]."-".$end_dates[0];
                    $end_date = date('Y-m-d', strtotime($end_date));
                    
                    $start_date2 = date('Y-m-d', strtotime('-7 days',strtotime($start_date)));
                    $end_date2 = date('Y-m-d', strtotime('-7 days',strtotime($end_date)));
                    $start_date1 = strtotime($start_date);
                    $end_date1 = strtotime($end_date);
                    $datediff = $end_date1 - $start_date1;
                    
                    $total_days = round($datediff / (60 * 60 * 24));
                    
                    $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` < '".$start_date."' AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                
                    $start_accord_mf_portfolio_array = [];
                    foreach ($accord_mf_portfolio as $key => $value) {
                        $start_accord_mf_portfolio_array[$value->schemecode] = $value;
                    }
                    
                    $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` <= '".$end_date."' AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                    // dd($accord_mf_portfolio);
                    $end_accord_mf_portfolio_array = [];
                    foreach ($accord_mf_portfolio as $key => $value) {
                        $end_accord_mf_portfolio_array[$value->schemecode] = $value;
                    }
                    
                    if($total_days > 365){
                        foreach($start_accord_mf_portfolio_array as $key => $value){
                            if($value && isset($end_accord_mf_portfolio_array[$value->schemecode])){
                                $navs1 = (float) $value->navrs;
                                $navs2 = (float) $end_accord_mf_portfolio_array[$value->schemecode]->navrs;
                                $navs =pow((1+(($navs2-$navs1)/$navs1)),(365/$total_days))-1;
                                $navs = $navs*100;
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = $navs;
                            }else{
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = "";
                            }
                        } 
                    }else{
                        foreach($start_accord_mf_portfolio_array as $key => $value){
                            if($value && isset($end_accord_mf_portfolio_array[$value->schemecode])){
                                $navs1 = (float) $value->navrs;
                                $navs2 = (float) $end_accord_mf_portfolio_array[$value->schemecode]->navrs;
                                $navs = ($navs2 - $navs1)/$navs1*100;
                                // echo "---".$value->schemecode."---".$navs1."---".$navs2."---".$navs."<br>";
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = $navs;
                            }else{
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = "";
                            }
                        } 
                    }  
                }
                
                foreach($resultData as $key => $value){
                    foreach ($input['point_to_point'] as $k2 => $v2) {
                        $value[$v2['start_date']."-".$v2['end_date']] = isset($point_to_point[$value['schemecode']][$v2['start_date']."-".$v2['end_date']])?$point_to_point[$value['schemecode']][$v2['start_date']."-".$v2['end_date']]:"";
                    }
                    
                    $resultData[$key] = $value;
                }
            }
            
            
            if($is_sorting){
                // dd($is_sorting);
                if($ordering == "asc"){
                    usort($resultData,function($first,$second){
                        if($first['is_sorting'] == "2021"){
                            return $first["2021"] > $second["2021"];
                        }else if($first['is_sorting'] == "2020"){
                            return $first["2020"] > $second["2020"];
                        }else if($first['is_sorting'] == "2019"){
                            return $first["2019"] > $second["2019"];
                        }else if($first['is_sorting'] == "2018"){
                            return $first["2018"] > $second["2018"];
                        }else if($first['is_sorting'] == "2017"){
                            return $first["2017"] > $second["2017"];
                        }else if($first['is_sorting'] == "2016"){
                            return $first["2016"] > $second["2016"];
                        }else if($first['is_sorting'] == "2015"){
                            return $first["2015"] > $second["2015"];
                        }else if($first['is_sorting'] == "2014"){
                            return $first["2014"] > $second["2014"];
                        }else if($first['is_sorting'] == "2013"){
                            return $first["2013"] > $second["2013"];
                        }else if($first['is_sorting'] == "2012"){
                            return $first["2012"] > $second["2012"];
                        }
                    });
                }else{
                    usort($resultData,function($first,$second){
                        if($first['is_sorting'] == "2021"){
                            return $first["2021"] < $second["2021"];
                        }else if($first['is_sorting'] == "2020"){
                            return $first["2020"] < $second["2020"];
                        }else if($first['is_sorting'] == "2019"){
                            return $first["2019"] < $second["2019"];
                        }else if($first['is_sorting'] == "2018"){
                            return $first["2018"] < $second["2018"];
                        }else if($first['is_sorting'] == "2017"){
                            return $first["2017"] < $second["2017"];
                        }else if($first['is_sorting'] == "2016"){
                            return $first["2016"] < $second["2016"];
                        }else if($first['is_sorting'] == "2015"){
                            return $first["2015"] < $second["2015"];
                        }else if($first['is_sorting'] == "2014"){
                            return $first["2014"] < $second["2014"];
                        }else if($first['is_sorting'] == "2013"){
                            return $first["2013"] < $second["2013"];
                        }else if($first['is_sorting'] == "2012"){
                            return $first["2012"] < $second["2012"];
                        }
                    });
                }
                
            }
            
            $data['result'] = $resultData;
            $data['cr'] = [];
            $data['crkey'] = [];
            $data['yr'] = [];
            $data['yrkey'] = [];
            $all_colum_list = explode(',', $all_colum_list);

            foreach ($all_colum_list as $key => $value) {
                $all_colum = explode('_', $value);
                if($all_colum[1] == "T"){
                    $val1 = $retuen_list[$all_colum[0]-1];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($data['cr'], $val1);
                    array_push($data['crkey'], $val1['key_name']);
                }else if($all_colum[1] == "C"){
                    $val1 = ["name"=>$all_colum[0]];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($data['yr'], $val1);
                    array_push($data['yrkey'], $all_colum[0]);
                }else if($all_colum[1] == "P"){
                    $val1 = ["name"=>$all_colum[0]];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($data['yr'], $val1);
                    array_push($data['yrkey'], $all_colum[0]);
                }
            }
            
            $data['category_detail'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')->where('accord_sclass_mst.classcode','=', $input['category_id'])->first();                        
                        
            if (Auth::check()){
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $data['name_color'] = $displayInfo->name_color;
                $data['company_name_color'] = $displayInfo->company_name_color;
                $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
                $data['amfi_registered'] = $displayInfo->amfi_registered;
                $data['city_color'] = $displayInfo->city_color;
                $data['address_color_background'] = $displayInfo->address_color_background;
                $data['footer_branding_option'] = $displayInfo->footer_branding_option;
                $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $data['address'] = ($displayInfo->address_check && $displayInfo->address!='')?$displayInfo->address:'';

                if($data['address']){
                    $data['address2'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                    $data['address'] = $data['address']." ".$data['address2'];
                }else{
                    $data['address'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                }

                $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $data['watermark'] = 0;
                }else{
                    $data['watermark'] = 1;
                }
            }else{
                $data['name_color'] = "";
                $data['company_name_color'] = "";
                $data['name'] = "";
                $data['company_name'] = "";
                $data['amfi_registered'] = "";
                $data['phone_no'] = "";
                $data['website'] = "";
                $data['company_logo'] = "";
                $data['watermark'] = "";
                $data['email'] = "";
                $data['address'] = "";
                $data['email'] = "";
                $data['pdf_cover_image'] = "";
                $data['city_color'] = "";
                $data['address_color_background'] = "";
                $data['footer_branding_option'] = "";
            }

            $data['pdf_title_line1'] = $request->title_line1;
            $data['pdf_title_line2'] = $request->title_line2;
            $data['client_name'] = $request->client_name;
            $data['comments'] = $request->f_comments;
            $data['is_graph'] = $is_graph;
            $data['nav_graph'] = $nav_graph;

            $data['details'] = DB::table("mf_researches")->where("url","mf-category-wise-performance")->first();

            $data['pie_chart2'] = Session::get('pie_chart2');

            $user_id = Auth::user()->id;
            $ip_address = $this->getIp();
    
            History::create([
                'download_count' => 1,
                'user_id' => $user_id,
                'page_type' => "MF Research",
                'page_id' => 6,
                'ip' => $ip_address
            ]);

            if($data['client_name']){
                $oMerger = PDFMerger::init();
                $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                
                $pdf = PDF::loadView('frontend.mf_research.mf_category_wise_performance.pdf', $data);
                $pdf->save(public_path('calculators/'.$user->id.'_mf_research.pdf'));
                $oMerger->addPDF(public_path('calculators/'.$user->id.'_mf_research.pdf'), 'all');
                
                $oMerger->merge();
                $oMerger->setFileName($data['details']->name.'.pdf');
                return $oMerger->download();
            }else{
                $pdf = PDF::loadView('frontend.mf_research.mf_category_wise_performance.pdf', $data);
                return $pdf->download($data['details']->name.'.pdf');
            }

                
        }else if($page_type == "SAVE"){
            // dd($global_all_filed);
            $retuen_list = [
                ["id"=>"1","name"=>"1 Day","is_checked"=>0,"key_name"=>'1dayret'],
                ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
                ["id"=>"3","name"=>"1 Month","is_checked"=>0,"key_name"=>'1monthret'],
                ["id"=>"4","name"=>"3 Month","is_checked"=>0,"key_name"=>'3monthret'],
                ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
                ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
                ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
                ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
                ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
                ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret']
            ];

            $randhir1 = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else{
                $randhir3 = $randhir1[$randhir2[0]-2];
                // dd($randhir1);
                $randhir3 = explode('_',$randhir3);
                $order_by = "s_name";
                $ordering = $randhir2[1];
                if($randhir3[1] == "T"){
                    $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "C"){
                    // $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "P"){
                    // $order_by = $portfolio_attribute_list[$randhir3[0] - 1]['key_name'];
                }
            }

            $mf_scenner_data = session()->get('ms_mf_category_wise_performance_data');
            $mf_scenner_data['schemecode_id'] = $schemecode_id;
            $mf_scenner_data['cr'] = [];
            $mf_scenner_data['crkey'] = [];
            $mf_scenner_data['yr'] = [];
            $mf_scenner_data['yrkey'] = [];
            $mf_scenner_data['order_by'] = $order_by;
            $mf_scenner_data['pie_chart2'] = Session::get('pie_chart2');
            $mf_scenner_data['ordering'] = $ordering;
            $mf_scenner_data['is_graph'] = $is_graph;
            $mf_scenner_data['nav_graph'] = $nav_graph;

            $all_colum_list = explode(',', $all_colum_list);
            // dd($mf_scenner_data);

            foreach ($all_colum_list as $key => $value) {
                $all_colum = explode('_', $value);
                if($all_colum[1] == "T"){
                    $val1 = $retuen_list[$all_colum[0]-1];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($mf_scenner_data['cr'], $val1);
                    array_push($mf_scenner_data['crkey'], $val1['key_name']);
                }else if($all_colum[1] == "C"){
                    $val1 = ["name"=>$all_colum[0]];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($mf_scenner_data['yr'], $val1);
                    array_push($mf_scenner_data['yrkey'], $all_colum[0]);
                }else if($all_colum[1] == "P"){
                    $val1 = ["name"=>$all_colum[0]];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($mf_scenner_data['yr'], $val1);
                    array_push($mf_scenner_data['yrkey'], $all_colum[0]);
                }
            }
            $mf_scenner_data['comments'] = $request->f_comments;

            $insertData = [];
            $insertData['user_id'] = Auth::user()->id;
            $insertData['name'] = $save_title;
            $insertData['data'] = serialize($mf_scenner_data);
            
            $user_id = Auth::user()->id;
            $ip_address = $this->getIp();
    
            History::create([
                'save_count' => 1,
                'user_id' => $user_id,
                'page_type' => "MF Research",
                'page_id' => 6,
                'ip' => $ip_address
            ]);

            if($mf_scanner_saved_id){
                DB::table("mf_scanner_saved")->where("id",$mf_scanner_saved_id)->update($insertData);

                return redirect()->route('frontend.scanner_saved_files');
            }else{
                $insertData['mf_researche_id'] = 6;
                $insertData['type'] = 4;
                DB::table("mf_scanner_saved")->insert($insertData);
                return redirect()->route('frontend.scanner_saved_files');
            }
            
            
        }else if($page_type == "CSV"){
            if(!Auth::check()){
                return redirect('login');
            }

            $user_id = Auth::user()->id;
            $ip_address = $this->getIp();
    
            History::create([
                'download_count' => 1,
                'user_id' => $user_id,
                'page_type' => "MF Research",
                'page_id' => 6,
                'ip' => $ip_address
            ]);

            $retuen_list = [
                ["id"=>"1","name"=>"1 Day","is_checked"=>0,"key_name"=>'1dayret'],
                ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
                ["id"=>"3","name"=>"1 Month","is_checked"=>0,"key_name"=>'1monthret'],
                ["id"=>"4","name"=>"3 Month","is_checked"=>0,"key_name"=>'3monthret'],
                ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
                ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
                ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
                ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
                ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
                ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret']
            ];
            
            $randhir1 = explode(',',$all_colum_list);
            $randhir2 = explode('_',$request->shorting_id);
            $order_by = "s_name";
            $is_sorting = "";
            $ordering = $randhir2[1];
            if($randhir2[0] == 1){
                $order_by = "s_name";
            }else{
                $randhir3 = $randhir1[$randhir2[0]-2];
                // dd($randhir1);
                $randhir3 = explode('_',$randhir3);
                $order_by = "s_name";
                $ordering = $randhir2[1];
                if($randhir3[1] == "T"){
                    $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "C"){
                    $is_sorting = $randhir3[0];
                    // $order_by = $retuen_list[$randhir3[0] - 1]['key_name'];
                }else if($randhir3[1] == "P"){
                    $is_sorting = $randhir3[0];
                    // $order_by = $portfolio_attribute_list[$randhir3[0] - 1]['key_name'];
                }
            }
            // echo $order_by."-".$ordering."-".$is_sorting;
            // exit;
            $input = session()->get('ms_mf_category_wise_performance_data');
            // dd($input);
            $data = [];
            // DB::enableQueryLog();
            // $result = DB::table("mf_scanner");
            $result = DB::table("mf_scanner")
                    ->select(['mf_scanner.schemecode','mf_scanner.s_name','mf_scanner.classcode','mf_scanner.plan','mf_scanner.1dayret','mf_scanner.1weekret','mf_scanner.1monthret','mf_scanner.3monthret','mf_scanner.6monthret','mf_scanner.1yrret','mf_scanner.2yearret','mf_scanner.3yearret','mf_scanner.5yearret','mf_scanner.10yret']);

            $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

            if(isset($input['plan']) || $input['plan']){
                $plan_code = explode(',', $input['plan']);
                $result = $result->whereIn('mf_scanner.plan', $plan_code);
            }
            $data['schemecode_ids'] = [];
            if($schemecode_id){
                $data['schemecode_ids'] = explode(",", $schemecode_id);
                // $result = $result->whereIn('schemecode',$schemecode_ids);
            }

            $result = $result->orderBy($order_by, $ordering)->get();
            
            $resultData = [];

            $schemecode_array = "";
            $schemecode_ids = [];
            foreach ($result as $k1 => $v1) {
                if(!$schemecode_array){
                    $schemecode_array .= "(".$v1->schemecode;
                }else{
                    $schemecode_array .= ",".$v1->schemecode;
                }
                array_push($schemecode_ids, $v1->schemecode);
                $v1 = (array) $v1;
                if(isset($input['calender'])){
                    $calender = explode(",", $input['calender']);
                    // dd($calender);
                    foreach ($calender as $k2 => $v2) {
                        $category_wise_performance = DB::table("mf_category_wise_performance")->where('schemecode',$v1['schemecode'])->where('year',$v2)->first();
                        if($category_wise_performance){
                            $v1[$v2] = $category_wise_performance->aum;
                        }else{
                            $v1[$v2] = "";
                        }
                    }
                }
                $v1['is_sorting'] = $is_sorting;
                $result[$k1] = $v1;
                
                array_push($resultData, $v1);
            }
            $schemecode_array .= ")";
            
            
            
            $point_to_point = [];
            
            if(isset($input['point_to_point'])){
                foreach ($input['point_to_point'] as $k2 => $v2) {
                    
                    $start_dates = explode("/",$v2['start_date']);
                    $start_date = $start_dates[2]."-".$start_dates[1]."-".$start_dates[0];
                    $start_date = date('Y-m-d', strtotime($start_date));
                    
                    $end_dates = explode("/",$v2['end_date']);
                    $end_date = $end_dates[2]."-".$end_dates[1]."-".$end_dates[0];
                    $end_date = date('Y-m-d', strtotime($end_date));
                    
                    $start_date2 = date('Y-m-d', strtotime('-5 days',strtotime($start_date)));
                    // $start_date = date('Y-m-d', strtotime('-1 days',strtotime($start_date)));
                    
                    $end_date2 = date('Y-m-d', strtotime('-5 days',strtotime($end_date)));
                    $start_date1 = strtotime($start_date);
                    $end_date1 = strtotime($end_date);
                    $datediff = $end_date1 - $start_date1;
                    
                    $total_days = round($datediff / (60 * 60 * 24));
                    
                    $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` <= '".$start_date."' AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                    
                    $start_accord_mf_portfolio_array = [];
                    foreach ($accord_mf_portfolio as $key => $value) {
                        $start_accord_mf_portfolio_array[$value->schemecode] = $value;
                    }
                    
                    $accord_mf_portfolio = DB::select( DB::raw("SELECT * FROM mf_navhist AS t1 INNER JOIN ( SELECT `schemecode`, MAX(`navdate`) AS MaxDate FROM mf_navhist WHERE `navdate` <= '".$end_date."' AND `schemecode` IN ".$schemecode_array." GROUP BY `schemecode` ) AS t2 ON t1.`schemecode` = t2.`schemecode` AND t1.`navdate` = t2.MaxDate") );
                    
                    $end_accord_mf_portfolio_array = [];
                    foreach ($accord_mf_portfolio as $key => $value) {
                        $end_accord_mf_portfolio_array[$value->schemecode] = $value;
                    }
                    
                    if($total_days > 365){
                        foreach($start_accord_mf_portfolio_array as $key => $value){
                            if($value && isset($end_accord_mf_portfolio_array[$value->schemecode])){
                                $navs1 = (float) $value->navrs;
                                $navs2 = (float) $end_accord_mf_portfolio_array[$value->schemecode]->navrs;
                                $navs =pow((1+(($navs2-$navs1)/$navs1)),(365/$total_days))-1;
                                $navs = $navs*100;
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = $navs;
                            }else{
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = "";
                            }
                        } 
                    }else{
                        foreach($start_accord_mf_portfolio_array as $key => $value){
                            if($value && isset($end_accord_mf_portfolio_array[$value->schemecode])){
                                $navs1 = (float) $value->navrs;
                                $navs2 = (float) $end_accord_mf_portfolio_array[$value->schemecode]->navrs;
                                $navs = ($navs2 - $navs1)/$navs1*100;
                                // echo "---".$value->schemecode."---".$navs1."---".$navs2."---".$navs."<br>";
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = $navs;
                            }else{
                                $point_to_point[$value->schemecode][$v2['start_date']."-".$v2['end_date']] = "";
                            }
                        } 
                    }
                }
                
                foreach($result as $key => $value){
                    foreach ($input['point_to_point'] as $k2 => $v2) {
                        $value[$v2['start_date']."-".$v2['end_date']] = isset($point_to_point[$value['schemecode']][$v2['start_date']."-".$v2['end_date']])?$point_to_point[$value['schemecode']][$v2['start_date']."-".$v2['end_date']]:"";
                    }
                    
                    $resultData[$key] = $value;
                }
            }
            
            if($is_sorting){
                // dd($is_sorting);
                if($ordering == "asc"){
                    usort($resultData,function($first,$second){
                        if($first['is_sorting'] == "2021"){
                            return $first["2021"] > $second["2021"];
                        }else if($first['is_sorting'] == "2020"){
                            return $first["2020"] > $second["2020"];
                        }else if($first['is_sorting'] == "2019"){
                            return $first["2019"] > $second["2019"];
                        }else if($first['is_sorting'] == "2018"){
                            return $first["2018"] > $second["2018"];
                        }else if($first['is_sorting'] == "2017"){
                            return $first["2017"] > $second["2017"];
                        }else if($first['is_sorting'] == "2016"){
                            return $first["2016"] > $second["2016"];
                        }else if($first['is_sorting'] == "2015"){
                            return $first["2015"] > $second["2015"];
                        }else if($first['is_sorting'] == "2014"){
                            return $first["2014"] > $second["2014"];
                        }else if($first['is_sorting'] == "2013"){
                            return $first["2013"] > $second["2013"];
                        }else if($first['is_sorting'] == "2012"){
                            return $first["2012"] > $second["2012"];
                        }
                    });
                }else{
                    usort($resultData,function($first,$second){
                        if($first['is_sorting'] == "2021"){
                            return $first["2021"] < $second["2021"];
                        }else if($first['is_sorting'] == "2020"){
                            return $first["2020"] < $second["2020"];
                        }else if($first['is_sorting'] == "2019"){
                            return $first["2019"] < $second["2019"];
                        }else if($first['is_sorting'] == "2018"){
                            return $first["2018"] < $second["2018"];
                        }else if($first['is_sorting'] == "2017"){
                            return $first["2017"] < $second["2017"];
                        }else if($first['is_sorting'] == "2016"){
                            return $first["2016"] < $second["2016"];
                        }else if($first['is_sorting'] == "2015"){
                            return $first["2015"] < $second["2015"];
                        }else if($first['is_sorting'] == "2014"){
                            return $first["2014"] < $second["2014"];
                        }else if($first['is_sorting'] == "2013"){
                            return $first["2013"] < $second["2013"];
                        }else if($first['is_sorting'] == "2012"){
                            return $first["2012"] < $second["2012"];
                        }
                    });
                }
            }
            
            $data['result'] = $resultData;
            $data['cr'] = [];
            $data['crkey'] = [];
            $data['yr'] = [];
            $data['yrkey'] = [];
            $all_colum_list = explode(',', $all_colum_list);
            // dd($all_colum_list);
            foreach ($all_colum_list as $key => $value) {
                $all_colum = explode('_', $value);
                if($all_colum[1] == "T"){
                    $val1 = $retuen_list[$all_colum[0]-1];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($data['cr'], $val1);
                    array_push($data['crkey'], $val1['key_name']);
                }else if($all_colum[1] == "C"){
                    $val1 = ["name"=>$all_colum[0]];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($data['yr'], $val1);
                    array_push($data['yrkey'], $all_colum[0]);
                }else if($all_colum[1] == "P"){
                    $val1 = ["name"=>$all_colum[0]];
                    $val1['total'] = 0;
                    $val1['count'] = 0;
                    array_push($data['yr'], $val1);
                    array_push($data['yrkey'], $all_colum[0]);
                }
            }
            
            $data['category_detail'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')->where('accord_sclass_mst.classcode','=', $input['category_id'])->first();

            $data['details'] = DB::table("mf_researches")->where("url","mf-category-wise-performance")->first();

            $filename = $data['details']->name;
            $handle = fopen("./storage/app/".$filename, 'w');
            $insertData = [];
            $insertData[] = "SN";
            $insertData[] = "Scheme";
            foreach($data['cr'] as $value){
                $insertData[] = $value['name'];
            }
            foreach($data['yr'] as $value){
                $insertData[] = $value['name'];
            }
            fputcsv($handle, $insertData);

            foreach($data['result'] as $key=>$value) {
                $res = (array) $value;
                foreach($data['yrkey'] as $k1=>$val){
                    if($res[$val] == "0" || !$res[$val]){

                    }else{
                        $data['yr'][$k1]['total'] = $data['yr'][$k1]['total'] + $res[$val];
                        $data['yr'][$k1]['count'] = $data['yr'][$k1]['count'] + 1;
                    }
                }
                foreach($data['crkey'] as $k1=>$val){
                    if($res[$val] == "0" || !$res[$val]){

                    }else{
                        $data['cr'][$k1]['total'] = $data['cr'][$k1]['total'] + $res[$val];
                        $data['cr'][$k1]['count'] = $data['cr'][$k1]['count'] + 1;
                    }
                }
                if(in_array($value['schemecode'],$data['schemecode_ids']) || count($data['schemecode_ids']) == 0){
                    $insertData = [];
                    $insertData[] = $key+1;
                    $insertData[] = $res['s_name'];
                    foreach ($data['crkey'] as $key1 => $val) {
                        if($res[$val] == "0" || !$res[$val]){
                            $insertData[] = "-";
                        } else {
                            $insertData[] = number_format($res[$val], 2, '.', '');
                        }
                    }
                    foreach ($data['yrkey'] as $key1 => $val) {
                        if($res[$val] == "0" || !$res[$val]){
                            $insertData[] = "-";
                        } else {
                            $insertData[] = number_format($res[$val], 2, '.', '');
                        }
                    }
                    fputcsv($handle, $insertData);
                }
            }

            $insertData = [];
            $insertData[] = "Category Average";
            $insertData[] = "";
            foreach($data['cr'] as $value){
                if($value['total']){
                    $avg = $value['total'] / $value['count'];
                    $insertData[] = number_format($avg, 2, '.', '');
                }else{
                    $insertData[] = "-";
                }
            }
            foreach($data['yr'] as $value){
                if($value['total']){
                    $avg = $value['total'] / $value['count'];
                    $insertData[] = number_format($avg, 2, '.', '');
                }else{
                    $insertData[] = "-";
                }
            }
            fputcsv($handle, $insertData);

            $mfresearch_note = Mfresearch_note::where('type',"mf-category-wise-performance")->first();
            $description = "";
            if($mfresearch_note){
                $description = $mfresearch_note->description;
                $description = strip_tags($description);
            }
            $insertData = [];
            $insertData[] = "";
            $insertData[] = "";
            $insertData[] = "";
            foreach($data['cr'] as $value){
                $insertData[] = "";
            }
            foreach($data['yr'] as $value){
                $insertData[] = "";
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
            

        }else{
            return redirect()->route('frontend.mf_category_wise_performance');
        }
    }

    public function view(Request $request){
        $input = $request->all();
        $data = DB::table("mf_scanner_saved")->where("id","=",$input['id'])->first();
        $input = unserialize($data->data);
        $input['name'] = $data->name;
        $input['mf_scanner_saved_id'] = $data->id;

        $data = [];

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
        // $data['comments'] = $input['comments'];
        // dd($input);
        $input['details'] = DB::table("mf_researches")->where("url","mf-category-wise-performance")->first();
        if(Auth::user()){
            $mf_researches = $input['details'];
            $package_id = Auth::user()->package_id;
            $permission = DB::table("mf_research_permissions")->where("mf_research_id",$mf_researches->id)->where("package_id",$package_id)->first();
            if($permission){
                $input['permission'] = [
                    "is_view"=>$permission->is_view,
                    "is_download"=>$permission->is_download,
                    "is_save"=>$permission->is_save,
                    "is_cover"=>$permission->is_cover,
                    "is_csv"=>$permission->is_csv
                ];
            }else{
                $input['permission'] = [
                    "is_view"=>0,
                    "is_download"=>0,
                    "is_save"=>0,
                    "is_cover"=>0,
                    "is_csv"=>0
                ];
            }
        }else{
            $input['permission'] = [
                "is_view"=>1,
                "is_download"=>0,
                "is_save"=>0,
                "is_cover"=>0,
                "is_csv"=>0
            ];
        }
        return view('frontend.mf_research.mf_category_wise_performance.view',$input);
    }

    public function download(Request $request){
        $id = $request->id;
        $data = DB::table("mf_scanner_saved")->where("id","=",$id)->first();

        $input = unserialize($data->data);
        $input['name'] = $data->name;
        $input['mf_scanner_saved_id'] = $data->id;
        // dd($input);
        $data = [];
        $result = DB::table("mf_scanner")
                ->select(['mf_scanner.schemecode','mf_scanner.s_name','mf_scanner.classcode','mf_scanner.plan','mf_scanner.1dayret','mf_scanner.1weekret','mf_scanner.1monthret','mf_scanner.3monthret','mf_scanner.6monthret','mf_scanner.1yrret','mf_scanner.2yearret','mf_scanner.3yearret','mf_scanner.5yearret','mf_scanner.10yret']);

        $result = $result->where('mf_scanner.classcode','=', $input['category_id']);

        if(isset($input['plan']) || $input['plan']){
            $plan_code = explode(',', $input['plan']);
            $result = $result->whereIn('mf_scanner.plan', $plan_code);
        }
        $data['schemecode_ids'] = [];
        if($input['schemecode_id']){
            $data['schemecode_ids'] = explode(",", $input['schemecode_id']);
            $result = $result->whereIn('schemecode',$data['schemecode_ids']);
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
        
        $data['result'] = $result;
        $data['comments'] = $input['comments'];
        $data['is_graph'] = $input['is_graph'];
        $data['pie_chart2'] = $input['pie_chart2'];
        $data['crkey'] = $input['crkey'];
        $data['cr'] = $input['cr'];
        $data['yrkey'] = $input['yrkey'];
        $data['yr'] = $input['yr'];
        $data['name'] = $input['name'];
        $data['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];
        $data['pdf_title_line1'] = $request->pdf_title_line1;
        $data['pdf_title_line2'] = $request->pdf_title_line2;
        $data['client_name'] = $request->client_name;
        
        $data['category_detail'] = DB::table("accord_sclass_mst")
                    ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')->where('accord_sclass_mst.classcode','=', $input['category_id'])->first();
                    
        if (Auth::check()){
            $user = Auth::user();
            $displayInfo = Displayinfo::where('user_id',$user->id)->first();
            $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
            $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
            $data['name_color'] = $displayInfo->name_color;
            $data['company_name_color'] = $displayInfo->company_name_color;
            $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
            $data['amfi_registered'] = $displayInfo->amfi_registered;
            $data['city_color'] = $displayInfo->city_color;
            $data['address_color_background'] = $displayInfo->address_color_background;
            $data['footer_branding_option'] = $displayInfo->footer_branding_option;
            $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
            $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
            $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
            $data['address'] = ($displayInfo->address_check && $displayInfo->address!='')?$displayInfo->address:'';

            if($data['address']){
                $data['address2'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                $data['address'] = $data['address']." ".$data['address2'];
            }else{
                $data['address'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
            }

            $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
            $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
            if (isset($membership) && $membership > 0){
                $data['watermark'] = 0;
            }else{
                $data['watermark'] = 1;
            }
        }else{
            $data['name_color'] = "";
            $data['company_name_color'] = "";
            $data['name'] = "";
            $data['company_name'] = "";
            $data['amfi_registered'] = "";
            $data['phone_no'] = "";
            $data['website'] = "";
            $data['company_logo'] = "";
            $data['watermark'] = "";
            $data['email'] = "";
            $data['address'] = "";
            $data['email'] = "";
            $data['pdf_cover_image'] = "";
            $data['city_color'] = "";
            $data['address_color_background'] = "";
            $data['footer_branding_option'] = "";
        }

        $data['details'] = DB::table("mf_researches")->where("url","mf-category-wise-performance")->first();
    
            
        $user_id = Auth::user()->id;
        $ip_address = $this->getIp();

        History::create([
            'download_count' => 1,
            'user_id' => $user_id,
            'page_type' => "MF Research",
            'page_id' => 6,
            'ip' => $ip_address
        ]);

        if($data['pdf_title_line1']){
            $oMerger = PDFMerger::init();
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
            
            $pdf = PDF::loadView('frontend.mf_research.mf_category_wise_performance.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_mf_research.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_mf_research.pdf'), 'all');
            
            $oMerger->merge();
            $oMerger->setFileName($data['details']->name.'.pdf');
            return $oMerger->download();
        }else{
            $pdf = PDF::loadView('frontend.mf_research.mf_category_wise_performance.pdf', $data);
            return $pdf->download($data['details']->name.'.pdf');
        }

    }

    public function edit(Request $request){
        $id = $request->id;
        $data = DB::table("mf_scanner_saved")->where("id","=",$id)->first();

        $input = unserialize($data->data);
        $input['name'] = $data->name;
        $input['mf_scanner_saved_id'] = $data->id;

        $data = [];

        $data['schemecode_id'] = $input['schemecode_id'];
        $data['schemecode_ids'] = explode(',',$data['schemecode_id']);
        $data['comments'] = $input['comments'];
        $data['is_graph'] = $input['is_graph'];
        $data['nav_graph'] = $input['nav_graph'];
        $data['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];
        $data['name'] = $input['name'];

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
        $data['plan_list'] = DB::table("accord_plan_mst")->select(['accord_plan_mst.plan_code','accord_plan_mst.plan','mf_scanner_plan.name as planname'])->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')->where('accord_plan_mst.status',1)->get();

        // ["id"=>"10","name"=>"10 Year Return (%)"],
        $data['retuen_list'] = [
            ["id"=>"1","name"=>"1 Day","is_checked"=>1,"key_name"=>'1dayret'],
            ["id"=>"2","name"=>"7 Day","is_checked"=>0,"key_name"=>'1weekret'],
            ["id"=>"3","name"=>"1 Month","is_checked"=>1,"key_name"=>'1monthret'],
            ["id"=>"4","name"=>"3 Month","is_checked"=>1,"key_name"=>'3monthret'],
            ["id"=>"5","name"=>"6 Month","is_checked"=>0,"key_name"=>'6monthret'],
            ["id"=>"6","name"=>"1 Year","is_checked"=>1,"key_name"=>'1yrret'],
            ["id"=>"7","name"=>"2 Year","is_checked"=>0,"key_name"=>'2yearret'],
            ["id"=>"8","name"=>"3 Year","is_checked"=>1,"key_name"=>'3yearret'],
            ["id"=>"9","name"=>"5 Year","is_checked"=>1,"key_name"=>'5yearret'],
            ["id"=>"10","name"=>"10 Year","is_checked"=>0,"key_name"=>'10yret']
        ];

        $data['calender_list'] = [];

        for($i=date('Y')-1; $i >= date('Y')-10; $i--){
            array_push($data['calender_list'], ["year"=>$i,"is_checked"=>false]);
        }

        foreach ($data['debt_list'] as $key => $value) {
            if($value->classcode == $input['category_id']){
                $data['debt_list'][$key]->is_checked = true;
            }else{
                $data['debt_list'][$key]->is_checked = false;
            }
        }

        foreach ($data['equity_list'] as $key => $value) {
            if($value->classcode == $input['category_id']){
                $data['equity_list'][$key]->is_checked = true;
            }else{
                $data['equity_list'][$key]->is_checked = false;
            }
        }

        foreach ($data['hybrid_list'] as $key => $value) {
            if($value->classcode == $input['category_id']){
                $data['hybrid_list'][$key]->is_checked = true;
            }else{
                $data['hybrid_list'][$key]->is_checked = false;
            }
        }

        foreach ($data['other_list'] as $key => $value) {
            if($value->classcode == $input['category_id']){
                $data['other_list'][$key]->is_checked = true;
            }else{
                $data['other_list'][$key]->is_checked = false;
            }
        }

        foreach ($data['plan_list'] as $key => $value) {
            if($value->plan_code == $input['plan']){
                $data['plan_list'][$key]->is_checked = true;
            }else{
                $data['plan_list'][$key]->is_checked = false;
            }
        }

        foreach ($data['retuen_list'] as $key => $value) {
            if(in_array($value['key_name'], $input['crkey'])){
                $data['retuen_list'][$key]['is_checked'] = true;
            }else{
                $data['retuen_list'][$key]['is_checked'] = false;
            }
        }

        $data['point_to_point'] = isset($input['point_to_point'])?$input['point_to_point']:[];

        

        foreach ($data['calender_list'] as $key => $value) {
            if(in_array($value['year'], $input['yrkey'])){
                $data['calender_list'][$key]['is_checked'] = true;
            }else{
                $data['calender_list'][$key]['is_checked'] = false;
            }
        }
        
        $data['activemenu'] = 'mf-category-wise-performance';

        if(Auth::user()){
            $mf_researches = DB::table("mf_researches")->where("url","mf-category-wise-performance")->first();
            $package_id = Auth::user()->package_id;
            $permission = DB::table("mf_research_permissions")->where("mf_research_id",$mf_researches->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                    "is_view"=>$permission->is_view,
                    "is_download"=>$permission->is_download,
                    "is_save"=>$permission->is_save,
                    "is_cover"=>$permission->is_cover,
                    "is_csv"=>$permission->is_csv
                ];
            }else{
                $data['permission'] = [
                    "is_view"=>0,
                    "is_download"=>0,
                    "is_save"=>0,
                    "is_cover"=>0,
                    "is_csv"=>0
                ];
            }
        }else{
            $data['permission'] = [
                "is_view"=>1,
                "is_download"=>0,
                "is_save"=>0,
                "is_cover"=>0,
                "is_csv"=>0
            ];
        }
        // dd($data);
        return view('frontend.mf_research.mf_category_wise_performance.edit',$data);

    }

    public function scheme(Request $request){
        $checked_schemecode_ids = $request->checked_schemecode_ids;


        $mf_navhist = DB::table('mf_navhist')->whereIn('schemecode',$checked_schemecode_ids)->orderBy('schemecode', 'ASC')->orderBy('navdate', 'ASC')->get();
        $mf_scanner = DB::table('mf_scanner')->whereIn('schemecode',$checked_schemecode_ids)->orderBy('schemecode', 'ASC')->get();

        $scanner_names = [];

        foreach ($mf_scanner as $key => $value) {
            $scanner_names[$value->schemecode] = $value->s_name;
        }
        $resss = [];
        foreach ($mf_navhist as $key => $value) {
            $navdate = strtotime($value->navdate)."000";
            $navdate = (int) $navdate;
            $resss[$value->schemecode][] = [$navdate,(float) $value->navrs];
        }

        $response= [];
        foreach ($resss as $key => $value) {
            array_push($response, ["name"=>$scanner_names[$key],"data"=>$value]);
        }

        return response()->json($response);
        // return response()->json(["scanner_names"=>$scanner_names,"response"=>$response]);
    }
}
