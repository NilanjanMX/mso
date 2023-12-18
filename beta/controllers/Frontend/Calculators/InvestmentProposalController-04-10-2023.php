<?php

namespace App\Http\Controllers\Frontend\Calculators;

use PaytmWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Session;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Models\SaveCalculators;
use App\Models\CalculatorHeading;
use App\Models\Displayinfo;
use App\Models\Membership;
use App\Models\UserHistory;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\FundPerformanceCreateList;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\SchemecodeData;
use App\Models\Savelistsoftcopy;
use App\Models\Calculator;
use App\Models\Savelist;
use DB;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class InvestmentProposalController extends Controller
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
    public function index(Request $request) {

        if($request->action == "back"){
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (Session::has('investment_proposal')) {
                $saveCalculatorsData = Session::get('investment_proposal');

                // dd($saveCalculatorsData);
                // dd(session()->get('calculator_duration'));
                $data = $saveCalculatorsData;

                
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

                
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','investment_proposal')->first();
                $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','mf_scanner.classcode',
                                    'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                            ])
                        ->orderBy('s_name','ASC')->get();


                $data['equity_scheme_list'] = DB::table("mf_scanner")
                                ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->select(['schemecode','s_name','mf_scanner.classcode',
                                            'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                    ])
                                ->where("asset_type","Equity")
                                ->orderBy('s_name','ASC')->get();

                $data['hybrid_scheme_list'] = DB::table("mf_scanner")
                                ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->select(['schemecode','s_name','mf_scanner.classcode',
                                            'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                    ])
                                ->where("asset_type","Hybrid")
                                ->orderBy('s_name','ASC')->get();

                $data['debt_scheme_list'] = DB::table("mf_scanner")
                                ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->select(['schemecode','s_name','mf_scanner.classcode',
                                            'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                    ])
                                ->where("asset_type","Debt")
                                ->orderBy('s_name','ASC')->get();

                $data['other_scheme_list'] = DB::table("mf_scanner")
                                ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->select(['schemecode','s_name','mf_scanner.classcode',
                                            'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                    ])
                                ->where("asset_type","Other")
                                ->orderBy('s_name','ASC')->get();
                                
                $data['category_list'] = DB::table("accord_sclass_mst")
                                ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();
                $data['assets_list'] = ["Equity","Hybrid","Debt","Other"];
                $data['product_list'] = DB::table("roei_products")->select(['id','name'])->orderBy('name','ASC')->get();
                $data['product_type_list'] = DB::table("roei_product_types")->select(['id','name'])->orderBy('name','ASC')->get();
                // dd($data);

                $data['details'] = DB::table("calculators")->where('url','premium-calculator/investment_proposal')->first();
                return view('frontend.calculators.investment_proposal.edit',$data);
                
            }else{
                return redirect()->route('frontend.investment_proposal');
            }
        }else{

            if (session()->has('suggested_scheme_list')){
                session()->forget('suggested_scheme_list');
            }
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (session()->has('calc_title')){
                session()->forget('calc_title');
            }
            
            $ip_address = getIp();
    
            History::create([
                'list_count' => 1,
                'user_id' => Auth::user()->id,
                'page_type' => "Calculator",
                'page_id' => 39,
                'ip' => $ip_address
            ]);

            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','investment_proposal')->first();

            $data['scheme_list'] = DB::table("mf_scanner")
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->select(['schemecode','s_name','mf_scanner.classcode',
                                        'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                ])
                            ->orderBy('s_name','ASC')->get();


            $data['equity_scheme_list'] = DB::table("mf_scanner")
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->select(['schemecode','s_name','mf_scanner.classcode',
                                        'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                ])
                            ->where("asset_type","Equity")
                            ->orderBy('s_name','ASC')->get();
                            
            $data['category_list'] = DB::table("accord_sclass_mst")
                            ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();
            $data['assets_list'] = ["Equity","Hybrid","Debt","Other"];
            $data['product_list'] = DB::table("roei_products")->select(['id','name'])->orderBy('name','ASC')->get();
            $data['product_type_list'] = DB::table("roei_product_types")->select(['id','name'])->orderBy('name','ASC')->get();

            
            $data['scheme_data_list'] =  [];


            return view('frontend.calculators.investment_proposal.index',$data);
        }
    }

    public function frequencyValue($value){
        $return = "";
        if($value == 52){
            $return = "Weekly";
        }else if($value == 26){
            $return = "Fortnightly";
        }else if($value == 12){
            $return = "Monthly";
        }else if($value == 4){
            $return = "Quarterly";
        }else if($value == 2){
            $return = "Half-Yearly";
        }else if($value == 1){
            $return = "Yearly";
        }
        return $return;
    }

    //Recover emis through spis
    public function output(Request $request){
        if ($request->isMethod('post')) {

            $input = $request->all();
            // dd($input);
            $data = [];

            $data['is_note'] = isset($input['is_note'])?$input['is_note']:"";
            $data['client'] = isset($input['client'])?$input['client']:'';
            $data['note'] = isset($input['note'])?$input['note']:'';
            $data['clientname'] = isset($input['clientname'])?$input['clientname']:"";
            $data['comment'] = isset($input['comment'])?$input['comment']:"";
            $data['performance_of_selected_mutual_fund'] = isset($input['performance_of_selected_mutual_fund'])?$input['performance_of_selected_mutual_fund']:"";

            $data['lumpsum_checkbox'] = isset($input['lumpsum_checkbox'])?$input['lumpsum_checkbox']:"";
            $data['sip_checkbox'] = isset($input['sip_checkbox'])?$input['sip_checkbox']:"";
            $data['stp_checkbox'] = isset($input['stp_checkbox'])?$input['stp_checkbox']:"";
            $data['swp_checkbox'] = isset($input['swp_checkbox'])?$input['swp_checkbox']:"";
            $data['non_mf_product_checkbox'] = isset($input['non_mf_product_checkbox'])?$input['non_mf_product_checkbox']:"";
            $data['insurance_product_checkbox'] = isset($input['insurance_product_checkbox'])?$input['insurance_product_checkbox']:"";

            $data['stp_investor_checkbox'] = isset($input['stp_investor_checkbox'])?$input['stp_investor_checkbox']:"";

            $data['sip_investor_checkbox'] = isset($input['sip_investor_checkbox'])?$input['sip_investor_checkbox']:"";
            $data['sip_category_checkbox'] = isset($input['sip_category_checkbox'])?$input['sip_category_checkbox']:"";
            
            $data['lumpsum_investor_checkbox'] = isset($input['lumpsum_investor_checkbox'])?$input['lumpsum_investor_checkbox']:"";
            $data['lumpsum_category_checkbox'] = isset($input['lumpsum_category_checkbox'])?$input['lumpsum_category_checkbox']:"";
            
            $data['swp_investor_checkbox'] = isset($input['swp_investor_checkbox'])?$input['swp_investor_checkbox']:"";
            $data['swp_category_checkbox'] = isset($input['swp_category_checkbox'])?$input['swp_category_checkbox']:"";

            
            $data['insurance_product_insured_name_checkbox'] = isset($input['insurance_product_insured_name_checkbox'])?$input['insurance_product_insured_name_checkbox']:"";
            $data['insurance_product_remark_checkbox'] = isset($input['insurance_product_remark_checkbox'])?$input['insurance_product_remark_checkbox']:"";
            $data['non_mf_product_investor_checkbox'] = isset($input['non_mf_product_investor_checkbox'])?$input['non_mf_product_investor_checkbox']:"";
            $data['non_mf_product_amount_checkbox'] = isset($input['non_mf_product_amount_checkbox'])?$input['non_mf_product_amount_checkbox']:"";
            $data['non_mf_product_remark_checkbox'] = isset($input['non_mf_product_remark_checkbox'])?$input['non_mf_product_remark_checkbox']:"";

            $data['schemecode_list'] = [];

            $data['stp_form_list'] = [];
            $data['stp_table_list'] = [];
            if($data['stp_checkbox']){
                $stp_initial_investment_amount = $request->stp_initial_investment_amount;
                $stp_from_scheme = $request->stp_from_scheme;
                $stp_to_scheme = $request->stp_to_scheme;
                $stp_transfer_mode = $request->stp_transfer_mode;
                $stp_frequency = $request->stp_frequency;
                $stp_no_of_frequency = $request->stp_no_of_frequency;
                $stp_investment_period = $request->stp_investment_period;
                $stp_stp_amount = $request->stp_stp_amount;
                $stp_expected_future_value = $request->stp_expected_future_value;
                $stp_investor = $request->stp_investor;
                $stp_schemecode_id = $request->stp_schemecode_id;
                $stp_schemecode_name = $request->stp_schemecode_name;
                $stp_investment = $request->stp_investment;
                $stp_equity_scheme = $request->stp_equity_scheme;
                $stp_equity_scheme_name = $request->stp_equity_scheme_name;

                foreach ($stp_initial_investment_amount as $key => $value) {
                    $insertData = [];
                    $insertData['initial_investment_amount'] = $value;
                    $insertData['from_scheme'] = $stp_from_scheme[$key];
                    $insertData['to_scheme'] = $stp_to_scheme[$key];
                    $insertData['transfer_mode'] = $stp_transfer_mode[$key];
                    $insertData['transfer_mode_value'] = $stp_transfer_mode[$key];
                    $insertData['frequency'] = $this->frequencyValue($stp_frequency[$key]);
                    $insertData['frequency_value'] = $stp_frequency[$key];
                    $insertData['no_of_frequency'] = $stp_no_of_frequency[$key];
                    $insertData['investment_period'] = $stp_investment_period[$key];
                    $insertData['stp_amount'] = $stp_stp_amount[$key];
                    $insertData['expected_future_value'] = $stp_expected_future_value[$key];
                    $insertData['table_list'] = [];

                    $table_list = [];
                    foreach ($stp_schemecode_id[$key] as $k1 => $v1) {
                        $table_list["investor"] = $stp_investor[$key][$k1];
                        $table_list["investment"] = $stp_investment[$key][$k1];

                        if(isset($stp_schemecode_id[$key][$k1]) && $stp_schemecode_id[$key][$k1]){
                            array_push($data['schemecode_list'], $stp_schemecode_id[$key][$k1]);
                            $scanner_detail = DB::table("mf_scanner")
                                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                    ->select(['schemecode','s_name','mf_scanner.classcode','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                        ])
                                    ->where('schemecode',$stp_schemecode_id[$key][$k1])->first();

                            $table_list["schemecode_name"] = $scanner_detail->s_name;
                            $table_list["category"] = ($scanner_detail->class_name)?$scanner_detail->class_name:$scanner_detail->classname;
                            $table_list["schemecode"] = $stp_equity_scheme[$key][$k1];
                            $table_list["classcode"] = $scanner_detail->classcode;
                        }else{
                            $table_list["schemecode_name"] = $stp_schemecode_name[$key][$k1];
                            $table_list["category"] = "";
                            $table_list["schemecode"] = "";
                            $table_list["classcode"] = "";
                        }
                        if(isset($stp_equity_scheme[$key][$k1]) && $stp_equity_scheme[$key][$k1]){
                            array_push($data['schemecode_list'], $stp_equity_scheme[$key][$k1]);
                            $scanner_detail = DB::table("mf_scanner")
                                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                    ->select(['schemecode','s_name','mf_scanner.classcode','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                        ])
                                    ->where('schemecode',$stp_equity_scheme[$key][$k1])->first();

                            $table_list["equity_schemecode_name"] = $scanner_detail->s_name;
                            $table_list["equity_category"] = ($scanner_detail->class_name)?$scanner_detail->class_name:$scanner_detail->classname;
                            $table_list["equity_schemecode"] = $stp_equity_scheme[$key][$k1];
                            $table_list["equity_classcode"] = $scanner_detail->classcode;
                        }else{
                            $table_list["equity_schemecode_name"] = $stp_equity_scheme_name[$key][$k1];
                            $table_list["equity_category"] = "";
                            $table_list["equity_schemecode"] = "";
                            $table_list["equity_classcode"] = "";
                        }
                        $data['stp_table_list'][] = $table_list;
                        $insertData['table_list'][] = $table_list;
                    }
                    $data['stp_form_list'][] = $insertData;
                }
            }

            $data['sip_form_list'] = [];
            $data['sip_table_list'] = [];
            if($data['sip_checkbox']){
                $sip_asset_class = $request->sip_asset_class;
                $sip_sip_amount = $request->sip_sip_amount;
                $sip_frequency = $request->sip_frequency;
                $sip_assumed_rate_of_return = $request->sip_assumed_rate_of_return;
                $sip_sip_period = $request->sip_sip_period;
                $sip_investment_period = $request->sip_investment_period;
                $sip_total_investment = $request->sip_total_investment;
                $sip_expected_future_value = $request->sip_expected_future_value;
                $sip_investor_checkbox = $request->sip_investor_checkbox;
                $sip_category_checkbox = $request->sip_category_checkbox;
                $sip_investor = $request->sip_investor;
                $sip_schemecode_id = $request->sip_schemecode_id;
                $sip_schemecode_name = $request->sip_schemecode_name;
                $sip_category_input = $request->sip_category_input;
                $sip_amounts = $request->sip_amounts;

                foreach ($sip_asset_class as $key => $value) {
                    $insertData = [];
                    $insertData['asset_class'] = $value;
                    $insertData['sip_amount'] = $sip_sip_amount[$key];
                    $insertData['frequency'] = $this->frequencyValue($sip_frequency[$key]);
                    $insertData['frequency_value'] = $sip_frequency[$key];
                    $insertData['assumed_rate_of_return'] = $sip_assumed_rate_of_return[$key];
                    $insertData['sip_period'] = $sip_sip_period[$key];
                    $insertData['investment_period'] = $sip_investment_period[$key];
                    $insertData['total_investment'] = $sip_total_investment[$key];
                    $insertData['expected_future_value'] = $sip_expected_future_value[$key];
                    $insertData['table_list'] = [];

                    $table_list = [];
                    foreach ($sip_amounts[$key] as $k1 => $v1) {
                        $table_list["investor"] = $sip_investor[$key][$k1];
                        $table_list["amount"] = $sip_amounts[$key][$k1];

                        if(isset($sip_schemecode_id[$key][$k1]) && $sip_schemecode_id[$key][$k1]){

                            array_push($data['schemecode_list'], $sip_schemecode_id[$key][$k1]);

                            $scanner_detail = DB::table("mf_scanner")
                                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                    ->select(['schemecode','s_name','mf_scanner.classcode','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                        ])
                                    ->where('schemecode',$sip_schemecode_id[$key][$k1])->first();

                            $table_list["schemecode_name"] = $scanner_detail->s_name;
                            $table_list["schemecode"] = $sip_schemecode_id[$key][$k1];
                            $table_list["classcode"] = $scanner_detail->classcode;
                            $table_list["category"] = ($scanner_detail->class_name)?$scanner_detail->class_name:$scanner_detail->classname;
                        }else{
                            $table_list["schemecode_name"] = $sip_schemecode_name[$key][$k1];
                            $table_list["category"] = $sip_category_input[$key][$k1];
                            $table_list["schemecode"] = "";
                            $table_list["classcode"] = "";
                        }
                        $data['sip_table_list'][] = $table_list;
                        $insertData['table_list'][] = $table_list;
                    }
                    $data['sip_form_list'][] = $insertData;
                }
            }

            $data['lumpsum_form_list'] = [];
            $data['lumpsum_table_list'] = [];
            if($data['lumpsum_checkbox']){
                $lumpsum_asset_class = $request->lumpsum_asset_class;
                $lumpsum_investment_amount = $request->lumpsum_investment_amount;
                $lumpsum_investment_period = $request->lumpsum_investment_period;
                $lumpsum_assumed_rate_of_return = $request->lumpsum_assumed_rate_of_return;
                $lumpsum_actual_end_value = $request->lumpsum_actual_end_value;
                $lumpsum_investor = $request->lumpsum_investor;
                $lumpsum_schemecode_id = $request->lumpsum_schemecode_id;
                $lumpsum_schemecode_name = $request->lumpsum_schemecode_name;
                $lumpsum_category_input = $request->lumpsum_category_input;
                $lumpsum_amount = $request->lumpsum_amount;

                foreach ($lumpsum_asset_class as $key => $value) {
                    $insertData = [];
                    $insertData['asset_class'] = $value;
                    $insertData['investment_amount'] = $lumpsum_investment_amount[$key];
                    $insertData['investment_period'] = $lumpsum_investment_period[$key];
                    $insertData['assumed_rate_of_return'] = $lumpsum_assumed_rate_of_return[$key];
                    $insertData['actual_end_value'] = $lumpsum_actual_end_value[$key];
                    $insertData['table_list'] = [];
                    $table_list = [];
                    foreach ($lumpsum_schemecode_id[$key] as $k1 => $v1) {
                        $table_list["investor"] = $lumpsum_investor[$key][$k1];
                        $table_list["amount"] = $lumpsum_amount[$key][$k1];

                        if(isset($lumpsum_schemecode_id[$key][$k1]) && $lumpsum_schemecode_id[$key][$k1]){
                            array_push($data['schemecode_list'], $lumpsum_schemecode_id[$key][$k1]);
                            $scanner_detail = DB::table("mf_scanner")
                                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                    ->select(['schemecode','s_name','mf_scanner.classcode','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                        ])
                                    ->where('schemecode',$lumpsum_schemecode_id[$key][$k1])->first();

                            $table_list["schemecode"] = $lumpsum_schemecode_id[$key][$k1];
                            $table_list["classcode"] = $scanner_detail->classcode;
                            $table_list["schemecode_name"] = $scanner_detail->s_name;
                            $table_list["category"] = ($scanner_detail->class_name)?$scanner_detail->class_name:$scanner_detail->classname;
                        }else{
                            $table_list["schemecode_name"] = $lumpsum_schemecode_name[$key][$k1];
                            $table_list["schemecode"] = "";
                            $table_list["classcode"] = "";
                            $table_list["category"] = $lumpsum_category_input[$key][$k1];
                        }
                        $data['lumpsum_table_list'][] = $table_list;
                        $insertData['table_list'][] = $table_list;
                    }
                    $data['lumpsum_form_list'][] = $insertData;
                }
            }

            $data['swp_form_list'] = [];
            $data['swp_table_list'] = [];
            if($data['swp_checkbox']){
                $swp_total_investment_amount = $request->swp_total_investment_amount;
                $swp_assumed_rate_of_return = $request->swp_assumed_rate_of_return;
                $swp_frequency = $request->swp_frequency;
                $swp_period_year = $request->swp_period_year;
                $swp_period_month = $request->swp_period_month;
                $swp_required_end_value = $request->swp_required_end_value;
                $swp_in_amount = $request->swp_in_amount;
                $swp_in_amount_hide = $request->swp_in_amount_hide;
                $swp_in_percent = $request->swp_in_percent;
                $swp_type_amount = $request->swp_type_amount;
                $swp_actual_end_value = $request->swp_actual_end_value;
                $swp_investor = $request->swp_investor;
                $swp_schemecode_id = $request->swp_schemecode_id;
                $swp_schemecode_name = $request->swp_schemecode_name;
                $swp_category_input = $request->swp_category_input;
                $swp_amount = $request->swp_amount;
                foreach ($swp_total_investment_amount as $key => $value) {
                    
                    $insertData = [];
                    $insertData['total_investment_amount'] = $value;
                    $insertData['assumed_rate_of_return'] = $swp_assumed_rate_of_return[$key];
                    $insertData['frequency'] = $swp_frequency[$key];
                    $insertData['period_year'] = $swp_period_year[$key];
                    $insertData['period_month'] = $swp_period_month[$key];
                    $insertData['required_end_value'] = $swp_required_end_value[$key];
                    $insertData['in_amount'] = $swp_in_amount[$key];
                    $insertData['in_amount_hide'] = $swp_in_amount_hide[$key];
                    $insertData['in_percent'] = $swp_in_percent[$key];
                    $insertData['type_amount'] = $swp_type_amount[$key];
                    $insertData['actual_end_value'] = $swp_actual_end_value[$key];
                    $insertData['table_list'] = [];
                    $table_list = [];
                    foreach ($swp_schemecode_id[$key] as $k1 => $v1) {
                        $table_list["investor"] = $swp_investor[$key][$k1];
                        $table_list["amount"] = $swp_amount[$key][$k1];

                        if(isset($swp_schemecode_id[$key][$k1]) && $swp_schemecode_id[$key][$k1]){
                            array_push($data['schemecode_list'], $swp_schemecode_id[$key][$k1]);
                            $scanner_detail = DB::table("mf_scanner")
                                    ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                    ->select(['schemecode','s_name','mf_scanner.classcode','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                                        ])
                                    ->where('schemecode',$swp_schemecode_id[$key][$k1])->first();

                            $table_list["schemecode"] = $swp_schemecode_id[$key][$k1];
                            $table_list["classcode"] = $scanner_detail->classcode;
                            $table_list["schemecode_name"] = $scanner_detail->s_name;
                            $table_list["category"] = ($scanner_detail->class_name)?$scanner_detail->class_name:$scanner_detail->classname;
                        }else{
                            $table_list["schemecode_name"] = $swp_schemecode_name[$key][$k1];
                            $table_list["schemecode"] = "";
                            $table_list["classcode"] = "";
                            $table_list["category"] = $swp_category_input[$key][$k1];
                        }
                        $data['swp_table_list'][] = $table_list;
                        $insertData['table_list'][] = $table_list;
                    }
                    $data['swp_form_list'][] = $insertData;
                }
            }

            $data['non_mf_product_list'] = [];
            if($data['non_mf_product_checkbox']){
                $data['non_mf_product_investor_checkbox'] = isset($input['non_mf_product_investor_checkbox'])?$input['non_mf_product_investor_checkbox']:"";
                $data['non_mf_product_amount_checkbox'] = isset($input['non_mf_product_amount_checkbox'])?$input['non_mf_product_amount_checkbox']:"";
                $data['non_mf_product_remark_checkbox'] = isset($input['non_mf_product_remark_checkbox'])?$input['non_mf_product_remark_checkbox']:"";
                foreach ($input['non_mf_product_inverstor'] as $key => $value) {
                    $product_id = "";
                    if(isset($input['non_mf_product_id'][$key])){
                        $product_type_list = DB::table("roei_products")->select(['id','name'])->where('id',$input['non_mf_product_id'][$key])->first();
                        if($product_type_list){
                            $product_type_list = $product_type_list->name;
                        }else{
                            $product_type_list = isset($input['product_name'][$key])?$input['product_name'][$key]:"";
                        }
                        $product_id = $input['non_mf_product_id'][$key];
                    }else{
                        $product_type_list = isset($input['product_name'][$key])?$input['product_name'][$key]:"";
                    }
                    
                    $insertData = [];
                    $insertData['inverstor'] = $value;
                    $insertData['product_id'] = $product_id;
                    $insertData['product_name'] = $product_type_list;
                    $insertData['company'] = isset($input['non_mf_product_company'][$key])?$input['non_mf_product_company'][$key]:"";
                    $insertData['amount'] = isset($input['non_mf_product_amount'][$key])?$input['non_mf_product_amount'][$key]:"";
                    $insertData['remark'] = isset($input['non_mf_product_remark'][$key])?$input['non_mf_product_remark'][$key]:"";
                    $insertData['attach'] = isset($input['non_mf_product_attach'][$key])?$input['non_mf_product_attach'][$key]:"";
                    array_push($data['non_mf_product_list'], $insertData);
                }
            }

            $data['insurance_product_list'] = [];
            if($data['insurance_product_checkbox']){
                $data['insurance_product_insured_name_checkbox'] = isset($input['insurance_product_insured_name_checkbox'])?$input['insurance_product_insured_name_checkbox']:"";
                $data['insurance_product_remark_checkbox'] = isset($input['insurance_product_remark_checkbox'])?$input['insurance_product_remark_checkbox']:"";
                foreach ($input['insurance_product_investor'] as $key => $value) {
                    $product_type_id = "";
                    if(isset($input['insurance_product_type_id'][$key])){
                        $product_type_list = DB::table("roei_product_types")->select(['id','name'])->where('id',$input['insurance_product_type_id'][$key])->first();
                        if($product_type_list){
                            $product_type_list = $product_type_list->name;
                        }else{
                            $product_type_list = isset($input['product_type_name'][$key])?$input['product_type_name'][$key]:"";
                        }
                        $product_type_id = $input['insurance_product_type_id'][$key];
                    }else{
                        $product_type_list = isset($input['product_type_name'][$key])?$input['product_type_name'][$key]:"";
                    }
                    
                    $insertData = [];
                    $insertData['inverstor'] = $value;
                    $insertData['product_type_id'] = $product_type_id;
                    $insertData['product_type_name'] = $product_type_list;
                    $insertData['company'] = isset($input['insurance_product_company'][$key])?$input['insurance_product_company'][$key]:"";
                    $insertData['sum_assured'] = isset($input['insurance_product_sum_assured'][$key])?$input['insurance_product_sum_assured'][$key]:"";
                    $insertData['annual_premium'] = isset($input['insurance_product_annual_premium'][$key])?$input['insurance_product_annual_premium'][$key]:"";
                    $insertData['remark'] = isset($input['insurance_product_remark'][$key])?$input['insurance_product_remark'][$key]:"";
                    
                    // dd($input);
                    array_push($data['insurance_product_list'], $insertData);
                }
            }

            $data['scheme_data_list'] = [];

            // if($data['performance_of_selected_mutual_fund']){
            //     $data['scheme_data_list'] = SchemecodeData::whereIn("schemecode",$data['schemecode_list'])->get();
            //     foreach ($data['scheme_data_list'] as $key => $value) {
            //         $data['scheme_data_list'][$key] = (array) json_decode($value->data);
            //     }

            //     $unsortedData = collect($data['scheme_data_list']);
            //     $data['scheme_data_list'] = $unsortedData->sortBy('S_NAME');

            //     $unsortedData = collect($data['scheme_data_list']);
            //     $data['scheme_data_list'] = $unsortedData->sortBy('CATEGORY');

            //     $unsortedData = collect($data['scheme_data_list']);
            //     $data['scheme_data_list'] = $unsortedData->sortBy('ASSET_TYPE');
            // }


            $data['scheme_type'] = $request->scheme_type;
            $data['scheme_amount'] = $request->scheme_amount;
            $data['suggest'] = $request->suggest;
            $data['report'] = $request->report;
            $data['calculator_duration'] = $request->duration;

            if (isset($input['suggest']) && isset($input['suggest'])){
                // $data['suggest'] = $input['suggest'];
                $input['duration'] = isset($input['duration'])?$input['duration']:[];

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

            $calculators = DB::table("calculators")->where("url","premium-calculator/investment_proposal")->first();
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
            $data['edit_id'] = session()->get('calculator_form_id');

            Session::put("investment_proposal",$data);
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
            'page_id' => 39,
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

        $category_detail = Calculator::where("url","premium-calculator/investment_proposal")->first();

        $calculator_permissions = DB::table("calculator_permissions")->where("package_id",Auth::user()->package_id)->where("calculator_id",$category_detail->id)->first();
        if($calculator_permissions){
            $data['calculator_permissions']['is_view'] = ($calculator_permissions->is_view)?true:false;
            $data['calculator_permissions']['is_download'] = ($calculator_permissions->is_download)?true:false;
            $data['calculator_permissions']['is_save'] = ($calculator_permissions->is_save)?true:false;
        }else{
            $data['calculator_permissions']['is_view'] = false;
            $data['calculator_permissions']['is_download'] = false;
            $data['calculator_permissions']['is_save'] = false;
        }
        
        return view('frontend.calculators.investment_proposal.output',$data);
    }
    
    public function save(Request $request){
        $requestData = $request->all();
        if(Session::has("investment_proposal")){
            $data = Session::get("investment_proposal");
            $savedData = $data;
            $savedData['suggested_performance'] = session()->get('suggested_performance');

            $savedData['suggested_scheme_list'] = session()->get('suggested_scheme_list');
            $savedData['calculator_duration'] = session()->get('calculator_duration');
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
            $view = (string)View::make('frontend.calculators.investment_proposal.pdf',$data);
            
            if(Auth::check()){

                $edit_id = session()->get('calculator_form_id');
                if($edit_id){
                    SaveCalculators::where('user_id',Auth::user()->id)->where('id',$edit_id)->update([
                        'title' => $requestData['title'],
                        'data' => serialize($savedData),
                    ]);
                    Storage::put('calculators/'.$edit_id.'.txt', $view);
                }else{
                    $saveCal = SaveCalculators::create([
                        'title' => $requestData['title'],
                        'data' => serialize($savedData),
                        'calculator_id' => 39,
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
                    'page_id' => 39,
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
                return response("File saved successfully",200);
            }
        }
    }
    
    public function pdf(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('investment_proposal')) {

                $data = Session::get('investment_proposal');

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

                $data['pdf_title_line1'] = $request->pdf_title_line1;
                $data['pdf_title_line2'] = $request->pdf_title_line2;
                $data['client_name'] = $request->client_name;

                $ip_address = getIp();
                $scheme_count = 0;
                if(session()->has('suggested_scheme_list')){
                    $scheme_count = count(session()->get('suggested_scheme_list'));
                }
            
                $history = History::create([
                    'download_count' => 1,
                    'user_id' => Auth::user()->id,
                    'page_type' => "Calculator",
                    'page_id' => 39,
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

                $data['details'] = DB::table("calculators")->where('url','premium-calculator/investment_proposal')->first();

                if($data['pdf_title_line1']){
                    $oMerger = PDFMerger::init();
                    $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                    
                    $pdf = PDF::loadView('frontend.calculators.investment_proposal.pdf', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                    
                    $oMerger->merge();
                    $oMerger->setFileName($data['details']->name.'.pdf');
                    return $oMerger->download();
                }else{

                    $pdf = PDF::loadView('frontend.calculators.investment_proposal.pdf', $data);
                    return $pdf->download($data['details']->name.'.pdf');
                }
            }
        }
    }

    public function edit(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.goal_calculator');
        }

        $data = unserialize($saveCalculators['data']);
        
        $form_data = $data;

        Session::put('calc_title', $saveCalculators->title);
        Session::put('calculator_form_id', $request->id);
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','investment_proposal')->first();

        $data['client'] = isset($data['client'])?$data['client']:'';
        $data['client'] = isset($data['client'])?$data['client']:'';
        $data['custom_list_input'] = "";
        $data['custom_list_input'] = "";
        $data['category_list_input'] = "";
        $data['suggestedlist_type'] = "";
        $data['scheme_type'] = [];
        $data['scheme_amount'] = [];
        
        if(isset($data['suggest'])){
            $data['form_data']['suggest'] = $form_data['suggest'];
            if($data['suggest'] == 1){
                $data['suggested_performance'] = $form_data['suggested_performance'];
                $data['suggestedlist_type'] = $form_data['suggestedlist_type'];
                $data['calculator_duration'] = $form_data['calculator_duration'];
    
                $data['suggested_scheme_list'] = [];
    
                if($data['suggestedlist_type'] == "createlist"){
                    $data['scheme_type'] = $form_data['scheme_type'];
                    $data['scheme_amount'] = $form_data['scheme_amount'];
                    $data['suggested_scheme_list'] = $form_data['suggested_scheme_list'];
                    Session::put('suggested_scheme_list', $data['suggested_scheme_list']);
                }else if($data['suggestedlist_type'] == "customlist"){
                    $data['custom_list_input'] = $form_data['custom_list_input'];
                }else if($data['suggestedlist_type'] == "categorylist"){
                    $data['category_list_input'] = $form_data['category_list_input'];
                }            
                
                Session::put('suggested_performance', $data['suggested_performance']);
                Session::put('calculator_duration', $data['calculator_duration']);
            }else{
                $data['suggested_performance'] = "";
                $data['suggest'] = "";
                $data['suggested_scheme_list'] = [];
                $data['calculator_duration'] = [];
            }
        }else{
            $data['suggested_performance'] = "";
            $data['suggest'] = "";
            $data['suggested_scheme_list'] = [];
            $data['calculator_duration'] = [];
        }

        $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','mf_scanner.classcode',
                                    'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                            ])
                        ->orderBy('s_name','ASC')->get();


        $data['equity_scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','mf_scanner.classcode',
                                    'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                            ])
                        ->where("asset_type","Equity")
                        ->orderBy('s_name','ASC')->get();

        $data['hybrid_scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','mf_scanner.classcode',
                                    'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                            ])
                        ->where("asset_type","Hybrid")
                        ->orderBy('s_name','ASC')->get();

        $data['debt_scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','mf_scanner.classcode',
                                    'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                            ])
                        ->where("asset_type","Debt")
                        ->orderBy('s_name','ASC')->get();

        $data['other_scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','mf_scanner.classcode',
                                    'accord_sclass_mst.asset_type','accord_sclass_mst.classname','mf_scanner_classcode.name as class_name'
                            ])
                        ->where("asset_type","Other")
                        ->orderBy('s_name','ASC')->get();
                        
        $data['category_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();
        $data['assets_list'] = ["Equity","Hybrid","Debt","Other"];
        $data['product_list'] = DB::table("roei_products")->select(['id','name'])->orderBy('name','ASC')->get();
        $data['product_type_list'] = DB::table("roei_product_types")->select(['id','name'])->orderBy('name','ASC')->get();
        // $data['scheme_data_list'] =  [];
        // dd($data);

        $data['details'] = DB::table("calculators")->where('url','premium-calculator/investment_proposal')->first();
        return view('frontend.calculators.investment_proposal.edit',$data);
    }
    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.investment_proposal');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('investment_proposal', $data);

        $form_data = $data;
        $data['form_data'] = [];

        if(isset($data['suggest'])){
            $data['form_data']['suggest'] = $form_data['suggest'];
            if($data['suggest'] == 1){
                $data['suggested_performance'] = $form_data['suggested_performance'];
                $data['suggestedlist_type'] = $form_data['suggestedlist_type'];
                $data['calculator_duration'] = $form_data['calculator_duration'];
    
                $data['suggested_scheme_list'] = [];
    
                if($data['suggestedlist_type'] == "createlist"){
                    $data['scheme_type'] = $form_data['scheme_type'];
                    $data['scheme_amount'] = $form_data['scheme_amount'];
                    $data['suggested_scheme_list'] = $form_data['suggested_scheme_list'];
                    Session::put('suggested_scheme_list', $data['suggested_scheme_list']);
                }else if($data['suggestedlist_type'] == "customlist"){
                    $data['custom_list_input'] = $form_data['custom_list_input'];
                }else if($data['suggestedlist_type'] == "categorylist"){
                    $data['category_list_input'] = $form_data['category_list_input'];
                }            
                
                Session::put('suggested_performance', $data['suggested_performance']);
                Session::put('calculator_duration', $data['calculator_duration']);
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
        // dd($data);

        $data['edit_id'] = 0;
        $data['id'] = $request->id;
        $calculators = DB::table("calculators")->where("id",40)->first();
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
        $data['details'] = DB::table("calculators")->where('url','premium-calculator/investment_proposal')->first();

        // dd($data);
        return view('frontend.calculators.investment_proposal.view',$data);
    }
    
    public function merge_download(Request $request){

        $id = $request->save_file_id;

        $saveCalculators = SaveCalculators::where('id','=',$id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.review_of_existing_investment');
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

        $data['details'] = DB::table("calculators")->where('url','premium-calculator/investment_proposal')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.investment_proposal.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }

        foreach ($data['saved_sp_list_id'] as $key => $value) {
            $softcopieslists = Savelistsoftcopy::where('savelist_id',$value)->orderBy('position','asc')->get();

            if (isset($softcopieslists) && count($softcopieslists)>0){
                
                $data1['company'] = Auth::user();
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

        if($data['saved_list_id']){
            $softcopieslists = Savelistsoftcopy::where('savelist_id',$data['saved_list_id'])->orderBy('position','asc')->get();

            if (isset($softcopieslists) && count($softcopieslists)>0){
                
                $data1['company'] = Auth::user();
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
            $pdf = PDF::loadView('frontend.calculators.investment_proposal.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['details']->name.".pdf");
        return $oMerger->download();

    }

}

?>