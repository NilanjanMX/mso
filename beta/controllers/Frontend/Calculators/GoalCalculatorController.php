<?php

namespace App\Http\Controllers\Frontend\Calculators;

use PaytmWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
use App\Models\Savelistsoftcopy;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\Calculator;
use App\Models\FundPerformanceCreateList;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\SchemecodeData;
use App\Models\Savelist;
use DB;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class GoalCalculatorController extends Controller
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
    public function index(Request $request)
    {

        if($request->action == "back"){
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (Session::has('goal_calculator')) {
                $saveCalculatorsData = Session::get('goal_calculator');

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

                
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','goal_calculator')->first();
                $data['details'] = DB::table("calculators")->where('url','premium-calculator/goal_calculator')->first();
                return view('frontend.calculators.goal_calculator.edit',$data);
                
            }else{
                return redirect()->route('frontend.goal_calculator');
            }
        }else{

            if (session()->has('suggested_scheme_list')){
                session()->forget('suggested_scheme_list');
            }
            if (session()->has('goal_calculator')){
                session()->forget('goal_calculator');
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
                'page_id' => 40,
                'ip' => $ip_address
            ]);
            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','goal_calculator')->first();
            $data['details'] = DB::table("calculators")->where('url','premium-calculator/goal_calculator')->first();
            return view('frontend.calculators.goal_calculator.index',$data);
        }
    }

    public function output(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('goal_calculator')) {
                $input = Session::get('goal_calculator');
                $data = $input;
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
                return redirect()->route('frontend.goal_calculator');
            }
        }

        if ($request->isMethod('post')) {

            $input = $request->all();
            // dd($input);
            $cost_type = [];
            foreach ($input['cost_type'] as $key => $value) {
                array_push($cost_type, $value);
            }
            $lumpsum_monthly_sip = [];
            foreach ($input['lumpsum_monthly_sip'] as $key => $value) {
                array_push($lumpsum_monthly_sip, $value);
            }
            $data_array = [];
            foreach ($input['purpose_of_investment'] as $key => $value) {
                $data = [];
                $data['purpose_of_investment'] = ($value)?$value:"";
                $data['current_age'] = ($input['current_age'][$key])?$input['current_age'][$key]:0;
                $data['cost_type'] = ($cost_type[$key])?$cost_type[$key]:0;
                $data['amount'] = ($input['amount'][$key])?$input['amount'][$key]:0;
                $data['inflation'] = ($input['inflation'][$key])?$input['inflation'][$key]:0;
                $data['period'] = ($input['period'][$key])?$input['period'][$key]:0;
                $data['aror_debt'] = ($input['aror_debt'][$key])?$input['aror_debt'][$key]:0;
                $data['aror_hybrid'] = ($input['aror_hybrid'][$key])?$input['aror_hybrid'][$key]:0;
                $data['aror_equity'] = ($input['aror_equity'][$key])?$input['aror_equity'][$key]:0;

                $data['lumpsum_investment_mode'] = isset($input['lumpsum_investment_mode'][$key])?$input['lumpsum_investment_mode'][$key]:0;
                $data['lumpsum_debt'] = ($input['lumpsum_debt'][$key])?$input['lumpsum_debt'][$key]:0;
                $data['lumpsum_hybrid'] = ($input['lumpsum_hybrid'][$key])?$input['lumpsum_hybrid'][$key]:0;
                $data['lumpsum_equity'] = ($input['lumpsum_equity'][$key])?$input['lumpsum_equity'][$key]:0;

                $data['monthly_sip_investment_mode'] = isset($input['monthly_sip_investment_mode'][$key])?$input['monthly_sip_investment_mode'][$key]:0;
                $data['monthly_sip_debt'] = ($input['monthly_sip_debt'][$key])?$input['monthly_sip_debt'][$key]:0;
                $data['monthly_sip_hybrid'] = ($input['monthly_sip_hybrid'][$key])?$input['monthly_sip_hybrid'][$key]:0;
                $data['monthly_sip_equity'] = ($input['monthly_sip_equity'][$key])?$input['monthly_sip_equity'][$key]:0;

                $data['limited_period_monthly_investment_mode'] = isset($input['limited_period_monthly_investment_mode'][$key])?$input['limited_period_monthly_investment_mode'][$key]:0;
                $data['limited_period_monthly_sip_period_1'] = ($input['limited_period_monthly_sip_period_1'][$key])?$input['limited_period_monthly_sip_period_1'][$key]:0;
                $data['limited_period_monthly_sip_period_2'] = ($input['limited_period_monthly_sip_period_2'][$key])?$input['limited_period_monthly_sip_period_2'][$key]:0;
                $data['limited_period_monthly_sip_period_3'] = ($input['limited_period_monthly_sip_period_3'][$key])?$input['limited_period_monthly_sip_period_3'][$key]:0;
                $data['limited_period_monthly_sip_debt'] = ($input['limited_period_monthly_sip_debt'][$key])?$input['limited_period_monthly_sip_debt'][$key]:0;
                $data['limited_period_monthly_sip_hybrid'] = ($input['limited_period_monthly_sip_hybrid'][$key])?$input['limited_period_monthly_sip_hybrid'][$key]:0;
                $data['limited_period_monthly_sip_equity'] = ($input['limited_period_monthly_sip_equity'][$key])?$input['limited_period_monthly_sip_equity'][$key]:0;

                $data['lumpsum_monthly_sip_investment_mode'] = isset($input['lumpsum_monthly_sip_investment_mode'][$key])?$input['lumpsum_monthly_sip_investment_mode'][$key]:0;
                $data['lumpsum_monthly_sip'] = isset($lumpsum_monthly_sip[$key])?$lumpsum_monthly_sip[$key]:0;
                $data['lumpsum_monthly_sip_lumpsum_amount'] = ($input['lumpsum_monthly_sip_lumpsum_amount'][$key])?$input['lumpsum_monthly_sip_lumpsum_amount'][$key]:0;
                $data['lumpsum_monthly_sip_amount_debt'] = ($input['lumpsum_monthly_sip_amount_debt'][$key])?$input['lumpsum_monthly_sip_amount_debt'][$key]:0;
                $data['lumpsum_monthly_sip_amount_hybrid'] = ($input['lumpsum_monthly_sip_amount_hybrid'][$key])?$input['lumpsum_monthly_sip_amount_hybrid'][$key]:0;
                $data['lumpsum_monthly_sip_amount_equity'] = ($input['lumpsum_monthly_sip_amount_equity'][$key])?$input['lumpsum_monthly_sip_amount_equity'][$key]:0;
                // $data['lumpsum_monthly_sip_2'] = isset($input['lumpsum_monthly_sip_2'][$key])?$input['lumpsum_monthly_sip_2'][$key]:0;
                $data['lumpsum_monthly_sip_amount'] = ($input['lumpsum_monthly_sip_amount'][$key])?$input['lumpsum_monthly_sip_amount'][$key]:0;
                $data['lumpsum_monthly_sip_debt'] = ($input['lumpsum_monthly_sip_debt'][$key])?$input['lumpsum_monthly_sip_debt'][$key]:0;
                $data['lumpsum_monthly_sip_hybrid'] = ($input['lumpsum_monthly_sip_hybrid'][$key])?$input['lumpsum_monthly_sip_hybrid'][$key]:0;
                $data['lumpsum_monthly_sip_equity'] = ($input['lumpsum_monthly_sip_equity'][$key])?$input['lumpsum_monthly_sip_equity'][$key]:0;
                
                array_push($data_array, $data);
            }
            // dd($input);
            $data = [];
            $data['list'] = $data_array;
            $data['is_note'] = isset($input['is_note'])?$input['is_note']:'';
            $data['note'] = $input['note'];
            $data['client'] = isset($input['client'])?$input['client']:'';
            $data['clientname'] = $input['clientname'];
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
            // dd($data);
            Session::put("goal_calculator",$data);
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
            'page_id' => 40,
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


        $calculators = DB::table("calculators")->where("url","premium-calculator/goal_calculator")->first();
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
        
        return view('frontend.calculators.goal_calculator.output',$data);
    }
    
    public function save(Request $request){
        $requestData = $request->all();
        if(Session::has("goal_calculator"))
        {
            $data = Session::get("goal_calculator");
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
            $view = (string)View::make('frontend.calculators.goal_calculator.pdf',$data);
            // dd($savedData);
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
                        'calculator_id' => 40,
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
                    'page_id' => 40,
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
                //return view('frontend.goal_calculator.pdf',$input);
                return response("File saved successfully",200);
            }
        }
    }
    
    public function pdf(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('goal_calculator')) {

                $data = Session::get('goal_calculator');
                // dd($data);
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
                    'page_id' => 40,
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

                $data['details'] = DB::table("calculators")->where('url','premium-calculator/goal_calculator')->first();

                if($data['pdf_title_line1']){
                    $oMerger = PDFMerger::init();
                    $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                    
                    $pdf = PDF::loadView('frontend.calculators.goal_calculator.pdf', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                    
                    $oMerger->merge();
                    $oMerger->setFileName($data['details']->name.'.pdf');
                    return $oMerger->download();
                }else{

                    $pdf = PDF::loadView('frontend.calculators.goal_calculator.pdf', $data);
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
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','goal_calculator')->first();

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
                $data['suggested_scheme_list'] = [];
                $data['calculator_duration'] = [];
            }
        }else{
            $data['suggested_performance'] = "";
            $data['suggested_scheme_list'] = [];
            $data['calculator_duration'] = [];
        }

        // dd($data);

        $data['details'] = DB::table("calculators")->where('url','premium-calculator/goal_calculator')->first();
        return view('frontend.calculators.goal_calculator.edit',$data);
    }
    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.goal_calculator');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('goal_calculator', $data);

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
        $data['details'] = DB::table("calculators")->where('url','premium-calculator/goal_calculator')->first();
        return view('frontend.calculators.goal_calculator.view',$data);
    }
    
    public function merge_download(Request $request){

        $id = $request->save_file_id;

        $saveCalculators = SaveCalculators::where('id','=',$id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.goal_calculator');
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

        $data['details'] = DB::table("calculators")->where('url','premium-calculator/goal_calculator')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.goal_calculator.pdf', $data);
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
            $pdf = PDF::loadView('frontend.calculators.goal_calculator.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['details']->name.".pdf");
        return $oMerger->download();

    }

}

?>