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
use Response;

class MFResearchController extends Controller
{

    public function scanner_about(Request $request){
        $data['activemenu'] = 'about';
        return view('frontend.mf_scanner.about',$data);
    }

    public function scanner_saved_files(Request $request){
        session()->put('ms_mf_scenner_schemecode_id',"");
        $data['activemenu'] = 'savefile';
        $data['saved_file_lists'] = DB::table("mf_scanner_saved")
                ->select(["mf_scanner_saved.*","mf_researches.name as mf_researche_name","mf_researches.url","mf_researches.description"])
                ->LeftJoin('mf_researches', 'mf_researches.id', '=', 'mf_scanner_saved.mf_researche_id')
                ->where('user_id',Auth::user()->id)->orderBy('id','desc')->paginate(10);

        $data['permission'] = [];
        $package_id = Auth::user()->package_id;
        $mf_researches = DB::table("mf_researches")->LeftJoin("mf_research_permissions",'mf_research_permissions.mf_research_id', '=', 'mf_researches.id')->where("mf_research_permissions.package_id",$package_id)->get();

        foreach ($mf_researches as $key => $value) {
            $data['permission'][$value->mf_research_id] = [
                "is_view"=>$value->is_view,
                "is_download"=>$value->is_download,
                "is_save"=>$value->is_save,
                "is_cover"=>$value->is_cover,
                "is_csv"=>$value->is_csv
            ];
        }

        return view('frontend.mf_research.savefile',$data);
    }

    public function getCompareScheamDetail($schemecode_id){
        $data = [];
        $schemecode_ids = explode(",", $schemecode_id);
        foreach ($schemecode_ids as $key => $value) {
            $result = DB::table("mf_scanner")
                        ->select([
                            'mf_scanner.schemecode','primary_fd_code','scheme_name','s_name','1dayret as onedayret','1weekret as oneweekret',
                            '1monthret as onemonthret','3monthret as threemonthret','6monthret as sixmonthret','1yrret as oneyrret',
                            '2yearret as twoyearret','3yearret as threeyearret','5yearret as fiveyearret','10yret as tenyret','incret',
                            'turnover_ratio','tr_mode','total','total_date','navrs','expratio','EXITLOAD','REMARKS','Incept_date','INDEXCODE',
                            'IndexName','MCAP','PE','PB','Div_Yield','avg_mat_num','avg_mat_days','mod_dur_num','mod_dur_days','ytm','alpha',
                            'sharpe','treynor','sortino','beta','sd','trackingError','amc_code','plan','opt_code','type_code','mf_scanner.classcode','fund_mgr_code1',
                            'fund_mgr1','classname','category','large_cap','mid_cap','small_cap','highest_sector_all','highest_sector_all_per','ASECT_CODE','mf_ratting.rate as rating'])

                        ->LeftJoin('mf_ratting', 'mf_ratting.schemecode', '=', 'mf_scanner.schemecode')
                        ->where('mf_scanner.schemecode',$value)->where('mf_scanner.status',1)->first();
            $result->rating_one = "";            
            $result->rating_two = "";            
            $result->rating_three = "";            
            $result->rating_four = "";            
            $result->rating_five = "";            
            $last_date = DB::table('accord_mf_portfolio')->where('schemecode','=',$result->primary_fd_code)->orderBy('invdate','DESC')->first();
            if($last_date){
                $result->holding_list = DB::table('accord_mf_portfolio')->select(['accord_companymaster.compname',DB::raw("SUM(accord_mf_portfolio.holdpercentage) as holdpercentage")])->join('accord_companymaster', 'accord_companymaster.fincode', '=', 'accord_mf_portfolio.fincode')->where('accord_mf_portfolio.schemecode','=',$result->primary_fd_code)->where('invdate','=',$last_date->invdate)->groupBy('accord_mf_portfolio.compname')->orderByRaw('SUM(accord_mf_portfolio.holdpercentage) DESC')->take(10)->get();
                $result->sector_list = DB::table('accord_sect_allocation')->select(['SECT_NAME',DB::raw("SUM(Perc_Hold) as Perc_Hold")])->where('schemecode','=',$result->primary_fd_code)->where('InvDate','=',$last_date->invdate)->groupBy('SECT_NAME')->orderByRaw('SUM(Perc_Hold) DESC')->take(3)->get();
                $result1 = DB::table('accord_mf_portfolio')->select(['rattings.short_name','accord_mf_portfolio.rating',DB::raw("SUM(accord_mf_portfolio.holdpercentage) as holdpercentage")])->join('rattings', 'rattings.category_name', '=', 'accord_mf_portfolio.rating')->where('accord_mf_portfolio.schemecode','=',$result->primary_fd_code)->where('invdate','=',$last_date->invdate)->groupBy('rattings.short_name')->orderByRaw('SUM(accord_mf_portfolio.holdpercentage) DESC')->get();
                // dd($result1);
                foreach($result1 as $val){
                    if($val->short_name == "Sovereign"){
                        $result->rating_one = $val->holdpercentage;
                    }else if($val->short_name == "AAA"){
                        $result->rating_two = $val->holdpercentage;
                    }else if($val->short_name == "AA"){
                        $result->rating_three = $val->holdpercentage;
                    }else if($val->short_name == "A"){
                        $result->rating_four = $val->holdpercentage;
                    }else if($val->short_name == "Unrated"){
                        $result->rating_five = $val->holdpercentage;
                    }
                }
            }else{
                $result->holding_list = DB::table('accord_mf_portfolio')->select(['accord_companymaster.compname',DB::raw("SUM(accord_mf_portfolio.holdpercentage) as holdpercentage")])->join('accord_companymaster', 'accord_companymaster.fincode', '=', 'accord_mf_portfolio.fincode')->where('accord_mf_portfolio.schemecode','=',$result->primary_fd_code)->groupBy('accord_mf_portfolio.compname')->orderByRaw('SUM(accord_mf_portfolio.holdpercentage) DESC')->take(10)->get();
                $result->sector_list = DB::table('accord_sect_allocation')->select(['SECT_NAME',DB::raw("SUM(Perc_Hold) as Perc_Hold")])->where('schemecode','=',$result->primary_fd_code)->groupBy('SECT_NAME')->orderByRaw('SUM(Perc_Hold) DESC')->take(3)->get();
            }
            array_push($data,$result);
        }
        return $data;
    }
    
