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
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\Calculator;
use App\Models\FundPerformanceCreateList;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\SchemecodeData;
use App\Models\Savelist;
use App\Models\Savelistsoftcopy;
use DB;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class PCSwpComprehensiveController extends Controller
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
    
    public function index(Request $request){
        if($request->action == "back"){
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (Session::has('swp_comprehension')) {
                $saveCalculatorsData = Session::get('swp_comprehension');
                
                $data = $saveCalculatorsData;
                // dd($data);
                $data['form_data'] = [];

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
                    // dd($saveCalculatorsData['suggested_scheme_list']);
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
                    $data['suggest'] = "";
                }
                
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','swp_comprehension')->first();
                $data['detail'] = DB::table("calculators")->where('url','swp_comprehensive')->first();
                
                return view('frontend.calculators.swp_comprehensive.edit',$data);
                
            }else{
                return redirect()->route('frontend.stp_custom_transfer');
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
                'page_id' => 43,
                'ip' => $ip_address
            ]);
            
            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','swp_comprehension')->first();
            $data['details'] = DB::table("calculators")->where('url','swp_comprehensive')->first();
            return view('frontend.calculators.swp_comprehensive.index',$data);
        }
            
    }
    
    public function output(Request $request){
        $input = $request->all();
        $data = $input;
        // dd($data); 
        $data['client'] = isset($input['client'])?$input['client']:"";
        $data['is_note'] = isset($input['is_note'])?$input['is_note']:"";
        $data['is_graph'] = isset($input['is_graph'])?$input['is_graph']:"";
        
        $data['scheme_type'] = $request->scheme_type;

        $data['scheme_amount'] = $request->scheme_amount;
            
        if (isset($input['suggest']) && isset($input['suggest'])){
            $data['suggest'] = $input['suggest'];
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

        Session::put('swp_comprehension',$data);

        $ip_address = getIp();
        $scheme_count = 0;
        if(session()->has('suggested_scheme_list')){
            $scheme_count = count(session()->get('suggested_scheme_list'));
        }
    
        $history = History::create([
            'view_count' => 1,
            'user_id' => Auth::user()->id,
            'page_type' => "Calculator",
            'page_id' => 43,
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
        

        $calculators = DB::table("calculators")->where("url",'swp_comprehensive')->first();
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
        // dd($data);
        return view('frontend.calculators.swp_comprehensive.output',$data);
    }
    
    public function pdf(Request $request){
         if (Session::has('swp_comprehension')) {

                $data = Session::get('swp_comprehension');


                $data['pdf_title_line1'] = $request->pdf_title_line1;
                $data['pdf_title_line2'] = $request->pdf_title_line2;
                $data['client_name'] = $request->client_name;
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

                $data['calculator_details'] = DB::table("calculators")->where("url","swp_comprehensive")->first();

                $ip_address = getIp();
                $scheme_count = 0;
                if(session()->has('suggested_scheme_list')){
                    $scheme_count = count(session()->get('suggested_scheme_list'));
                }
            
                $history = History::create([
                    'download_count' => 1,
                    'user_id' => Auth::user()->id,
                    'page_type' => "Calculator",
                    'page_id' => 43,
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
                
                if($data['pdf_title_line1']){
                    $oMerger = PDFMerger::init();
                    $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                    
                    $pdf = PDF::loadView('frontend.calculators.swp_comprehensive.pdf', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                    
                    $oMerger->merge();
                    $oMerger->setFileName($data['calculator_details']->name.'.pdf');
                    return $oMerger->download();
                }else{
                    // return view('frontend.calculators.swp_comprehensive.pdf',$data);
                    $pdf = PDF::loadView('frontend.calculators.swp_comprehensive.pdf',$data);
                    return $pdf->download($data['calculator_details']->name.'.pdf');
                }

                
            }
    }
    
    public function save(Request $request){
        $requestData = $request->all();
        if(Session::has("swp_comprehension"))
        {
            $data = Session::get("swp_comprehension");
            

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

            $view = (string)View::make('frontend.calculators.swp_comprehensive.pdf',$data);

            $edit_id = session()->get('lumpsum_investment_target_future_value_form_id');

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
                    'calculator_id' => 43,
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
                'page_id' => 43,
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

    public function edit(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.swp_comprehension');
        }

        $saveCalculatorsData = unserialize($saveCalculators['data']);

        $data = $saveCalculatorsData;
        $data['suggest'] = isset($data['suggest'])?$data['suggest']:"";
        $data['form_data'] = [];

        $data['form_data']['suggest'] = "";

        $data['custom_list_input'] = "";
        $data['category_list_input'] = "";
        $data['suggestedlist_type'] = "";
        $data['scheme_type'] = [];
        $data['scheme_amount'] = [];
        
        if(isset($saveCalculatorsData['suggest'])){
            
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
        Session::put('calculator_form_id', $request->id);
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','swp_comprehension')->first();
        $data['detail'] = DB::table("calculators")->where('url','swp_comprehensive')->first();

        return view('frontend.calculators.swp_comprehensive.edit',$data);
    }
    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.swp_comprehension');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('swp_comprehension', $data);
        $data['edit_id'] = 0;
        $data['id'] = $request->id;
        $calculators = DB::table("calculators")->where('url','swp_comprehensive')->first();
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
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','swp_comprehension')->first();
        $data['detail'] = DB::table("calculators")->where('url','swp_comprehensive')->first();

        // dd($data);

        return view('frontend.calculators.swp_comprehensive.view',$data);
    }
    
    public function merge_download(Request $request){

        $id = $request->save_file_id;

        $saveCalculators = SaveCalculators::where('id','=',$id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.lumsumInvestmentRequiredForTargetFutureValue');
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

        $data['detail'] = DB::table("calculators")->where('url','swp_comprehensive')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.swp_comprehensive.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }

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
            $pdf = PDF::loadView('frontend.calculators.swp_comprehensive.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['detail']->name.".pdf");
        return $oMerger->download();

    }
    
    public function swp_check(Request $request)
    {
        $sendable = "yoyo";
                        $inputs = $request->all();
    
                        extract($inputs);
    
                        $calctype = '';
                        if($investmentmode == 1 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                        //dd("first calc");
                        $calctype = 1;
                            
                                $swpAmt = $swpamount;
                           
                                
                                
                                
                            
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 12;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 4;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 2;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 1;
                                $addonText = "Yearly";
                                }
                                
                            
                            
                            //$lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            //$lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($initial * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * $inter;
                            $returnAble = $maxMoneyBack / $inter;
                            //$totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100).'!'."formatted";
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            //dd("sec calc");
                            $calctype =  2;
                            
                                $swpAmt = $swpamount;
                            
                           
                            $mainMonths = 0;
                            $inter = 1;
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 12;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 4;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 2;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 1;
                                $addonText = "Yearly";
                                }
                              
                             //$lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                           // $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * $inter)*100;
                            $returnAble = $maxMoneyBack / $inter;
                            
                            //$totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent);
                            //dd($balanceAvailable);
                        }
                      else  if($investmentmode == 1 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("third calc");
                        $calctype = 3;
                            
                                $swpAmt = $swpamount;
                            
                                
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($total1 == $inpercent)
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                           // dd($lumpsumforAnnuity);
                            //$lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $initial * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $initial * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                       else if($investmentmode == 1 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                       // dd("fourth calc");
                        $calctype = 4;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($initial * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($initial - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("fifth calc");
                        $calctype = 5;
                            
                                $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($total1 == $inpercent)
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                           // dd($lumpsumforAnnuity);
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                            
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                        //dd("sixth calc");
                        $calctype = 6;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 2 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 7;
                            
                                $swpAmt = $swpamount;
                            
                                
                                
                                
                            
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            //$lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                            
                        }
                       else if($investmentmode == 2 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            //dd("sec calc");
                            $calctype =  8;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             //$lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * (12 / $inter));
                            
                            
                           // $totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                            //dd($balanceAvailable);
                        }
                        else if($investmentmode == 2 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 9;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            
                            // if($total1 == $inpercent)
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                            
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                            //dd($accumulated);
                        }
                        else if($investmentmode == 2 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 10;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                           // $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 2 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 11;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($total1 == $inpercent)
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                           // dd($lumpsumforAnnuity);
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 2 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                        //dd("sixth calc");
                        $calctype = 12;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                            //dd($balanceAvailable);
                        }
                        
                        else if($investmentmode == 3 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 13;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            
                            //$lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 3 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype =  14;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                            // $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                           // $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * (12 / $inter))*100;
                            
                            
                           // $totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent);
                        }
                        else  if($investmentmode == 3 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("third calc");
                        $calctype = 15;
                        
                             $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($actotal1 == $inpercent)
                            // {
                            // //echo("Coming 1");
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            // //echo("Coming 2");
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                            //dd($totalMonths." ".$total1);
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($actotal1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                           // $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                            
                        }
                        else  if($investmentmode == 3 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 16;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 3 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 17;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($total1 == $inpercent)
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                           // dd($lumpsumforAnnuity);
                            //$lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                        
                        else if($investmentmode == 3 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 18;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                            $sendable = $maxMoneyBackInMonthly.'!'.$maxMoneyBackPercent;
                        }
                       else if($investmentmode == 4 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 19;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                           // $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                           // $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                          //  $totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype =  20;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                           //  $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                           // $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * (12 / $inter))*100;
                            
                            
                         //   $totalWithdrawal = $swpAmt * $totalMonths;
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent);
                        }
                        else if($investmentmode == 4 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 21;
                        
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($actotal1 == $inpercent)
                            // {
                            // //echo("Coming 1");
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            // //echo("Coming 2");
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                            //dd($totalMonths." ".$total1);
                           // $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                           // $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($actotal1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                           // $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                        
                        else if($investmentmode == 4 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 22;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 23;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            // if($total1 == $inpercent)
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            // }
                            // else
                            // {
                            //     $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            // }
                            
                           // dd($lumpsumforAnnuity);
                           // $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            //$balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * (12 / $inter);
                            //$totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            $sendable = $maxMoneyBack.'!'.($maxMoneyBackPercent*100);
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 24;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            
                            $sendable = $maxMoneyBackInMonthly.'!'.($maxMoneyBackPercent*100);
                        }
                        
                        else
                        {
                            //dd("else");
                            $sendable = "not done";
                            
                        }
                        
                        
                        
                        return response($sendable,200);
    }
    
}

?>