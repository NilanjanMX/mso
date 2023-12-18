<?php

namespace App\Http\Controllers\Frontend\Calculators;

use App\Models\Displayinfo;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\FundPerformanceCreateList;
use App\Models\Membership;
use App\Models\SaveCalculators;
use App\Models\CalculatorHeading;
use App\Models\SchemecodeData;
use App\Models\Savelist;
use App\Models\UserHistory;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\Calculator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use DB;
use App\Models\Savelistsoftcopy;


class SipRequiredTargetMonthlyAnnuityController extends Controller
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
    
    public function monthlyAnnuityForSIP(Request $request){

        if($request->action == "back"){
            if (session()->has('monthly_annuity_for_sip_form_id')){
                session()->forget('monthly_annuity_for_sip_form_id');
            }
            // dd(Session::get('monthly_annuity_for_sip_form_data'));
            if (Session::has('monthly_annuity_for_sip_form_data')) {
                $saveCalculatorsData = Session::get('monthly_annuity_for_sip_form_data');
                
                $data = [];
                $data['form_data'] = [];
                $data['form_data']['client'] = isset($saveCalculatorsData['client'])?$saveCalculatorsData['client']:0;
                $data['form_data']['report'] = isset($saveCalculatorsData['report'])?$saveCalculatorsData['report']:0;
                $data['form_data']['clientname'] = isset($saveCalculatorsData['clientname'])?$saveCalculatorsData['clientname']:"";
                $data['form_data']['balance_required'] = isset($saveCalculatorsData['balance_required'])?$saveCalculatorsData['balance_required']:"";
                $data['form_data']['current_age'] = isset($saveCalculatorsData['current_age'])?$saveCalculatorsData['current_age']:"";
                $data['form_data']['is_note'] = $saveCalculatorsData['is_note'];
                $data['form_data']['note'] = isset($saveCalculatorsData['note'])?$saveCalculatorsData['note']:'';
                $data['form_data']['sip_amount'] = isset($saveCalculatorsData['sip_amount'])?$saveCalculatorsData['sip_amount']:'';
                $data['form_data']['sip_period'] = isset($saveCalculatorsData['sip_period'])?$saveCalculatorsData['sip_period']:'';
                $data['form_data']['annuity_period'] = isset($saveCalculatorsData['annuity_period'])?$saveCalculatorsData['annuity_period']:'';
                $data['form_data']['accumulation_phase_interest_rate_1'] = isset($saveCalculatorsData['accumulation_phase_interest_rate_1'])?$saveCalculatorsData['accumulation_phase_interest_rate_1']:'';
                $data['form_data']['distribution_phase_interest_rate_1'] = isset($saveCalculatorsData['distribution_phase_interest_rate_1'])?$saveCalculatorsData['distribution_phase_interest_rate_1']:'';
                $data['form_data']['accumulation_phase_interest_rate_2'] = isset($saveCalculatorsData['accumulation_phase_interest_rate_2'])?$saveCalculatorsData['accumulation_phase_interest_rate_2']:'';
                $data['form_data']['distribution_phase_interest_rate_2'] = isset($saveCalculatorsData['distribution_phase_interest_rate_2'])?$saveCalculatorsData['distribution_phase_interest_rate_2']:'';
                $data['form_data']['deferment'] = isset($saveCalculatorsData['deferment'])?$saveCalculatorsData['deferment']:'';
                $data['form_data']['deferment_period'] = isset($saveCalculatorsData['deferment_period'])?$saveCalculatorsData['deferment_period']:'';
                $data['form_data']['include_inflation'] = isset($saveCalculatorsData['include_inflation'])?$saveCalculatorsData['include_inflation']:'';
                $data['form_data']['expected_inflation_rate'] = isset($saveCalculatorsData['expected_inflation_rate'])?$saveCalculatorsData['expected_inflation_rate']:'';
                $data['form_data']['include_taxation'] = isset($saveCalculatorsData['include_taxation'])?$saveCalculatorsData['include_taxation']:'';
                $data['form_data']['assumed_inflation_rate_for_indexation'] = isset($saveCalculatorsData['assumed_inflation_rate_for_indexation'])?$saveCalculatorsData['assumed_inflation_rate_for_indexation']:'';
                $data['form_data']['applicable_short_term_tax_rate'] = isset($saveCalculatorsData['applicable_short_term_tax_rate'])?$saveCalculatorsData['applicable_short_term_tax_rate']:'';
                $data['form_data']['for_period_upto'] = isset($saveCalculatorsData['for_period_upto'])?$saveCalculatorsData['for_period_upto']:'';
                $data['form_data']['applicable_long_term_tax_rate'] = isset($saveCalculatorsData['applicable_long_term_tax_rate'])?$saveCalculatorsData['applicable_long_term_tax_rate']:'';
                $data['form_data']['from_the_year'] = isset($saveCalculatorsData['from_the_year'])?$saveCalculatorsData['from_the_year']:'';
                $data['form_data']['suggest'] = "";
                $data['custom_list_input'] = "";
                $data['category_list_input'] = "";
                $data['suggestedlist_type'] = "";
                $data['scheme_type'] = [];
                $data['scheme_amount'] = [];   
                
                if(isset($saveCalculatorsData['suggest'])){
                    
                    $saveCalculatorsData['suggested_performance'] = session()->get('suggested_performance');

                    $saveCalculatorsData['suggested_scheme_list'] = session()->get('suggested_scheme_list');
                    $saveCalculatorsData['calculator_duration'] = session()->get('calculator_duration');
            
                    $data['form_data']['suggest'] = $saveCalculatorsData['suggest'];
                    if($saveCalculatorsData['suggest'] == 1){
                        $data['suggested_performance'] = $saveCalculatorsData['suggested_performance'];
                        $data['suggestedlist_type'] = $saveCalculatorsData['suggestedlist_type'];
                        $data['calculator_duration'] = $saveCalculatorsData['calculator_duration'];
            
                        $data['suggested_scheme_list'] = [];
            
                        if($data['suggestedlist_type'] == "createlist"){
                            $data['scheme_type'] = $saveCalculatorsData['scheme_type'];
                            $data['scheme_amount'] = $saveCalculatorsData['scheme_amount'];
                            $data['suggested_scheme_list'] = $saveCalculatorsData['suggested_scheme_list'];
                            Session::put('suggested_scheme_list', $saveCalculatorsData['suggested_scheme_list']);
                        }else if($data['suggestedlist_type'] == "customlist"){
                            $data['custom_list_input'] = $saveCalculatorsData['custom_list_input'];
                        }else if($data['suggestedlist_type'] == "categorylist"){
                            $data['category_list_input'] = $saveCalculatorsData['category_list_input'];
                        }            
                        
                        Session::put('suggested_performance', $saveCalculatorsData['suggested_performance']);
                        Session::put('calculator_duration', $saveCalculatorsData['calculator_duration']);
                    }else{
                        $data['suggested_performance'] = "";
                        $data['suggested_scheme_list'] = [];
                        $data['calculator_duration'] = [];
                    }
                }else{
                    $data['suggested_performance'] = "";
                    $data['suggested_scheme_list'] = [];
                    $data['calculator_duration'] = [];
                }
                
                
        
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','sip_required_for_target_monthly_swp')->first();
                // dd($data);

                $data['details'] = DB::table("calculators")->where("url","calculators/swp-calculator/sip-required-for-target-monthly-annuity")->first();
                // dd($data);
                return view('frontend.calculators.siprequiredfortargetmonthlyannuity.edit',$data);
                
            }else{
                return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
            }
        }else{
            if (session()->has('suggested_scheme_list')){
                session()->forget('suggested_scheme_list');
            }
            if (session()->has('monthly_annuity_for_sip_form_data')){
                session()->forget('monthly_annuity_for_sip_form_data');
            }
            if (session()->has('monthly_annuity_for_sip_form_id')){
                session()->forget('monthly_annuity_for_sip_form_id');
            }
            if (session()->has('calc_title')){
                session()->forget('calc_title');
            }
    
            $ip_address = getIp();
    
            History::create([
                'list_count' => 1,
                'user_id' => Auth::user()->id,
                'page_type' => "Calculator",
                'page_id' => 22,
                'ip' => $ip_address
            ]);

            $data = [];
            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','sip_required_for_target_monthly_swp')->first();
            
            $data['details'] = DB::table("calculators")->where('url','calculators/swp-calculator/sip-required-for-target-monthly-annuity')->first();
            // dd($data);
            return view('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip',$data);
        }
    }

    public function monthlyAnnuityForSIPOutput(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('monthly_annuity_for_sip_form_data')) {
                $input = Session::get('monthly_annuity_for_sip_form_data');
                $data['sip_amount'] = $input['sip_amount'];
                $data['sip_period'] = $input['sip_period'];
                $data['annuity_period'] = $input['annuity_period'];
                $data['accumulation_phase_interest_rate_1'] = $input['accumulation_phase_interest_rate_1'];
                $data['distribution_phase_interest_rate_1'] = $input['distribution_phase_interest_rate_1'];
                $data['report'] = $input['report'];

                if ($input['accumulation_phase_interest_rate_2'] != '') {
                    $data['accumulation_phase_interest_rate_2'] = $input['accumulation_phase_interest_rate_2'];
                }else{
                    $data['accumulation_phase_interest_rate_2'] = '';
                }
                if ($input['distribution_phase_interest_rate_2'] != '') {
                    $data['distribution_phase_interest_rate_2'] = $input['distribution_phase_interest_rate_2'];
                }else{
                    $data['distribution_phase_interest_rate_2'] = '';
                }
                if (isset($input['clientname']) && $input['clientname'] != '' ) {
                    $data['clientname'] = $input['clientname'];
                }
                if ($input['current_age'] != '' && isset($input['current_age'])) {
                    $data['current_age'] = $input['current_age'];
                }
                if (isset($input['is_note']) && $input['is_note'] != '') {
                    $data['is_note'] = $input['is_note'];
                }else{
                    $data['is_note'] = 0;
                }
                if (isset($input['include_taxation']) && $input['include_taxation'] != '' && isset($input['include_taxation'])) {
                    $data['include_taxation'] = $input['include_taxation'];
                }
                if (isset($input['note']) && $input['note'] != '' && isset($input['note'])) {
                    $data['note'] = $input['note'];
                }else{
                    $data['note'] = '';
                }
                if (isset($input['client']) && $input['client'] != '' && isset($input['client'])) {
                    $data['client'] = $input['client'];
                }
                $data['scheme_type'] = $request->scheme_type;

                $data['scheme_amount'] = $request->scheme_amount;
                
                if (isset($input['category_list_input']) && isset($input['category_list_input'])){
                    $data['category_list_input'] = $input['category_list_input'];
                }
                if (isset($input['balance_required']) && $input['balance_required'] != '') {
                    $data['balance_required'] = $input['balance_required'];
                }else{
                    $data['balance_required'] = '';
                }
                if (isset($input['suggest']) && isset($input['suggest'])){
                    $data['suggest'] = $input['suggest'];

                    // Fund Performance //
                    $data['suggested_performance'] = $input['suggested_performance'];
                    session()->put('suggested_performance',$input['suggested_performance']);
                    $data['suggestedlist_type'] = $input['suggestedlist_type'];
                    if ($data['suggestedlist_type']=='customlist'){
                        $data['custom_list_input'] = $input['custom_list_input'];
                        $saveListDetails = FundPerformanceCreateList::where('id',$data['custom_list_input'])->first();
                        session()->forget('suggested_scheme_list');
                        $saveListDetails['schemecodes'] = json_decode($saveListDetails['schemecodes']);
                        if (isset($saveListDetails['schemecodes']) && count($saveListDetails['schemecodes'])) {
                            foreach ($saveListDetails['schemecodes'] as $inp) {
                                $asset_scheme_option = explode("_", $inp);
                                $scheme = $asset_scheme_option[1];
                                $lo_scheme_with_NAV = SchemecodeData::where('schemecode',$scheme)->first();
                                if (session()->has('suggested_scheme_list')) {
                                    $suggested_scheme_list = session()->get('suggested_scheme_list');
                                    array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                    session()->put('suggested_scheme_list',$suggested_scheme_list);
                                } else {
                                    $suggested_scheme_list = array();
                                    array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                    session()->put('suggested_scheme_list',$suggested_scheme_list);
                                }
                            }
                        }

                    }elseif ($data['suggestedlist_type']=='categorylist'){
                        $data['category_list_input'] = $input['category_list_input'];
                        $saveListDetails = FundPerformanceCreateCategoryList::where('id',$data['category_list_input'])->first();
                        session()->forget('suggested_scheme_list');
                        $saveListDetails['schemecodes'] = json_decode($saveListDetails['schemecode']);
                        if (isset($saveListDetails['schemecodes']) && count($saveListDetails['schemecodes'])) {
                            foreach ($saveListDetails['schemecodes'] as $inp) {
                                $scheme = $inp;
                                $lo_scheme_with_NAV = SchemecodeData::where('schemecode',$scheme)->first();
                                if (session()->has('suggested_scheme_list')) {
                                    $suggested_scheme_list = session()->get('suggested_scheme_list');
                                    array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                    session()->put('suggested_scheme_list',$suggested_scheme_list);
                                } else {
                                    $suggested_scheme_list = array();
                                    array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                    session()->put('suggested_scheme_list',$suggested_scheme_list);
                                }
                            }
                        }
                    }
                }

                

            }else{
                return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
            }
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'sip_amount' => 'required|numeric|between:100,9999999999',
                'sip_period' => 'required|numeric|between:1,99',
                'annuity_period' => 'required|numeric|between:1,99',
                'accumulation_phase_interest_rate_1' => 'required|numeric|between:0.1,18',
                'distribution_phase_interest_rate_1' => 'required|numeric|between:0.1,18',
                'accumulation_phase_interest_rate_2' => 'sometimes|nullable|numeric|between:0.1,18',
                'distribution_phase_interest_rate_2' => 'sometimes|nullable|numeric|between:0.1,18',
                'clientname' => 'sometimes|nullable|max:30',
                'balance_required' => 'sometimes|nullable|numeric|min:0',
            ]);

            $input = $request->all();

            if ($input['current_age'] != '' && isset($input['current_age'])) {
                $data['current_age'] = $input['current_age'];
            }
            
            $data['sip_amount'] = $input['sip_amount'];
            $data['sip_period'] = $input['sip_period'];
            $data['annuity_period'] = $input['annuity_period'];
            $data['accumulation_phase_interest_rate_1'] = $input['accumulation_phase_interest_rate_1'];
            $data['distribution_phase_interest_rate_1'] = $input['distribution_phase_interest_rate_1'];
            $data['report'] = $input['report'];
            if ($input['accumulation_phase_interest_rate_2'] != '') {
                $data['accumulation_phase_interest_rate_2'] = $input['accumulation_phase_interest_rate_2'];
            }else{
                $data['accumulation_phase_interest_rate_2'] = '';
            }
            if ($input['distribution_phase_interest_rate_2'] != '') {
                $data['distribution_phase_interest_rate_2'] = $input['distribution_phase_interest_rate_2'];
            }else{
                $data['distribution_phase_interest_rate_2'] = '';
            }
            $data['scheme_type'] = $request->scheme_type;

            $data['scheme_amount'] = $request->scheme_amount;
            
            if (isset($input['category_list_input']) && isset($input['category_list_input'])){
                $data['category_list_input'] = $input['category_list_input'];
            }

            $data['deferment'] = $input['deferment'];

            if (isset($input['deferment_period']) && $input['deferment_period'] != '') {
                $data['deferment_period'] = $input['deferment_period'];
                if($data['deferment'] == 'no'){
                    $data['deferment_period'] = '';
                }
            }

            $data['include_inflation'] = $input['include_inflation'];

            if (isset($input['expected_inflation_rate']) && $input['expected_inflation_rate'] != '') {
                $data['expected_inflation_rate'] = $input['expected_inflation_rate'];
            }

            if (isset($input['is_note']) && $input['is_note'] != '') {
                $data['is_note'] = $input['is_note'];
            }else{
                $data['is_note'] = 0;
            }
            if (isset($input['note']) && $input['note'] != '' && isset($input['note'])) {
                $data['note'] = $input['note'];
            }else{
                $data['note'] = '';
            }
            if (isset($input['client']) && $input['client'] != '' && isset($input['client'])) {
                $data['client'] = $input['client'];
            }
            if (isset($input['include_taxation']) && $input['include_taxation'] != '' && isset($input['include_taxation'])) {
                $data['include_taxation'] = $input['include_taxation'];
            }
            if (isset($input['assumed_inflation_rate_for_indexation']) && $input['assumed_inflation_rate_for_indexation'] != '') {
                $data['assumed_inflation_rate_for_indexation'] = $input['assumed_inflation_rate_for_indexation'];
            }

            if (isset($input['applicable_short_term_tax_rate']) && $input['applicable_short_term_tax_rate'] != '') {
                $data['applicable_short_term_tax_rate'] = $input['applicable_short_term_tax_rate'];
            }

            if (isset($input['for_period_upto']) && $input['for_period_upto'] != '') {
                $data['for_period_upto'] = $input['for_period_upto'];
            }
            if (isset($input['applicable_long_term_tax_rate']) && $input['applicable_long_term_tax_rate'] != '') {
                $data['applicable_long_term_tax_rate'] = $input['applicable_long_term_tax_rate'];
            }
            if (isset($input['from_the_year']) && $input['from_the_year'] != '') {
                $data['from_the_year'] = $input['from_the_year'];
            }

            if (isset($input['clientname']) && $input['clientname'] != '') {
                $data['clientname'] = $input['clientname'];
            }
            
            if (isset($input['suggest']) && isset($input['suggest'])){
                $data['suggest'] = $input['suggest'];
                $input['duration'] = isset($input['duration'])?$input['duration']:[];

                // Fund Performance Post //

                $data['suggested_performance'] = $input['include_performance'];
                session()->put('suggested_performance',$data['suggested_performance']);
                session()->forget('calculator_duration');
                session()->put('calculator_duration',$input['duration']);
                $data['suggestedlist_type'] = $input['suggestedlist_type'];
                if ($data['suggestedlist_type']=='customlist'){
                    $data['custom_list_input'] = $input['custom_list_input'];
                    $saveListDetails = FundPerformanceCreateList::where('id',$data['custom_list_input'])->first();
                    session()->forget('suggested_scheme_list');
                    $saveListDetails['schemecodes'] = json_decode($saveListDetails['schemecodes']);
                    if (isset($saveListDetails['schemecodes']) && count($saveListDetails['schemecodes'])) {
                        foreach ($saveListDetails['schemecodes'] as $inp) {
                            $asset_scheme_option = explode("_", $inp);
                            $scheme = $asset_scheme_option[1];
                            $lo_scheme_with_NAV = SchemecodeData::where('schemecode',$scheme)->first();
                            if (session()->has('suggested_scheme_list')) {
                                $suggested_scheme_list = session()->get('suggested_scheme_list');
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            } else {
                                $suggested_scheme_list = array();
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            }
                        }
                    }

                }elseif ($data['suggestedlist_type']=='categorylist'){
                    $data['category_list_input'] = $input['category_list_input'];
                    $saveListDetails = FundPerformanceCreateCategoryList::where('id',$data['category_list_input'])->first();
                    session()->forget('suggested_scheme_list');
                    $saveListDetails['schemecodes'] = json_decode($saveListDetails['schemecode']);
                    if (isset($saveListDetails['schemecodes']) && count($saveListDetails['schemecodes'])) {
                        foreach ($saveListDetails['schemecodes'] as $inp) {
                            $scheme = $inp;
                            $lo_scheme_with_NAV = SchemecodeData::where('schemecode',$scheme)->first();
                            if (session()->has('suggested_scheme_list')) {
                                $suggested_scheme_list = session()->get('suggested_scheme_list');
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            } else {
                                $suggested_scheme_list = array();
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            }
                        }
                    }
                }
            }
            if ($input['balance_required'] != '') {
                $data['balance_required'] = $input['balance_required'];
            }else{
                $data['balance_required'] = 0;
            }
            Session::put('monthly_annuity_for_sip_form_data', $data);
        }

        $ip_address = getIp();
        $scheme_count = 0;
        if(session()->has('suggested_scheme_list')){
            $scheme_count = count(session()->get('suggested_scheme_list'));
        }
    
        $history = History::create([
            'view_count' => 1,
            'user_id' => Auth::user()->id,
            'page_type' => "Calculator",
            'page_id' => 22,
            'scheme_count' => $scheme_count,
            'ip' => $ip_address
        ]);

        if(session()->has('suggested_scheme_list')){
            $suggested_scheme_list = session()->get('suggested_scheme_list');
            // dd($suggested_scheme_list);
            foreach ($suggested_scheme_list as $key => $value) {
                $insertData = [];
                $insertData['scheme_id'] = $value->Schemecode;
                $insertData['user_history_id'] = $history['id'];
                // dd($insertData);
                HistorySuggestedScheme::create($insertData);
            }
        }

        $category_detail = Calculator::where("url","calculators/swp-calculator/sip-required-for-target-monthly-annuity")->first();

        $calculator_permissions = DB::table("calculator_permissions")->where("package_id",Auth::user()->package_id)->where("calculator_id",$category_detail->id)->first();
        if($calculator_permissions){
            $data['calculator_permissions'] = [
               "is_view"=>$calculator_permissions->is_view,
               "is_download"=>$calculator_permissions->is_download,
               "is_cover"=>$calculator_permissions->is_cover,
               "is_save"=>$calculator_permissions->is_save
            ];
        }else{
             $data['calculator_permissions'] = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_cover"=>0,
                 "is_save"=>0
             ];
        }
        $edit_id = session()->get('monthly_annuity_for_sip_form_id');
        if(!empty($edit_id)){
            $calc = SaveCalculators::where('user_id',Auth::user()->id)->where('id',$edit_id)->first();
            $data['cal_name'] = $calc->title;
        }
        
        $calculators = DB::table("calculators")->where("id",22)->first();
        if($calculators){
            $package_id = Auth::user()->package_id;
            $permission = DB::table("calculator_permissions")->where("calculator_id",$calculators->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                   "is_view"=>$permission->is_view,
                   "is_download"=>$permission->is_download,
                   "is_cover"=>$permission->is_cover,
                   "is_save"=>$permission->is_save
                ];
            }else{
                 $data['permission'] = [
                     "is_view"=>1,
                     "is_download"=>0,
                     "is_cover"=>0,
                     "is_save"=>0
                 ];
            }
        }else{
            $data['permission'] = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_cover"=>0,
                 "is_save"=>0
            ];
        }
        $data['edit_id'] = session()->get('monthly_annuity_for_sip_form_id');

        return view('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip_output',$data);
    }

    public function monthlyAnnuityForSIPOutputSave(Request $request){
        $requestData = $request->all();
        if ($request->isMethod('get')) {
            if (Session::has('monthly_annuity_for_sip_form_data')) {
                $input = Session::get('monthly_annuity_for_sip_form_data');
                $data['sip_amount'] = $input['sip_amount'];
                $data['sip_period'] = $input['sip_period'];
                $data['annuity_period'] = $input['annuity_period'];
                $data['accumulation_phase_interest_rate_1'] = $input['accumulation_phase_interest_rate_1'];
                $data['distribution_phase_interest_rate_1'] = $input['distribution_phase_interest_rate_1'];
                $data['report'] = $input['report'];
                $data['scheme_amount'] = $input['scheme_amount'];
                $data['scheme_type'] = $input['scheme_type'];
                if ($input['current_age'] != '' && isset($input['current_age'])) {
                $data['current_age'] = $input['current_age'];
                }

                if ($input['accumulation_phase_interest_rate_2'] != '') {
                    $data['accumulation_phase_interest_rate_2'] = $input['accumulation_phase_interest_rate_2'];
                }else{
                    $data['accumulation_phase_interest_rate_2'] = '';
                }
                if ($input['distribution_phase_interest_rate_2'] != '') {
                    $data['distribution_phase_interest_rate_2'] = $input['distribution_phase_interest_rate_2'];
                }else{
                    $data['distribution_phase_interest_rate_2'] = '';
                }

                $data['deferment'] = $input['deferment'];

                if (isset($input['deferment_period']) && $input['deferment_period'] != '') {
                    $data['deferment_period'] = $input['deferment_period'];
                }

                $data['include_inflation'] = $input['include_inflation'];

                if (isset($input['expected_inflation_rate']) && $input['expected_inflation_rate'] != '') {
                    $data['expected_inflation_rate'] = $input['expected_inflation_rate'];
                }

                if (isset($input['include_taxation']) && $input['include_taxation'] != '' && isset($input['include_taxation'])) {
                    $data['include_taxation'] = $input['include_taxation'];
                }

                if (isset($input['assumed_inflation_rate_for_indexation']) && $input['assumed_inflation_rate_for_indexation'] != '') {
                    $data['assumed_inflation_rate_for_indexation'] = $input['assumed_inflation_rate_for_indexation'];
                }

                if (isset($input['applicable_short_term_tax_rate']) && $input['applicable_short_term_tax_rate'] != '') {
                    $data['applicable_short_term_tax_rate'] = $input['applicable_short_term_tax_rate'];
                }

                if (isset($input['for_period_upto']) && $input['for_period_upto'] != '') {
                    $data['for_period_upto'] = $input['for_period_upto'];
                }
                if (isset($input['applicable_long_term_tax_rate']) && $input['applicable_long_term_tax_rate'] != '') {
                    $data['applicable_long_term_tax_rate'] = $input['applicable_long_term_tax_rate'];
                }
                if (isset($input['from_the_year']) && $input['from_the_year'] != '') {
                    $data['from_the_year'] = $input['from_the_year'];
                }

                if (isset($input['clientname']) && $input['clientname'] != '' ) {
                    $data['clientname'] = $input['clientname'];
                }
                if (isset($input['suggest']) && isset($input['suggest'])){
                    $data['suggest'] = $input['suggest'];
                }

                if (isset($input['balance_required']) && $input['balance_required'] != '') {
                    $data['balance_required'] = $input['balance_required'];
                }else{
                    $data['balance_required'] = 0;
                }
                if (isset($input['is_note']) && $input['is_note'] != '') {
                    $data['is_note'] = $input['is_note'];
                }else{
                    $data['is_note'] = 0;
                }
                if (isset($input['note']) && $input['note'] != '' && isset($input['note'])) {
                    $data['note'] = $input['note'];
                }else{
                    $data['note'] = '';
                }
                if (isset($input['client']) && $input['client'] != '' && isset($input['client'])) {
                    $data['client'] = $input['client'];
                }
                if (isset($input['suggestedlist_type']) && isset($input['suggestedlist_type'])){
                    $data['suggestedlist_type'] = $input['suggestedlist_type'];
                }
                $data['pie_chart2'] = Session::get('pie_chart2');
                $savedData = $data;

                $savedData['suggested_performance'] = session()->get('suggested_performance');

                $savedData['suggested_scheme_list'] = session()->get('suggested_scheme_list');
                $savedData['calculator_duration'] = session()->get('calculator_duration');

                //Auth
                if (Auth::check()){
                    $user = Auth::user();
                    $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                    $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                    $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                    $data['name_color'] = $displayInfo->name_color;
                    $data['company_name_color'] = $displayInfo->company_name_color;
                    $data['city_color'] = $displayInfo->city_color;
                    $data['address_color_background'] = $displayInfo->address_color_background;
                    $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
                    $data['amfi_registered'] = $displayInfo->amfi_registered;
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
                }

                $view = (string)View::make('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip_output_pdf',$data);
                $edit_id = session()->get('monthly_annuity_for_sip_form_id');
                if($edit_id){
                    SaveCalculators::where('user_id',Auth::user()->id)->where('id',$edit_id)->update([
                        'title' => $requestData['title'],
                        'data' => serialize($savedData),
                    ]);
                    Storage::put('calculators/'.$edit_id.'.txt', $view);
                    session()->forget('monthly_annuity_for_sip_form_id');
                }else{
                    
                    $saveCal = SaveCalculators::create([
                        'title' => $requestData['title'],
                        'data' => serialize($savedData),
                        'calculator_id' => 22,
                        'user_id' => Auth::user()->id
                    ]);
                    Storage::put('calculators/'.$saveCal['id'].'.txt', $view);
                }

                $ip_address = getIp();
                $scheme_count = 0;
                if(session()->has('suggested_scheme_list')){
                    $scheme_count = count(session()->get('suggested_scheme_list'));
                }
            
                $history = History::create([
                    'save_count' => 1,
                    'user_id' => Auth::user()->id,
                    'page_type' => "Calculator",
                    'page_id' => 22,
                    'scheme_count' => $scheme_count,
                    'ip' => $ip_address
                ]);

                if(session()->has('suggested_scheme_list')){
                    $suggested_scheme_list = session()->get('suggested_scheme_list');
                    // dd($suggested_scheme_list);
                    foreach ($suggested_scheme_list as $key => $value) {
                        $insertData = [];
                        $insertData['scheme_id'] = $value->Schemecode;
                        $insertData['user_history_id'] = $history['id'];
                        // dd($insertData);
                        HistorySuggestedScheme::create($insertData);
                    }
                }
            }else{
                return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
            }
        }
    }


    public function monthlyAnnuityForSIPOutputDownloadPdf(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('monthly_annuity_for_sip_form_data')) {
                $input = Session::get('monthly_annuity_for_sip_form_data');

                $data['sip_amount'] = $input['sip_amount'];
                $data['sip_period'] = $input['sip_period'];
                $data['annuity_period'] = $input['annuity_period'];
                $data['accumulation_phase_interest_rate_1'] = $input['accumulation_phase_interest_rate_1'];
                $data['distribution_phase_interest_rate_1'] = $input['distribution_phase_interest_rate_1'];
                $data['report'] = $input['report'];
                $data['scheme_amount'] = $input['scheme_amount'];
                $data['scheme_type'] = $input['scheme_type'];
                if (isset($input['current_age']) && $input['current_age'] != '') {
                $data['current_age'] = $input['current_age'];
                }

                if ($input['accumulation_phase_interest_rate_2'] != '') {
                    $data['accumulation_phase_interest_rate_2'] = $input['accumulation_phase_interest_rate_2'];
                }else{
                    $data['accumulation_phase_interest_rate_2'] = '';
                }
                if ($input['distribution_phase_interest_rate_2'] != '') {
                    $data['distribution_phase_interest_rate_2'] = $input['distribution_phase_interest_rate_2'];
                }else{
                    $data['distribution_phase_interest_rate_2'] = '';
                }

                $data['deferment'] = $input['deferment'];

                if (isset($input['deferment_period']) && $input['deferment_period'] != '') {
                    $data['deferment_period'] = $input['deferment_period'];
                }

                $data['include_inflation'] = $input['include_inflation'];

                if (isset($input['expected_inflation_rate']) && $input['expected_inflation_rate'] != '') {
                    $data['expected_inflation_rate'] = $input['expected_inflation_rate'];
                }
                
                if (isset($input['include_taxation']) && $input['include_taxation'] != '' && isset($input['include_taxation'])) {
                    $data['include_taxation'] = $input['include_taxation'];
                }

                if (isset($input['assumed_inflation_rate_for_indexation']) && $input['assumed_inflation_rate_for_indexation'] != '') {
                    $data['assumed_inflation_rate_for_indexation'] = $input['assumed_inflation_rate_for_indexation'];
                }

                if (isset($input['applicable_short_term_tax_rate']) && $input['applicable_short_term_tax_rate'] != '') {
                    $data['applicable_short_term_tax_rate'] = $input['applicable_short_term_tax_rate'];
                }

                if (isset($input['for_period_upto']) && $input['for_period_upto'] != '') {
                    $data['for_period_upto'] = $input['for_period_upto'];
                }
                if (isset($input['applicable_long_term_tax_rate']) && $input['applicable_long_term_tax_rate'] != '') {
                    $data['applicable_long_term_tax_rate'] = $input['applicable_long_term_tax_rate'];
                }
                if (isset($input['from_the_year']) && $input['from_the_year'] != '') {
                    $data['from_the_year'] = $input['from_the_year'];
                }

                if (isset($input['clientname']) && $input['clientname'] != '' ) {
                    $data['clientname'] = $input['clientname'];
                }
                if (isset($input['suggest']) && isset($input['suggest'])){
                    $data['suggest'] = $input['suggest'];
                }

                if (isset($input['balance_required']) && $input['balance_required'] != '') {
                    $data['balance_required'] = $input['balance_required'];
                }else{
                    $data['balance_required'] = 0;
                }
                if (isset($input['is_note']) && $input['is_note'] != '') {
                    $data['is_note'] = $input['is_note'];
                }else{
                    $data['is_note'] = 0;
                }
                if (isset($input['note']) && $input['note'] != '' && isset($input['note'])) {
                    $data['note'] = $input['note'];
                }else{
                    $data['note'] = '';
                }
                if (isset($input['client']) && $input['client'] != '' && isset($input['client'])) {
                    $data['client'] = $input['client'];
                }
                $data['pie_chart2'] = Session::get('pie_chart2');
                $data['pdf_title_line1'] = $request->pdf_title_line1;
                $data['pdf_title_line2'] = $request->pdf_title_line2;
                $data['client_name'] = $request->client_name;
                //Auth
                if (Auth::check()){
                    $user = Auth::user();
                    $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                    $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                    $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                    $data['name_color'] = $displayInfo->name_color;
                    $data['company_name_color'] = $displayInfo->company_name_color;
                    $data['city_color'] = $displayInfo->city_color;
                    $data['address_color_background'] = $displayInfo->address_color_background;
                    $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
                    $data['amfi_registered'] = $displayInfo->amfi_registered;
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
                }

                $ip_address = getIp();
                $scheme_count = 0;
                if(session()->has('suggested_scheme_list')){
                    $scheme_count = count(session()->get('suggested_scheme_list'));
                }
            
                $history = History::create([
                    'download_count' => 1,
                    'user_id' => Auth::user()->id,
                    'page_type' => "Calculator",
                    'page_id' => 22,
                    'scheme_count' => $scheme_count,
                    'ip' => $ip_address
                ]);

                if(session()->has('suggested_scheme_list')){
                    $suggested_scheme_list = session()->get('suggested_scheme_list');
                    // dd($suggested_scheme_list);
                    foreach ($suggested_scheme_list as $key => $value) {
                        $insertData = [];
                        $insertData['scheme_id'] = $value->Schemecode;
                        $insertData['user_history_id'] = $history['id'];
                        // dd($insertData);
                        HistorySuggestedScheme::create($insertData);
                    }
                }

                $data['title'] = "Monthly SWP For SIP";

                $data['pie_chart2'] = Session::get('pie_chart2');

                $data['details'] = DB::table("calculators")->where("url","calculators/swp-calculator/sip-required-for-target-monthly-annuity")->first();

                if($data['pdf_title_line1']){
                    $oMerger = PDFMerger::init();
                    $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                    
                    $pdf = PDF::loadView('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip_output_pdf', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                    
                    $oMerger->merge();
                    $oMerger->setFileName($data['details']->name.'.pdf');
                    return $oMerger->download();
                }else{

                    $pdf = PDF::loadView('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip_output_pdf', $data);
                    return $pdf->download($data['details']->name.'.pdf');
                }


            }else{
                return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
            }
        }
    }

    public function edit(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
        }

        $saveCalculatorsData = unserialize($saveCalculators['data']);

        // dd($saveCalculatorsData);

        $data = [];
        $data['form_data'] = [];
        $data['form_data']['client'] = isset($saveCalculatorsData['client'])?$saveCalculatorsData['client']:0;
        $data['form_data']['report'] = isset($saveCalculatorsData['report'])?$saveCalculatorsData['report']:0;
        $data['form_data']['clientname'] = isset($saveCalculatorsData['clientname'])?$saveCalculatorsData['clientname']:"";
        $data['form_data']['current_age'] = isset($saveCalculatorsData['current_age'])?$saveCalculatorsData['current_age']:"";
        $data['form_data']['balance_required'] = isset($saveCalculatorsData['balance_required'])?$saveCalculatorsData['balance_required']:"";
        $data['form_data']['is_note'] = $saveCalculatorsData['is_note']?$saveCalculatorsData['is_note']:'';
        $data['form_data']['note'] = isset($saveCalculatorsData['note'])?$saveCalculatorsData['note']:'';
        $data['form_data']['sip_amount'] = isset($saveCalculatorsData['sip_amount'])?$saveCalculatorsData['sip_amount']:'';
        $data['form_data']['sip_period'] = isset($saveCalculatorsData['sip_period'])?$saveCalculatorsData['sip_period']:'';
        $data['form_data']['annuity_period'] = isset($saveCalculatorsData['annuity_period'])?$saveCalculatorsData['annuity_period']:'';
        $data['form_data']['accumulation_phase_interest_rate_1'] = isset($saveCalculatorsData['accumulation_phase_interest_rate_1'])?$saveCalculatorsData['accumulation_phase_interest_rate_1']:'';
        $data['form_data']['distribution_phase_interest_rate_1'] = isset($saveCalculatorsData['distribution_phase_interest_rate_1'])?$saveCalculatorsData['distribution_phase_interest_rate_1']:'';
        $data['form_data']['accumulation_phase_interest_rate_2'] = isset($saveCalculatorsData['accumulation_phase_interest_rate_2'])?$saveCalculatorsData['accumulation_phase_interest_rate_2']:'';
        $data['form_data']['distribution_phase_interest_rate_2'] = isset($saveCalculatorsData['distribution_phase_interest_rate_2'])?$saveCalculatorsData['distribution_phase_interest_rate_2']:'';
        $data['form_data']['deferment'] = isset($saveCalculatorsData['deferment'])?$saveCalculatorsData['deferment']:'';
        $data['form_data']['deferment_period'] = isset($saveCalculatorsData['deferment_period'])?$saveCalculatorsData['deferment_period']:'';
        $data['form_data']['include_inflation'] = isset($saveCalculatorsData['include_inflation'])?$saveCalculatorsData['include_inflation']:'';
        $data['form_data']['expected_inflation_rate'] = isset($saveCalculatorsData['expected_inflation_rate'])?$saveCalculatorsData['expected_inflation_rate']:'';
        $data['form_data']['include_taxation'] = isset($saveCalculatorsData['include_taxation'])?$saveCalculatorsData['include_taxation']:'';
        $data['form_data']['assumed_inflation_rate_for_indexation'] = isset($saveCalculatorsData['assumed_inflation_rate_for_indexation'])?$saveCalculatorsData['assumed_inflation_rate_for_indexation']:'';
        $data['form_data']['applicable_short_term_tax_rate'] = isset($saveCalculatorsData['applicable_short_term_tax_rate'])?$saveCalculatorsData['applicable_short_term_tax_rate']:'';
        $data['form_data']['for_period_upto'] = isset($saveCalculatorsData['for_period_upto'])?$saveCalculatorsData['for_period_upto']:'';
        $data['form_data']['applicable_long_term_tax_rate'] = isset($saveCalculatorsData['applicable_long_term_tax_rate'])?$saveCalculatorsData['applicable_long_term_tax_rate']:'';
        $data['form_data']['from_the_year'] = isset($saveCalculatorsData['from_the_year'])?$saveCalculatorsData['from_the_year']:'';
        
        $data['form_data']['suggest'] = "";
        $data['custom_list_input'] = "";
        $data['category_list_input'] = "";
        $data['suggestedlist_type'] = "";
        $data['scheme_type'] = [];
        $data['scheme_amount'] = []; 
        
        if(isset($saveCalculatorsData['suggest'])){
                    
            // $saveCalculatorsData['suggested_performance'] = session()->get('suggested_performance');

            // $saveCalculatorsData['suggested_scheme_list'] = session()->get('suggested_scheme_list');
            // $saveCalculatorsData['calculator_duration'] = session()->get('calculator_duration');
    
            $data['form_data']['suggest'] = $saveCalculatorsData['suggest'];
            if($saveCalculatorsData['suggest'] == 1){
                $data['suggested_performance'] = $saveCalculatorsData['suggested_performance'];
                $data['suggestedlist_type'] = $saveCalculatorsData['suggestedlist_type'];
                $data['calculator_duration'] = $saveCalculatorsData['calculator_duration'];
    
                $data['suggested_scheme_list'] = [];
    
                if($data['suggestedlist_type'] == "createlist"){
                    $data['scheme_type'] = $saveCalculatorsData['scheme_type'];
                    $data['scheme_amount'] = $saveCalculatorsData['scheme_amount'];
                    $data['suggested_scheme_list'] = $saveCalculatorsData['suggested_scheme_list'];
                    Session::put('suggested_scheme_list', $saveCalculatorsData['suggested_scheme_list']);
                }else if($data['suggestedlist_type'] == "customlist"){
                    $data['custom_list_input'] = $saveCalculatorsData['custom_list_input'];
                }else if($data['suggestedlist_type'] == "categorylist"){
                    $data['category_list_input'] = $saveCalculatorsData['category_list_input'];
                }            
                
                Session::put('suggested_performance', $saveCalculatorsData['suggested_performance']);
                Session::put('calculator_duration', $saveCalculatorsData['calculator_duration']);
            }else{
                $data['suggested_performance'] = "";
                $data['suggested_scheme_list'] = [];
                $data['calculator_duration'] = [];
            }
        }else{
            $data['suggested_performance'] = "";
            $data['suggested_scheme_list'] = [];
            $data['calculator_duration'] = [];
        }
            
        Session::put('calc_title', $saveCalculators->title);
        Session::put('monthly_annuity_for_sip_form_id', $request->id);
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','sip_required_for_target_monthly_swp')->first();
        // dd($data);
        return view('frontend.calculators.siprequiredfortargetmonthlyannuity.edit',$data);
    }

    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('monthly_annuity_for_sip_form_data', $data);
        Session::put('pie_chart2', $data['pie_chart2']);
        $data['edit_id'] = 0;
        $data['id'] = $request->id;
        $calculators = DB::table("calculators")->where("id",22)->first();
        if($calculators){
            $package_id = Auth::user()->package_id;
            $permission = DB::table("calculator_permissions")->where("calculator_id",$calculators->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                   "is_view"=>$permission->is_view,
                   "is_download"=>$permission->is_download,
                   "is_cover"=>$permission->is_cover,
                   "is_save"=>$permission->is_save
                ];
            }else{
                 $data['permission'] = [
                     "is_view"=>1,
                     "is_download"=>0,
                     "is_cover"=>0,
                     "is_save"=>0
                 ];
            }
        }else{
            $data['permission'] = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_cover"=>0,
                 "is_save"=>0
            ];
        }

        $data['savelists'] = Savelist::where('user_id',Auth::user()->id)->where('validate_at','>=',date('Y-m-d'))->orderBy('validate_at','desc')->get();
        $data['suggestedlists'] = Savelist::where('user_id',0)->orderBy('id','desc')->get();
        $data['details'] = DB::table("calculators")->where('url','calculators/swp-calculator/sip-required-for-target-monthly-annuity')->first();
        return view('frontend.calculators.siprequiredfortargetmonthlyannuity.view',$data);
    }
    
    public function merge_download(Request $request){

        $id = $request->save_file_id;

        $saveCalculators = SaveCalculators::where('id','=',$id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.monthlyTargetAnnuityForSIP');
        }

        $data = unserialize($saveCalculators['data']);
        $data['saved_sp_list_id'] = $request->saved_sp_list_id;
        $data['saved_list_id'] = $request->saved_list_id;
        $data['before_after'] = $request->mergeposition;
        $data['is_cover'] = $request->is_cover;
        $data['pdf_title_line1'] = $request->pdf_title_line1;
        $data['pdf_title_line2'] = $request->pdf_title_line2;
        $data['client_name'] = $request->client_name;
        $data['id'] = $id;
        
        if (Auth::check()){
            $user = Auth::user();
            $displayInfo = Displayinfo::where('user_id',$user->id)->first();
            $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
            $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
            $data['name_color'] = $displayInfo->name_color;
            $data['company_name_color'] = $displayInfo->company_name_color;
            $data['city_color'] = $displayInfo->city_color;
            $data['address_color_background'] = $displayInfo->address_color_background;
            $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
            $data['amfi_registered'] = $displayInfo->amfi_registered;
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
            $data['membership'] = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
            if (isset($data['membership']) && $data['membership'] > 0){
                $data['watermark'] = 0;
            }else{
                $data['watermark'] = 1;
            }
        }

        $data['title'] = "Lumsum Investment Required for Target Future Value";

        $data['details'] = DB::table("calculators")->where('url','calculators/swp-calculator/sip-required-for-target-monthly-annuity')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip_output_pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }

        if($data['saved_sp_list_id']){
            foreach ($data['saved_sp_list_id'] as $key => $value) {
                $softcopieslists = Savelistsoftcopy::where('savelist_id',$value)->orderBy('position','asc')->get();
    
                if (isset($softcopieslists) && count($softcopieslists)>0){
                    
                    $data1 = $data;
                    $data1['company'] = Auth::user();
                    $data1['name'] = $data['name'];
                    $data1['membership'] = $data['membership'];
                    $data1['displayInfo'] = $displayInfo;
                    $data1['getSoftCopyList'] = $softcopieslists;
                    $data1['type'] = 0;
                    $data1['pdf_title_line1'] = $data['pdf_title_line1'];
                    $data1['pdf_title_line2'] = $data['pdf_title_line2'];
                    $data1['client_name'] = $data['client_name'];
                    
                    // dd($data1);
                    $pdf = PDF::loadView('frontend.salespresenter.mysavelist_output_pdf', $data1);
                    $pdf->save(public_path('calculators/'.$user->id.'_salespresenter.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_salespresenter.pdf'), 'all');
                }
            }
        }

        if($data['saved_list_id']){
            $softcopieslists = Savelistsoftcopy::where('savelist_id',$data['saved_list_id'])->orderBy('position','asc')->get();

            if (isset($softcopieslists) && count($softcopieslists)>0){
                
                $data1 = $data;
                $data1['company'] = Auth::user();
                $data1['name'] = $data['name'];
                $data1['membership'] = $data['membership'];
                $data1['displayInfo'] = $displayInfo;
                $data1['getSoftCopyList'] = $softcopieslists;
                $data1['type'] = 0;
                $data1['pdf_title_line1'] = $data['pdf_title_line1'];
                $data1['pdf_title_line2'] = $data['pdf_title_line2'];
                $data1['client_name'] = $data['client_name'];
                
                $pdf = PDF::loadView('frontend.salespresenter.mysavelist_output_pdf', $data1);
                $pdf->save(public_path('calculators/'.$user->id.'_salespresenter.pdf'));
                $oMerger->addPDF(public_path('calculators/'.$user->id.'_salespresenter.pdf'), 'all');
            }
        }

        if ($data['before_after']=='before'){
            $pdf = PDF::loadView('frontend.calculators.siprequiredfortargetmonthlyannuity.monthly_annuity_for_sip_output_pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['details']->name.".pdf");
        return $oMerger->download();

    }


}