    public function mf_download_saved_file(Request $request){
        $id = $request->id;
        $data = DB::table("mf_scanner_saved")->where("id","=",$id)->first();
         //dd($data->type);
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
             //Soumyadip Logic to download file type (landscape/Potrait)
             $checked_column_count = 0;
             foreach($data['response'] as $key=>$val){
                 if($val['table_checkbox'] === 1){
                     $checked_column_count++;
                 }
             }
             //dd($checked_column_count);
             if($checked_column_count <= 8){
                 $pdf = PDF::loadView('frontend.mf_scanner.pdf', $data);
             }else{
                 $pdf = PDF::loadView('frontend.mf_scanner.landscape_pdf', $data)->setPaper('a4', 'landscape');
             }
             // Logic end
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
            $data['crkey'] = $input['crkey'];
            $data['cr'] = $input['cr'];
            $data['yrkey'] = $input['yrkey'];
            $data['yr'] = $input['yr'];
            $data['name'] = $input['name'];
            $data['mf_scanner_saved_id'] = $input['mf_scanner_saved_id'];
            
            $data['category_detail'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')->where('accord_sclass_mst.classcode','=', $input['category_id'])->first();
                        
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
            $pdf = PDF::loadView('frontend.categorywiseperformance.pdf', $data);
            return $pdf->download('mf_category_wise_scheme_performance.pdf');
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
            if(!isset($input['type'])){
                dd("old Data");
            }
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
            
            // return view('frontend.mf_scanner.stocks_held_pdf',$input);
            $pdf = PDF::loadView('frontend.mf_scanner.stocks_held_pdf', $input);
            return $pdf->download('stocks_held_pdf.pdf');
        }
    }

    public function mf_view_saved_file_details(Request $request){
        $input = $request->all();
        $data = DB::table("mf_scanner_saved")->where("id","=",$input['id'])->first();

        //dd($data);
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

    public function mf_delete_saved_file(Request $request){
        $input = $request->all();
        DB::table("mf_scanner_saved")->where("id","=",$input['id'])->delete();
        // dd($input['id']);
        return redirect()->route('frontend.scanner_saved_files');
    }

    public function mf_page_one(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_research.mf_page_one',$data);
    }

    public function mf_page_two(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_research.mf_page_two',$data);
    }

    public function mf_page_three(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_research.mf_page_three',$data);
    }

    public function mf_page_four(Request $request){
        $data['activemenu'] = '';
        return view('frontend.mf_research.mf_page_four',$data);
    }

}
