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

class MFScannerCompareController extends Controller
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
    
    public function removeCompare(Request $request){
        $input = $request->all();
        if(isset($input['schemecode'])){
            $schemecode_id = session()->get('ms_mf_scenner_schemecode_id');
            $schemecode_ids = explode(",", $schemecode_id);
            $schemecode_id = "";
            foreach ($schemecode_ids as $key => $value) {
                if($value != $input['schemecode']){
                    if($schemecode_id){
                        $schemecode_id = $schemecode_id.','.$value;
                    }else{
                        $schemecode_id = $value;
                    }
                }
                session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
            }
            
        }
        $schemecode_id = session()->get('ms_mf_scenner_schemecode_id');
        $schemecode_ids = explode(",", $schemecode_id);
        echo count($schemecode_ids);
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

    public function saveCompare(){
        $schemecode_id = session()->get('ms_mf_scenner_schemecode_id');
        $insertData = [];
        $insertData['user_id'] = Auth::user()->id;
        $insertData['type'] = 2;
        $insertData['mf_researche_id'] = 2;
        $insertData['name'] = $save_title;
        $insertData['data'] = serialize($schemecode_id);
        DB::table("mf_scanner_saved")->insert($insertData);
        return redirect()->route('frontend.mf_scanner_compare');
    }
    
    public function compare(Request $request){
        $schemecode_id = session()->get('ms_mf_scenner_schemecode_id');
        // echo "<br>";
        $input = $request->all();
        if(isset($input['compare_type'])){
            // dd($input);
            if($input['compare_type'] == "ADD"){
                if($schemecode_id){
                    $schemecode_ids = explode(",", $schemecode_id);
                    // dd($schemecode_ids);
                    if(count($schemecode_ids)<4){
                        $schemecode_id = $schemecode_id.','.$input['compare_schemecode'];
                        session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
                        return redirect()->route('frontend.mf_scanner_compare');
                    }else{
                        return redirect()->route('frontend.mf_scanner_compare')->with('success','Account Created');
                    }
                }else{
                    $schemecode_id = $input['compare_schemecode'];
                    session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
                    return redirect()->route('frontend.mf_scanner_compare');
                }
            }else if($input['compare_type'] == "DOWNLOAD"){
                if(!Auth::check()){
                    return redirect('login');
                }
                $expry = Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
                if(empty($expry)){
                    return redirect('membership');
                }
                $pdf_data = [];
                $pdf_data['result'] = $this->getCompareScheamDetail($schemecode_id);
                $pdf_data['basic_detail_checkbox'] = ($input['basic_detail_checkbox'])?explode(',',$input['basic_detail_checkbox']):[];
                $pdf_data['rating_checkbox'] = ($input['rating_checkbox_id'])?1:0;
                $pdf_data['return_checkbox'] = ($input['return_checkbox'])?explode(',',$input['return_checkbox']):[];
                $pdf_data['mf_ratios_checkbox'] = ($input['mf_ratios_checkbox'])?explode(',',$input['mf_ratios_checkbox']):[];
                $pdf_data['portfolio_checkbox'] = ($input['portfolio_checkbox'])?explode(',',$input['portfolio_checkbox']):[];
                $pdf_data['sector_checkbox'] = ($input['sector_checkbox'])?explode(',',$input['sector_checkbox']):[];
                $pdf_data['holding_checkbox'] = ($input['holding_checkbox'])?$input['holding_checkbox']:"";
                
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
                // dd($pdf_data);
                //return view('frontend.mf_scanner.compare_pdf',$pdf_data);
                $pdf = PDF::loadView('frontend.mf_scanner.compare_pdf', $pdf_data);
                return $pdf->download('mf_comparison.pdf');
            }else if($input['compare_type'] == "SAVE"){
                if(!Auth::check()){
                    return redirect('login');
                }
                $expry = Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
                if(empty($expry)){
                    return redirect('membership');
                }
                
                $mf_scenner_data = [
                    "basic_detail_checkbox"=>$input['basic_detail_checkbox'],
                    "return_checkbox"=>$input['return_checkbox'],
                    "mf_ratios_checkbox"=>$input['mf_ratios_checkbox'],
                    "portfolio_checkbox"=>$input['portfolio_checkbox'],
                    "sector_checkbox"=>$input['sector_checkbox'],
                    "holding_checkbox"=>$input['holding_checkbox'],
                    "rating_checkbox"=>$input['rating_checkbox_id'],
                    "schemecode_id"=>$schemecode_id
                ];
                $insertData = [];
                $insertData['user_id'] = Auth::user()->id;
                $insertData['type'] = 2;
                $insertData['mf_researche_id'] = 2;
                $insertData['name'] = $input['compare_title'];
                $insertData['data'] = serialize($mf_scenner_data);
                DB::table("mf_scanner_saved")->insert($insertData);
                return redirect()->route('frontend.mf_scanner_compare');
            }
        }
        $input = session()->get('ms_mf_scenner_data');
        $data = [];
        
        $schemecode_ids = [];
        $data['result'] = [];
        if($schemecode_id){
            $data['result'] = $this->getCompareScheamDetail($schemecode_id);
        }

        // dd($data['result']);

        $data['dropdownList'] = DB::table("mf_scanner")->select(['schemecode','s_name'])->whereNotIn('schemecode',$schemecode_ids)->where('mf_scanner.status',1)->orderBy("s_name","ASC")->get();
        $data['activemenu'] = 'mf-scanner-compare';
        $user_id = 0;
        if(Auth::user()){
            $mf_researches = DB::table("mf_researches")->where("url","mf-screener-compare")->first();
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
        $ip_address = $this->getIp();
    
        History::create([
            'list_count' => 1,
            'user_id' => $user_id,
            'page_type' => "MF Research",
            'page_id' => 2,
            'ip' => $ip_address
        ]);
        $data['category_list'] = DB::table("accord_sclass_mst")->select(['classcode','classname'])->orderBy('classname', 'asc')->get();
        return view('frontend.mf_scanner.mf_scanner_compare',$data);
    }
    
    
    public function update_compare(Request $request,$id){
        $schemecode_id = session()->get('ms_mf_scenner_schemecode_id');
        $input = $request->all();
        if(isset($input['compare_type'])){
            // dd($input);
            if($input['compare_type'] == "ADD"){
                if($schemecode_id){
                    $schemecode_ids = explode(",", $schemecode_id);
                    // dd($schemecode_ids);
                    if(count($schemecode_ids)<4){
                        $schemecode_id = $schemecode_id.','.$input['compare_schemecode'];
                        session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
                        return redirect()->back()->withInput()->with('error',"");
                    }else{
                        return redirect()->back()->withInput()->with('success','Account Created');
                    }
                }else{
                    $schemecode_id = $input['compare_schemecode'];
                    session()->put('ms_mf_scenner_schemecode_id',$schemecode_id);
                    return redirect()->back()->withInput()->with('error',"");
                }
            }else if($input['compare_type'] == "DOWNLOAD"){
                if(!Auth::check()){
                    return redirect('login');
                }
                $expry = Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
                if(empty($expry)){
                    return redirect('membership');
                }
                
                // dd($input);
                $pdf_data = [];
                $pdf_data['result'] = $this->getCompareScheamDetail($schemecode_id);
                $pdf_data['basic_detail_checkbox'] = ($input['basic_detail_checkbox'])?explode(',',$input['basic_detail_checkbox']):[];
                $pdf_data['return_checkbox'] = ($input['return_checkbox'])?explode(',',$input['return_checkbox']):[];
                $pdf_data['mf_ratios_checkbox'] = ($input['mf_ratios_checkbox'])?explode(',',$input['mf_ratios_checkbox']):[];
                $pdf_data['portfolio_checkbox'] = ($input['portfolio_checkbox'])?explode(',',$input['portfolio_checkbox']):[];
                $pdf_data['sector_checkbox'] = ($input['sector_checkbox'])?explode(',',$input['sector_checkbox']):[];
                $pdf_data['holding_checkbox'] = ($input['holding_checkbox'])?$input['holding_checkbox']:"";
                $pdf_data['rating_checkbox'] = isset($input['rating_checkbox'])?$input['rating_checkbox']:"";
                
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
                // dd($pdf_data);
                //return view('frontend.mf_scanner.compare_pdf',$pdf_data);
                $pdf = PDF::loadView('frontend.mf_scanner.compare_pdf', $pdf_data);
                return $pdf->download('mf_comparison.pdf');
            }else if($input['compare_type'] == "SAVE"){
                if(!Auth::check()){
                    return redirect('login');
                }
                $expry = Membership::where('user_id', Auth::user()->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active', 1)->where('duration_name','!=', '')->first();
                if(empty($expry)){
                    return redirect('membership');
                }
                
                $mf_scenner_data = [
                    "basic_detail_checkbox"=>$input['basic_detail_checkbox'],
                    "return_checkbox"=>$input['return_checkbox'],
                    "mf_ratios_checkbox"=>$input['mf_ratios_checkbox'],
                    "portfolio_checkbox"=>$input['portfolio_checkbox'],
                    "sector_checkbox"=>$input['sector_checkbox'],
                    "holding_checkbox"=>$input['holding_checkbox'],
                    "schemecode_id"=>$schemecode_id
                ];
                $insertData = [];
                $insertData['user_id'] = Auth::user()->id;
                $insertData['type'] = 2;
                $insertData['mf_researche_id'] = 2;
                $insertData['data'] = serialize($mf_scenner_data);
                DB::table("mf_scanner_saved")->where('id',$id)->update($insertData);
                return redirect()->back()->withInput()->with('error',"");
            }
        }
        $data = DB::table("mf_scanner_saved")->where("id","=",$id)->first();
        $input = unserialize($data->data);
        $input['name'] = $data->name;
        $input['mf_scanner_saved_id'] = $data->id;
        if($schemecode_id){
            
        }else{
            session()->put('ms_mf_scenner_schemecode_id',$input['schemecode_id']);
        
            $schemecode_id = $input['schemecode_id'];
        }
        

        $data = [];
        
        $schemecode_ids = [];
        $data['result'] = [];
        if($schemecode_id){
            $data['result'] = $this->getCompareScheamDetail($schemecode_id);
        }

        $data['dropdownList'] = DB::table("mf_scanner")->select(['schemecode','s_name'])->whereNotIn('schemecode',$schemecode_ids)->orderBy('s_name','ASC')->get();
        $data['activemenu'] = 'mf-scanner-compare';
        $data['basic_detail_checkbox'] = ($input['basic_detail_checkbox'])?explode(',',$input['basic_detail_checkbox']):[];
        $data['return_checkbox'] = ($input['return_checkbox'])?explode(',',$input['return_checkbox']):[];
        $data['mf_ratios_checkbox'] = ($input['mf_ratios_checkbox'])?explode(',',$input['mf_ratios_checkbox']):[];
        $data['portfolio_checkbox'] = ($input['portfolio_checkbox'])?explode(',',$input['portfolio_checkbox']):[];
        $data['sector_checkbox'] = ($input['sector_checkbox'])?explode(',',$input['sector_checkbox']):[];
        $data['holding_checkbox'] = ($input['holding_checkbox'])?$input['holding_checkbox']:"";
        // dd($data);
        return view('frontend.mf_scanner.mf_update_scanner_compare',$data);

    }

}
