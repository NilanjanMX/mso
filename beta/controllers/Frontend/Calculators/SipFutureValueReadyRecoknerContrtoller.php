<?php

namespace App\Http\Controllers\Frontend\Calculators;

//use Barryvdh\DomPDF\PDF as PDF;
use App\Models\Displayinfo;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\FundPerformanceCreateList;
use App\Models\Membership;
use App\Models\SaveCalculators;
use App\Models\Savelist;
use App\Models\UserHistory;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\Savelistsoftcopy;
use App\Models\CalculatorHeading;
use App\Models\SchemecodeData;
use App\Models\Calculator;
use App\Models\Calculator_category;
use App\Models\Calculator_category_value;
use App\Models\Samplereport;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use DB;

class SipFutureValueReadyRecoknerContrtoller extends Controller
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
    
    public function sip_future_value_ready_reckoner(Request $request){
        
        if($request->action == "back"){
            if (session()->has('sip_future_value_ready_reckoner_form_id')){
                session()->forget('sip_future_value_ready_reckoner_form_id');
            }
            // dd(Session::get('sip_future_value_ready_reckoner_form_data'));
            if (Session::has('sip_future_value_ready_reckoner_form_data')) {
                $saveCalculatorsData = Session::get('sip_future_value_ready_reckoner_form_data');
                
                // dd($saveCalculatorsData);
                
                // if (session()->has('sip_future_value_ready_reckoner_form_data')){
                //     session()->forget('sip_future_value_ready_reckoner_form_data');
                // }
                
                $data = [];
                $data['form_data'] = [];
                $data['form_data']['client'] = isset($saveCalculatorsData['client'])?$saveCalculatorsData['client']:0;
                $data['form_data']['report'] = isset($saveCalculatorsData['report'])?$saveCalculatorsData['report']:0;
                $data['form_data']['clientname'] = isset($saveCalculatorsData['clientname'])?$saveCalculatorsData['clientname']:"";
                $data['form_data']['is_note'] = $saveCalculatorsData['is_note'];
                $data['form_data']['note'] = isset($saveCalculatorsData['note'])?$saveCalculatorsData['note']:'';
                $data['form_data']['is_graph'] = $saveCalculatorsData['is_graph'];
                $data['form_data']['investment'] = $saveCalculatorsData['investment'];
                $data['form_data']['period1'] = $saveCalculatorsData['period1'];
                $data['form_data']['period2'] = $saveCalculatorsData['period2'];
                $data['form_data']['period3'] = $saveCalculatorsData['period3'];
                $data['form_data']['period4'] = $saveCalculatorsData['period4'];
                $data['form_data']['period5'] = $saveCalculatorsData['period5'];
                $data['form_data']['interest1'] = $saveCalculatorsData['interest1'];
                $data['form_data']['interest2'] = $saveCalculatorsData['interest2'];
                $data['form_data']['interest3'] = $saveCalculatorsData['interest3'];
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
                
                
        
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','sip_future_value_ready_recokner')->first();
                // dd($data);

                $data['details'] = DB::table("calculators")->where("url","calculators/sip-calculator/sip-future-value-ready-recokner")->first();
                // dd($data);
                return view('frontend.calculators.sipfuturevaluereadyrecokner.edit',$data);
                
            }else{
                return redirect()->route('frontend.sipFutureValueReadyReckoner');
            }
        }else{
            if (session()->has('suggested_scheme_list')){
                session()->forget('suggested_scheme_list');
            }
            if (session()->has('sip_future_value_ready_reckoner_form_data')){
                session()->forget('sip_future_value_ready_reckoner_form_data');
            }
            if (session()->has('sip_future_value_ready_reckoner_form_id')){
                session()->forget('sip_future_value_ready_reckoner_form_id');
            }
            if (session()->has('calc_title')){
                session()->forget('calc_title');
            }
    
            $ip_address = getIp();
    
            History::create([
                'list_count' => 1,
                'user_id' => Auth::user()->id,
                'page_type' => "Calculator",
                'page_id' => 11,
                'ip' => $ip_address
            ]);

            $data = [];
            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','sip_future_value_ready_recokner')->first();
            
            $data['details'] = DB::table("calculators")->where('url','calculators/sip-calculator/sip-future-value-ready-recokner')->first();
            // dd($data);
            return view('frontend.calculators.sipfuturevaluereadyrecokner.index',$data);
        }
    }

    public function sip_future_value_ready_reckoner_output(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('sip_future_value_ready_reckoner_form_data')) {
                $input = Session::get('sip_future_value_ready_reckoner_form_data');
                // dd($input);
                $data['investment'] = $input['investment'];
                $data['period1'] = $input['period1'];
                $data['period2'] = $input['period2'];
                $data['period3'] = $input['period3'];
                $data['period4'] = $input['period4'];
                $data['period5'] = $input['period5'];
                $data['interest1'] = $input['interest1'];
                $data['interest2'] = $input['interest2'];
                $data['interest3'] = $input['interest3'];

                
                if (isset($input['is_graph']) && $input['is_graph'] != '') {
                    $data['is_graph'] = $input['is_graph'];
                }else{
                    $data['is_graph'] = 0;
                }
                if (isset($input['is_note']) && $input['is_note'] != '') {
                    $data['is_note'] = $input['is_note'];
                }else{
                    $data['is_note'] = 0;
                }

                if (isset($input['clientname']) && $input['clientname'] != '' && isset($input['client'])) {
                    $data['clientname'] = $input['clientname'];
                }


                if (isset($input['report']) && $input['report'] != '' && isset($input['report'])) {
                    $data['report'] = $input['report'];
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

                if (isset($input['clientname']) && $input['clientname'] != '' ) {
                    $data['clientname'] = $input['clientname'];
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
            // Fund Performance //
                }
            }else{
                return redirect()->route('frontend.sipFutureValueReadyReckoner');
            }
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'investment' => 'required|numeric|between:100,9999999999',
                'interest1' => 'required|numeric|between:0.1,18',
                'interest2' => 'nullable|numeric|between:0.1,18',
                'interest3' => 'nullable|numeric|between:0.1,18',
                'period1' => 'required|numeric|between:1,99',
                'period2' => 'nullable|numeric|between:1,99',
                'period3' => 'nullable|numeric|between:1,99',
                'period4' => 'nullable|numeric|between:1,99',
                'period5' => 'nullable|numeric|between:1,99',
                'clientname' => 'sometimes|nullable|max:30',
            ]);

            $input = $request->all();
            $data['investment'] = $input['investment'];
            $data['period1'] = $input['period1'];
            $data['period2'] = $input['period2'];
            $data['period3'] = $input['period3'];
            $data['period4'] = $input['period4'];
            $data['period5'] = $input['period5'];
            $data['interest1'] = $input['interest1'];
            $data['interest2'] = $input['interest2'];
            $data['interest3'] = $input['interest3'];
            

            if (isset($input['is_graph']) && $input['is_graph'] != '') {
                $data['is_graph'] = $input['is_graph'];
            }else{
                $data['is_graph'] = 0;
            }
            if (isset($input['is_note']) && $input['is_note'] != '') {
                $data['is_note'] = $input['is_note'];
            }else{
                $data['is_note'] = 0;
            }

            if (isset($input['clientname']) && $input['clientname'] != '' && isset($input['client'])) {
                $data['clientname'] = $input['clientname'];
            }


            if (isset($input['report']) && $input['report'] != '' && isset($input['report'])) {
                $data['report'] = $input['report'];
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
            Session::put('sip_future_value_ready_reckoner_form_data', $data);
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
            'page_id' => 11,
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

        $category_detail = Calculator::where("url","calculators/sip-calculator/sip-future-value-ready-recokner")->first();

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

        if(isset($input['is_graph']) && $input['is_graph'] != ''){
            $all_periods = array();
            if(isset($input['period1']) && !empty($input['period1'])){
                $all_periods[] = $input['period1'];
            }if(isset($input['period2']) && !empty($input['period2'])){
                $all_periods[] = $input['period2'];
            }if(isset($input['period3']) && !empty($input['period3'])){
                $all_periods[] = $input['period3'];
            }if(isset($input['period4']) && !empty($input['period4'])){
                $all_periods[] = $input['period4'];
            }if(isset($input['period5']) && !empty($input['period5'])){
                $all_periods[] = $input['period5'];
            }
            $data['all_periods'] = $all_periods;
        }

        $edit_id = session()->get('sip_future_value_ready_reckoner_form_id');
        if(!empty($edit_id)){
            $calc = SaveCalculators::where('user_id',Auth::user()->id)->where('id',$edit_id)->first();
            $data['cal_name'] = $calc->title;
        }
        
        $calculators = DB::table("calculators")->where("id",11)->first();
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
        $data['edit_id'] = session()->get('sip_future_value_ready_reckoner_form_id');
        // dd(session()->get('sip_future_value_ready_reckoner_form_id'));
        return view('frontend.calculators.sipfuturevaluereadyrecokner.output',$data);
    }

    public function sip_future_value_ready_reckoner_output_save(Request $request){
        $requestData = $request->all();
        if ($request->isMethod('get')) {
            if (Session::has('sip_future_value_ready_reckoner_form_data')) {
                $input = Session::get('sip_future_value_ready_reckoner_form_data');
                
                $data['is_note'] = $input['is_note'];
                $data['is_graph'] = $input['is_graph'];
                $data['investment'] = $input['investment'];
                $data['period1'] = $input['period1'];
                $data['period2'] = $input['period2'];
                $data['period3'] = $input['period3'];
                $data['period4'] = $input['period4'];
                $data['period5'] = $input['period5'];
                $data['interest1'] = $input['interest1'];
                $data['interest2'] = $input['interest2'];
                $data['interest3'] = $input['interest3'];
                $data['report'] = $input['report'];
                $data['scheme_amount'] = $input['scheme_amount'];
                $data['scheme_type'] = $input['scheme_type'];
                $data['note'] = $input['note'];
                $data['client'] = isset($input['client'])?$input['client']:'';
                
                if (isset($input['clientname']) && $input['clientname'] != '' ) {
                    $data['clientname'] = $input['clientname'];
                }
                if (isset($input['suggest']) && isset($input['suggest'])){
                    $data['suggest'] = $input['suggest'];
                }
                if (isset($input['suggestedlist_type']) && isset($input['suggestedlist_type'])){
                    $data['suggestedlist_type'] = $input['suggestedlist_type'];
                }
                if (isset($input['category_list_input']) && isset($input['category_list_input'])){
                    $data['category_list_input'] = $input['category_list_input'];
                }
                if (isset($input['custom_list_input']) && isset($input['custom_list_input'])){
                    $data['custom_list_input'] = $input['custom_list_input'];
                }

                $data['pie_chart2'] = Session::get('pie_chart2');
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

                $view = (string)View::make('frontend.calculators.sipfuturevaluereadyrecokner.pdf',$data);

                $edit_id = session()->get('sip_future_value_ready_reckoner_form_id');

                if($edit_id){
                    SaveCalculators::where('user_id',Auth::user()->id)->where('id',$edit_id)->update([
                        'title' => $requestData['title'],
                        'data' => serialize($savedData),
                    ]);
                    Storage::put('calculators/'.$edit_id.'.txt', $view);
                    session()->forget('sip_future_value_ready_reckoner_form_id');
                }else{
                    
                    $saveCal = SaveCalculators::create([
                        'title' => $requestData['title'],
                        'data' => serialize($savedData),
                        'calculator_id' => 11,
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
                    'page_id' => 11,
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
                return redirect()->route('frontend.sipFutureValueReadyReckoner');
            }
        }
    }


    public function sip_future_value_ready_reckoner_output_pdf_download(Request $request){
        if ($request->isMethod('get')) {
            if (Session::has('sip_future_value_ready_reckoner_form_data')) {
                $input = Session::get('sip_future_value_ready_reckoner_form_data');
                // $data = $input;
                $data['investment'] = $input['investment'];
                $data['period1'] = $input['period1'];
                $data['period2'] = $input['period2'];
                $data['period3'] = $input['period3'];
                $data['period4'] = $input['period4'];
                $data['period5'] = $input['period5'];
                $data['interest1'] = $input['interest1'];
                $data['interest2'] = $input['interest2'];
                $data['interest3'] = $input['interest3'];

                if (isset($input['clientname']) && $input['clientname'] != '') {
                    $data['clientname'] = $input['clientname'];
                }
                if (isset($input['suggest']) && isset($input['suggest'])){
                    $data['suggest'] = $input['suggest'];
                }
                if (isset($input['scheme_type']) && isset($input['scheme_type'])){
                    $data['scheme_type'] = $input['scheme_type'];
                }
                if (isset($input['scheme_amount']) && isset($input['scheme_amount'])){
                    $data['scheme_amount'] = $input['scheme_amount'];
                }

                if (isset($input['note']) && isset($input['note'])){
                    $data['note'] = $input['note'];
                }
                if (isset($input['is_note']) && isset($input['is_note'])){
                    $data['is_note'] = $input['is_note'];
                }

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
                    'page_id' => 11,
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

                
                $data['title'] = "Sip Future Value Ready Reckoner";

                $data['pie_chart2'] = Session::get('pie_chart2');

                $data['details'] = DB::table("calculators")->where("url","calculators/sip-calculator/sip-future-value-ready-recokner")->first();

                if($data['pdf_title_line1']){
                    $oMerger = PDFMerger::init();
                    $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                    
                    $pdf = PDF::loadView('frontend.calculators.sipfuturevaluereadyrecokner.pdf', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                    
                    $oMerger->merge();
                    $oMerger->setFileName($data['details']->name.'.pdf');
                    return $oMerger->download();
                }else{

                    $pdf = PDF::loadView('frontend.calculators.sipfuturevaluereadyrecokner.pdf', $data);
                    return $pdf->download($data['details']->name.'.pdf');
                }


            }else{
                return redirect()->route('frontend.sipFutureValueReadyReckoner');
            }
        }
    }

    public function sip_future_value_ready_reckoner_edit(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.sipFutureValueReadyReckoner');
        }

        $saveCalculatorsData = unserialize($saveCalculators['data']);

        // dd($saveCalculatorsData);

        $data = [];
        $data['form_data'] = [];
        $data['form_data']['client'] = isset($saveCalculatorsData['client'])?$saveCalculatorsData['client']:0;
        $data['form_data']['report'] = isset($saveCalculatorsData['report'])?$saveCalculatorsData['report']:0;
        $data['form_data']['clientname'] = isset($saveCalculatorsData['clientname'])?$saveCalculatorsData['clientname']:"";
        $data['form_data']['is_note'] = $saveCalculatorsData['is_note'];
        $data['form_data']['note'] = isset($saveCalculatorsData['note'])?$saveCalculatorsData['note']:'';
        $data['form_data']['is_graph'] = $saveCalculatorsData['is_graph'];
        $data['form_data']['investment'] = $saveCalculatorsData['investment'];
        $data['form_data']['period1'] = $saveCalculatorsData['period1'];
        $data['form_data']['period2'] = $saveCalculatorsData['period2'];
        $data['form_data']['period3'] = $saveCalculatorsData['period3'];
        $data['form_data']['period4'] = $saveCalculatorsData['period4'];
        $data['form_data']['period5'] = $saveCalculatorsData['period5'];
        $data['form_data']['interest1'] = $saveCalculatorsData['interest1'];
        $data['form_data']['interest2'] = $saveCalculatorsData['interest2'];
        $data['form_data']['interest3'] = $saveCalculatorsData['interest3'];
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
            
        Session::put('calc_title', $saveCalculators->title);
        Session::put('sip_future_value_ready_reckoner_form_id', $request->id);
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','sip_future_value_ready_recokner')->first();
        // dd($data);
        return view('frontend.calculators.sipfuturevaluereadyrecokner.edit',$data);
    }

    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.sipfuturevaluereadyrecokner');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('sip_future_value_ready_reckoner_form_data', $data);
        Session::put('pie_chart2', $data['pie_chart2']);
        $data['edit_id'] = 0;
        $data['id'] = $request->id;
        $calculators = DB::table("calculators")->where("id",2)->first();
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
        $data['details'] = DB::table("calculators")->where('url','calculators/sip-calculator/sip-future-value-ready-recokner')->first();
        return view('frontend.calculators.sipfuturevaluereadyrecokner.view',$data);
    }
    
    public function merge_download(Request $request){

        $id = $request->save_file_id;

        $saveCalculators = SaveCalculators::where('id','=',$id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.sipfuturevaluereadyrecokner');
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

        $data['details'] = DB::table("calculators")->where('url','calculators/sip-calculator/sip-future-value-ready-recokner')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.sipfuturevaluereadyrecokner.pdf', $data);
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
            $pdf = PDF::loadView('frontend.calculators.sipfuturevaluereadyrecokner.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['details']->name.".pdf");
        return $oMerger->download();

    }
    
    
    

}