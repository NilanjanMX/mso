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
use App\Models\History;
use Response;

class MFInvestmentPortfolioAnalysisController extends Controller
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
    

    public function investment_analysis (Request $request){
        $investment_scheme = $request->investment_scheme;
        $data = [];
        $data['schemecode'] = "";

        $data['activemenu'] = 'mf-investment-analysis';
        $input = $request->all();
        
        $data['flag'] = false;
        $data['result'] = [];
        $data['assert_type'] = [];
        $data['category_type'] = [];
        $data['fund_manager'] = [];
        $data['mf_scanner'] = [];
        $data['fund'] = [];
        $data['scheme'] = [];
        $data['scheme_result'] = [];
        $data['equity_top_holding_list'] = [];
        $data['number'] = $request->number;
        $data['investment_scheme'] = $request->investment_scheme;
        $data['action_type'] = $request->action_type;
        $data['title'] = $request->save_title;
        $data['categorywise_scheme'] = [];
        $data['large_cap'] = 0;
        $data['mid_cap'] = 0;
        $data['small_cap'] = 0;
        $data['category_holding'] = $request->category_holding;
        $data['fund_holding_holding'] = $request->fund_holding_holding;
        $data['amc_holding'] = $request->amc_holding;
        $data['scheme_holding'] = $request->scheme_holding;
        $data['equity_allocation'] = $request->equity_allocation;
        $data['equity_top_holding'] = $request->equity_top_holding;
        $data['equity_top_sectors'] = $request->equity_top_sectors;
        $data['equity_classification'] = $request->equity_classification;
        $data['debt_allocation'] = $request->debt_allocation;
        $data['debt_top_holding'] = $request->debt_top_holding;
        $data['debt_top_sectors'] = $request->debt_top_sectors;
        $data['debt_credit_quality'] = $request->debt_credit_quality;
        $data['gold_allocation'] = $request->gold_allocation;
        $data['gold_top_holding'] = $request->gold_top_holding;
        $data['other_allocation'] = $request->other_allocation;
        $data['other_top_holding'] = $request->other_top_holding;
        $data['past_performance_scheme'] = $request->past_performance_scheme;
        
        $data['scheme_threemonth'] = $request->scheme_threemonth;
        $data['scheme_sixmonth'] = $request->scheme_sixmonth;
        $data['scheme_oneyear'] = $request->scheme_oneyear;
        $data['scheme_threeyear'] = $request->scheme_threeyear;
        $data['scheme_fiveyear'] = $request->scheme_fiveyear;
        $data['scheme_tenyear'] = $request->scheme_tenyear;
        
        // dd($data);
        if ($investment_scheme) {
            $schemecode_primary_ids = [];
            $data['flag'] = true;
            // dd($input);
            if($investment_scheme == 1){
                $schemecode_id = $request->schemecode_percent_id;
                $allocation = $request->allocation;
                $data['investment_price'] = $request->number;
                $allocations = [];
                foreach ($schemecode_id as $key => $value) {
                    $allocations[$value] = $allocation[$key];
                    $data['result'][] = ["schemecode_id"=>$value,"allocation"=>$allocation[$key]];
                }
            }else{
                $schemecode_id = $request->schemecode_amount_id;
                $allocation = $request->amount;
                $data['investment_price'] = 0;
                $allocations = [];
                foreach ($schemecode_id as $key => $value) {
                    $data['investment_price'] = $data['investment_price'] + (int) $allocation[$key];
                    $data['result'][] = ["schemecode_id"=>$value,"allocation"=>$allocation[$key]];
                }
                foreach ($schemecode_id as $key => $value) {
                    $allocations[$value] = ($allocation[$key] * 100) / ($data['investment_price']);
                }
            } 

            $data['allocations'] = $allocations;
            $data['mf_scanner'] = DB::table("mf_scanner")->select(['mf_scanner.*','asset_type','accord_amc_mst.fund','mf_scanner_classcode.name as class_name','accord_sclass_mst.classname as classname'])
                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_scanner.classcode')
                    ->LeftJoin('accord_amc_mst', 'accord_amc_mst.amc_code', '=', 'mf_scanner.amc_code')
                    ->whereIn('mf_scanner.schemecode', $schemecode_id)->get();

            // dd($data['mf_scanner']);
            
            $schemecode_ids = [];
            $same_primary_schemecode = [];

            foreach ($data['mf_scanner'] as $key => $value) {
                $classname = ($value->class_name)?$value->class_name:$value->classname;
                if(isset($data['assert_type'][$value->asset_type])){
                    $data['assert_type'][$value->asset_type] = $data['assert_type'][$value->asset_type] + $allocations[$value->schemecode];
                }else{
                    $data['assert_type'][$value->asset_type] = $allocations[$value->schemecode];
                }
                if(isset($data['category_type'][$classname])){
                    $data['category_type'][$classname] = $data['category_type'][$classname] + $allocations[$value->schemecode];
                }else{
                    $data['category_type'][$classname] = $allocations[$value->schemecode];
                }
                if(isset($data['fund_manager'][$value->fund_mgr1])){
                    $data['fund_manager'][$value->fund_mgr1] = $data['fund_manager'][$value->fund_mgr1] + $allocations[$value->schemecode];
                }else{
                    $data['fund_manager'][$value->fund_mgr1] = $allocations[$value->schemecode];
                }
                if(isset($data['fund'][$value->fund])){
                    $data['fund'][$value->fund] = $data['fund'][$value->fund] + $allocations[$value->schemecode];
                }else{
                    $data['fund'][$value->fund] = $allocations[$value->schemecode];
                }
                if(isset($data['scheme'][$value->s_name])){
                    $data['scheme'][$value->s_name] = $data['scheme'][$value->s_name] + $allocations[$value->schemecode];
                }else{
                    $data['scheme'][$value->s_name] = $allocations[$value->schemecode];
                }

                $categorywise_scheme = ["s_name"=>$value->s_name,"allocation"=>$allocations[$value->schemecode],"s_name"=>$value->s_name];
                if(isset($data['categorywise_scheme'][$value->classcode])){
                    $data['categorywise_scheme'][$value->classcode]['lists'][] = $categorywise_scheme;
                }else{
                    $data['categorywise_scheme'][$value->classcode]['detail'] = $classname;
                    $data['categorywise_scheme'][$value->classcode]["lists"][] = $categorywise_scheme;

                }
                $data['scheme_result'][$value->schemecode] = $value->s_name;
                $schemecode_primary_ids[$value->primary_fd_code] = $value->schemecode;
                if(isset($same_primary_schemecode[$value->primary_fd_code])){
                    $same_primary_schemecode[$value->primary_fd_code] = $same_primary_schemecode[$value->primary_fd_code] + 1;
                }else{
                    $same_primary_schemecode[$value->primary_fd_code] = 1;
                }
                
                array_push($schemecode_ids, $value->primary_fd_code);
                if($value->large_cap){
                    $data['large_cap'] = $data['large_cap'] + $value->large_cap*$allocations[$value->schemecode]/100;
                }
                if($value->mid_cap){
                    $data['mid_cap'] = $data['mid_cap'] + $value->mid_cap*$allocations[$value->schemecode]/100;
                }
                if($value->small_cap){
                    $data['small_cap'] = $data['small_cap'] + $value->small_cap*$allocations[$value->schemecode]/100;
                }

            }
            
            $last_date = DB::table('mf_portfolio_analysis')->whereIn('schemecode',$schemecode_ids)->orderBy('invdate','DESC')->first();
            // dd($same_primary_schemecode);
            $data['rating_one'] = 0;
            $data['rating_two'] = 0;
            $data['rating_three'] = 0;
            $data['rating_four'] = 0;
            $data['rating_five'] = 0;
            $data['date'] = "";
            if($last_date){
                $result1 = DB::table('accord_mf_portfolio')
                        ->select(['rattings.short_name','accord_mf_portfolio.rating','accord_mf_portfolio.holdpercentage','accord_mf_portfolio.schemecode'])
                        ->join('rattings', 'rattings.category_name', '=', 'accord_mf_portfolio.rating')
                        ->where(function ($query) {
                            $query->where('accord_mf_portfolio.asect_name', '=', "Certificate of Deposit")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Corporate Debt")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Government Securities")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Deposits")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Others")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Commercial Paper")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Deposits(Placed as Margin)")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Floating Rate Instrumrents")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "PTC & Securitized Debt")
                            ->orWhere('accord_mf_portfolio.asect_name', '=', "Treasury Bills");
                        })
                        ->whereIn('accord_mf_portfolio.schemecode',$schemecode_ids)->where('invdate','=',$last_date->invdate)
                        ->orderBy('accord_mf_portfolio.holdpercentage','DESC')->get();
                // dd($result1);
                foreach($result1 as $val){
                    if($val->short_name == "Sovereign"){
                        $holdpercentage = (float) $val->holdpercentage;
                        $schemecode_primary_id = $schemecode_primary_ids[$val->schemecode];
                        $same_primary_schemecode_id = $same_primary_schemecode[$val->schemecode];
                        $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                        $schemecode_value = $data['investment_price']*$schemecode_value/100;
                        $data['rating_one'] = $data['rating_one'] + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                    }else if($val->short_name == "AAA"){
                        $holdpercentage = (float) $val->holdpercentage;
                        $schemecode_primary_id = $schemecode_primary_ids[$val->schemecode];
                        $same_primary_schemecode_id = $same_primary_schemecode[$val->schemecode];
                        $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                        $schemecode_value = $data['investment_price']*$schemecode_value/100;
                        $data['rating_two'] =  $data['rating_two'] + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                    }else if($val->short_name == "AA"){
                        $holdpercentage = (float) $val->holdpercentage;
                        $schemecode_primary_id = $schemecode_primary_ids[$val->schemecode];
                        $same_primary_schemecode_id = $same_primary_schemecode[$val->schemecode];
                        $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                        $schemecode_value = $data['investment_price']*$schemecode_value/100;
                        $data['rating_three'] =  $data['rating_three'] + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                        // $data['rating_three'] =  $data['rating_three'] + (float) $val->holdpercentage;
                    }else if($val->short_name == "A"){
                        $holdpercentage = (float) $val->holdpercentage;
                        $schemecode_primary_id = $schemecode_primary_ids[$val->schemecode];
                        $same_primary_schemecode_id = $same_primary_schemecode[$val->schemecode];
                        $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                        $schemecode_value = $data['investment_price']*$schemecode_value/100;
                        $data['rating_four'] =  $data['rating_four'] + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                        // $data['rating_four'] =  $data['rating_four'] + (float) $val->holdpercentage;
                    }else if($val->short_name == "Unrated"){
                        $holdpercentage = (float) $val->holdpercentage;
                        $schemecode_primary_id = $schemecode_primary_ids[$val->schemecode];
                        $same_primary_schemecode_id = $same_primary_schemecode[$val->schemecode];
                        $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                        $schemecode_value = $data['investment_price']*$schemecode_value/100;
                        $data['rating_five'] =  $data['rating_five'] + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                        // $data['rating_five'] =  $data['rating_five'] + (float) $val->holdpercentage;
                    }
                }
                
                
                $data['date'] = $last_date->invdate;
            }
            
            // dd($data);
            $equity_top_holding_list = DB::table('mf_portfolio_analysis')
                        ->select(["compname","fincode",DB::raw("SUM(mf_portfolio_analysis.holdpercentage) as holdpercentage")])
                        ->LeftJoin('mf_asset_classes', 'mf_asset_classes.asect_name', '=', 'mf_portfolio_analysis.asect_name')
                        ->where('mf_portfolio_analysis.status','=',1)->whereIn('schemecode',$schemecode_ids)->where(function ($query) {
                            $query->where('mf_portfolio_analysis.asect_name', '=', "DOMESTIC EQUITIES")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "DOMESTIC EQUITY")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "EQUITY")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "EQUITY & EQUITY RELATED")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "EQUITY & EQUITY RELATED INSTRUMENTS")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "Overseas Equities");
                        })
                        ->groupBy("mf_portfolio_analysis.fincode")
                        ->orderBy('holdpercentage','DESC')->take(20)->get();
            $data['equity_top_holding_list'] = [];
            foreach ($equity_top_holding_list as $key => $value) {
                $equity_holding = DB::table('mf_portfolio_analysis')
                        ->select(["compname","holdpercentage","schemecode"])->where('mf_portfolio_analysis.fincode', '=', $value->fincode)->whereIn('schemecode',$schemecode_ids)->get();
                // dd($equity_holding);
                $total_number = 0;
                foreach ($equity_holding as $key1 => $value1) {
                    
                    $holdpercentage = $value1->holdpercentage;
                    $schemecode_primary_id = $schemecode_primary_ids[$value1->schemecode];
                    $same_primary_schemecode_id = $same_primary_schemecode[$value1->schemecode];
                    $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                    $schemecode_value = $data['investment_price']*$schemecode_value/100;
                    // echo $value1->schemecode."--".$value1->compname."--".$schemecode_value."<br>";
                    // dd($allocations[$value1->schemecode]);
                    $total_number = $total_number + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                }
                $data['equity_top_holding_list'][$key] = (array) $value;
                $data['equity_top_holding_list'][$key]['total_number'] = $total_number;
            }

            usort($data['equity_top_holding_list'],function($first,$second){
                return ($first['total_number'] < $second['total_number']);
            });
            // dd($data['equity_top_holding_list']);

            $equity_top_sector_list = DB::table('mf_portfolio_analysis')
                        ->select(["sector_name",DB::raw("SUM(mf_portfolio_analysis.holdpercentage) as holdpercentage")])
                        ->LeftJoin('mf_asset_classes', 'mf_asset_classes.asect_name', '=', 'mf_portfolio_analysis.asect_name')
                        ->where('mf_portfolio_analysis.status','=',1)->whereIn('schemecode',$schemecode_ids)->where(function ($query) {
                            $query->where('mf_portfolio_analysis.asect_name', '=', "DOMESTIC EQUITIES")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "DOMESTIC EQUITY")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "EQUITY")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "EQUITY & EQUITY RELATED")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "EQUITY & EQUITY RELATED INSTRUMENTS")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "")
                                ->orWhere('mf_portfolio_analysis.asect_name', '=', "Overseas Equities");
                        })
                        ->groupBy("mf_portfolio_analysis.sector_name")->orderBy('holdpercentage','DESC')->take(10)->get();
            $data['equity_top_sector_list'] = [];
            foreach ($equity_top_sector_list as $key => $value) {
                $equity_holding = DB::table('mf_portfolio_analysis')
                        ->select(["sector_name","holdpercentage","schemecode"])
                        ->where('mf_portfolio_analysis.sector_name', '=', $value->sector_name)
                        
                        ->whereIn('schemecode',$schemecode_ids)->get();
                        
                // dd($equity_holding);
                $total_number = 0;
                foreach ($equity_holding as $key1 => $value1) {
                    
                    $holdpercentage = $value1->holdpercentage;
                    $schemecode_primary_id = $schemecode_primary_ids[$value1->schemecode];
                    $same_primary_schemecode_id = $same_primary_schemecode[$value1->schemecode];
                    $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                    $schemecode_value = $data['investment_price']*$schemecode_value/100;
                    // echo $value1->schemecode."--".$value1->compname."--".$schemecode_value."<br>";
                    // dd($allocations[$value1->schemecode]);
                    $total_number = $total_number + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                }
                $data['equity_top_sector_list'][$key] = (array) $value;
                $data['equity_top_sector_list'][$key]['total_number'] = $total_number;
            }

            usort($data['equity_top_sector_list'],function($first,$second){
                return ($first['total_number'] < $second['total_number']);
            });

            $debt_top_holding_list = DB::table('mf_portfolio_analysis')
                       ->select(["compname","fincode",DB::raw("SUM(mf_portfolio_analysis.holdpercentage) as holdpercentage")])
                        ->LeftJoin('mf_asset_classes', 'mf_asset_classes.asect_name', '=', 'mf_portfolio_analysis.asect_name')
                        ->where('mf_portfolio_analysis.status','=',1)->whereIn('schemecode',$schemecode_ids)->where(function ($query) {
                            $query->where('mf_portfolio_analysis.asect_name', '=', "Certificate of Deposit")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Corporate Debt")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Government Securities")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Deposits")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Others")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Commercial Paper")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Deposits(Placed as Margin)")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Floating Rate Instrumrents")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "PTC & Securitized Debt")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Treasury Bills");
                        })
                        ->groupBy("mf_portfolio_analysis.fincode")
                        ->orderBy('holdpercentage','DESC')->take(20)->get();

            $data['debt_top_holding_list'] = [];
            foreach ($debt_top_holding_list as $key => $value) {
                $equity_holding = DB::table('mf_portfolio_analysis')
                        ->select(["compname","holdpercentage","schemecode"])->where('mf_portfolio_analysis.fincode', '=', $value->fincode)->whereIn('schemecode',$schemecode_ids)->get();
                $total_number = 0;
                foreach ($equity_holding as $key1 => $value1) {
                    
                    $holdpercentage = $value1->holdpercentage;
                    $schemecode_primary_id = $schemecode_primary_ids[$value1->schemecode];
                    $same_primary_schemecode_id = $same_primary_schemecode[$value1->schemecode];
                    $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                    $schemecode_value = $data['investment_price']*$schemecode_value/100;
                    $total_number = $total_number + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                }
                $data['debt_top_holding_list'][$key] = (array) $value;
                $data['debt_top_holding_list'][$key]['total_number'] = $total_number;
            }

            usort($data['debt_top_holding_list'],function($first,$second){
                return ($first['total_number'] < $second['total_number']);
            });

            $debt_top_asset_type_list = DB::table('mf_portfolio_analysis')
                        ->select(["mf_portfolio_analysis.asect_name",DB::raw("SUM(mf_portfolio_analysis.holdpercentage) as holdpercentage")])
                        ->LeftJoin('mf_asset_classes', 'mf_asset_classes.asect_name', '=', 'mf_portfolio_analysis.asect_name')
                        ->where('mf_portfolio_analysis.status','=',1)->whereIn('schemecode',$schemecode_ids)->where(function ($query) {
                            $query->where('mf_portfolio_analysis.asect_name', '=', "Certificate of Deposit")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Commercial Paper")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Corporate Debt")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Deposits")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Deposits(Placed as Margin)")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Floating Rate Instrumrents")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Government Securities")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "PTC & Securitized Debt")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Treasury Bills");
                        })
                        ->groupBy("mf_portfolio_analysis.asect_name")->orderBy('holdpercentage','DESC')->take(10)->get();

            $data['debt_top_asset_type_list'] = [];
            foreach ($debt_top_asset_type_list as $key => $value) {
                $equity_holding = DB::table('mf_portfolio_analysis')
                        ->select(["asect_name","holdpercentage","schemecode"])->where('mf_portfolio_analysis.asect_name', '=', $value->asect_name)->whereIn('schemecode',$schemecode_ids)->get();
                $total_number = 0;
                foreach ($equity_holding as $key1 => $value1) {
                    
                    $holdpercentage = $value1->holdpercentage;
                    $schemecode_primary_id = $schemecode_primary_ids[$value1->schemecode];
                    $same_primary_schemecode_id = $same_primary_schemecode[$value1->schemecode];
                    $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                    $schemecode_value = $data['investment_price']*$schemecode_value/100;
                    $total_number = $total_number + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                }
                $data['debt_top_asset_type_list'][$key] = (array) $value;
                $data['debt_top_asset_type_list'][$key]['total_number'] = $total_number;
            }

            usort($data['debt_top_asset_type_list'],function($first,$second){
                return ($first['total_number'] < $second['total_number']);
            });

            $gold_top_holding_list = DB::table('mf_portfolio_analysis')
                        ->select(["compname","fincode",DB::raw("SUM(mf_portfolio_analysis.holdpercentage) as holdpercentage")])
                        ->LeftJoin('mf_asset_classes', 'mf_asset_classes.asect_name', '=', 'mf_portfolio_analysis.asect_name')
                        ->where('mf_portfolio_analysis.status','=',1)->whereIn('schemecode',$schemecode_ids)->where(function ($query) {
                            $query->where('mf_portfolio_analysis.asect_name', '=', "GOLD")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Gold");
                        })
                        ->groupBy("mf_portfolio_analysis.fincode")
                        ->orderBy('holdpercentage','DESC')->take(5)->get();

            $data['gold_top_holding_list'] = [];
            foreach ($gold_top_holding_list as $key => $value) {
                $equity_holding = DB::table('mf_portfolio_analysis')
                        ->select(["compname","holdpercentage","schemecode"])->where('mf_portfolio_analysis.fincode', '=', $value->fincode)->whereIn('schemecode',$schemecode_ids)->get();
                $total_number = 0;
                foreach ($equity_holding as $key1 => $value1) {
                    
                    $holdpercentage = $value1->holdpercentage;
                    $schemecode_primary_id = $schemecode_primary_ids[$value1->schemecode];
                    $same_primary_schemecode_id = $same_primary_schemecode[$value1->schemecode];
                    $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                    $schemecode_value = $data['investment_price']*$schemecode_value/100;
                    $total_number = $total_number + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                }
                $data['gold_top_holding_list'][$key] = (array) $value;
                $data['gold_top_holding_list'][$key]['total_number'] = $total_number;
            }

            usort($data['gold_top_holding_list'],function($first,$second){
                return ($first['total_number'] < $second['total_number']);
            });

            $other_top_holding_list = DB::table('mf_portfolio_analysis')
                        ->select(["compname","fincode",DB::raw("SUM(mf_portfolio_analysis.holdpercentage) as holdpercentage")])
                        ->LeftJoin('mf_asset_classes', 'mf_asset_classes.asect_name', '=', 'mf_portfolio_analysis.asect_name')
                        ->where('mf_portfolio_analysis.status','=',1)->whereIn('schemecode',$schemecode_ids)->where(function ($query) {
                            $query->where('mf_portfolio_analysis.asect_name', '=', "Cash & Cash Equivalents")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Cash & Cash Equivalents and Net Assets")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Bills Rediscounting")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Derivatives-Futures")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Warrants")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Domestic Mutual Funds Units")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Preference Shares")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Rights")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "ADRs & GDRs")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "Overseas Mutual Fund Units")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "DERIVATIVES-CALL OPTIONS")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "DERIVATIVES-PUT OPTIONS")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "REITS & INVITS")
                            ->orWhere('mf_portfolio_analysis.asect_name', '=', "SILVER");
                        })
                        ->groupBy("mf_portfolio_analysis.fincode")
                        ->orderBy('holdpercentage','DESC')->take(5)->get();

            $data['other_top_holding_list'] = [];
            foreach ($other_top_holding_list as $key => $value) {
                $equity_holding = DB::table('mf_portfolio_analysis')
                        ->select(["compname","holdpercentage","schemecode"])->where('mf_portfolio_analysis.fincode', '=', $value->fincode)->whereIn('schemecode',$schemecode_ids)->get();
                $total_number = 0;
                foreach ($equity_holding as $key1 => $value1) {
                    
                    $holdpercentage = $value1->holdpercentage;
                    $schemecode_primary_id = $schemecode_primary_ids[$value1->schemecode];
                    $same_primary_schemecode_id = $same_primary_schemecode[$value1->schemecode];
                    $schemecode_value = isset($allocations[$schemecode_primary_id])?$allocations[$schemecode_primary_id]:0;
                    $schemecode_value = $data['investment_price']*$schemecode_value/100;
                    $total_number = $total_number + ($holdpercentage*$schemecode_value/$data['investment_price'] * $same_primary_schemecode_id);
                }
                $data['other_top_holding_list'][$key] = (array) $value;
                $data['other_top_holding_list'][$key]['total_number'] = $total_number;
            }

            usort($data['other_top_holding_list'],function($first,$second){
                return ($first['total_number'] < $second['total_number']);
            });

            // dd($data['other_top_holding_list']);
            
                
            if($data['action_type'] == "DOWNLOAD"){
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
                $data['pie_chart2'] = Session::get('pie_chart2');
                // return view('frontend.portfolio.investment_analysis_pdf',$data);
                // if(Auth::user()->email == "test@test.com"){
                //     $pdf = PDF::loadView('frontend.portfolio.investment_analysis_pdf', $data);
                //     return $pdf->stream('investment_portfolio_analysis.pdf');
                // }else{
                //     $pdf = PDF::loadView('frontend.portfolio.investment_analysis_pdf', $data);
                //     return $pdf->download('investment_portfolio_analysis.pdf');
                // }
                $pdf = PDF::loadView('frontend.portfolio.investment_analysis_pdf', $data);
                return $pdf->download('investment_portfolio_analysis.pdf');
            }else if($data['action_type'] == "SAVE"){
                // dd($data);
                $data['pie_chart2'] = Session::get('pie_chart2');
                if (Auth::check()){
                    $insertData = [];
                    $insertData['user_id'] = Auth::user()->id;
                    $insertData['type'] = "mf-investment-analysis";
                    $insertData['mf_researche_id'] = 11;
                    $insertData['name'] = $data['title'];
                    $insertData['data'] = serialize($data);
                    // dd($insertData);
                    DB::table("mf_scanner_saved")->insert($insertData);
                    return redirect()->route('frontend.scanner_saved_files');
                }
            }

            // dd($data['debt_top_holding_list']);
        }
        
        $user_id = 0;
        if(Auth::user()){
            $mf_researches = DB::table("mf_researches")->where("url","mf-investment-portfolio-analysis")->first();
            $package_id = Auth::user()->package_id;
            $user_id = Auth::user()->id;
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
        $data['scheme_list'] = DB::table("mf_scanner")->select(['schemecode','s_name'])->where('status',1)->orderBy('s_name','ASC')->get();
        // dd($data);
        
        
        $ip_address = $this->getIp();
    
        History::create([
            'list_count' => 1,
            'user_id' => $user_id,
            'page_type' => "MF Research",
            'page_id' => 11,
            'ip' => $ip_address
        ]);
        
        return view('frontend.portfolio.investment_analysis',$data);
    }
    
    public function investment_analysis_pdf(Request $request){
        $id = $request->id;
        $type = $request->type;
        
        
        $input = DB::table("mf_scanner_saved")->where("id","=",$id)->first();
        // dd($input);
        $data = unserialize($input->data);
        
        $data['name'] = $input->name;
        $data['mf_scanner_saved_id'] = $input->id;
        
        if($type == "D"){
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
            // return view('frontend.portfolio.investment_analysis_pdf',$data);
            $pdf = PDF::loadView('frontend.portfolio.investment_analysis_pdf', $data);
            return $pdf->download('investment_portfolio_analysis.pdf');
        }else if($type == "V"){
            // dd($data);
            return view('frontend.portfolio.investment_analysis_view',$data);
        }
        
        dd($input);
    }

    public function investment_analysis_image(Request $request){
        $input = $request->all();
        $base64Image = str_replace('data:image/png;base64,', '', $input['img']);

        // Decode the base64 image data
        $imageData = base64_decode($base64Image);

        // Generate a unique filename
        $filename = 'image_' . time() . '.png';

        // Specify the path where you want to save the image
        $path = public_path($filename);

        // Save the image to the specified path
        file_put_contents($path, $imageData);

        $image = asset($filename);

        Session::put('pie_chart2', $image);
        return response()->json(['message' => 'Image saved successfully']);
    }
    
    public function investment_analysis_action(){
    }

}