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
use App\Models\Savelistsoftcopy;
use App\Models\UserHistory;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\Calculator;
use App\Models\FundPerformanceCreateList;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\SchemecodeData;
use App\Models\Savelist;
use DB;

use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class ReviewOfExistingInvestmentController extends Controller
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

        if (session()->has('suggested_scheme_list')){
            session()->forget('suggested_scheme_list');
        }

        if($request->action == "back"){
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (Session::has('review_of_existing_investment')) {
                $saveCalculatorsData = Session::get('review_of_existing_investment');
                
                $data = $saveCalculatorsData;               
                
        
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','review_of_existing_investment')->first();
                // dd($data);

                $data['details'] = DB::table("calculators")->where('url','premium-calculator/review_of_existing_investment')->first();
                $data['scheme_list'] = DB::table("mf_scanner")
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                        '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','incret as incret','mf_scanner.plan','mf_scanner.classcode','accord_sclass_mst.classname',
                                        'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                                ])
                            ->orderBy('s_name','ASC')->get();
                $scanner_avg_list = DB::table("mf_scanner_avg")
                                ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner_avg.classcode')
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->select(['1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                            '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yearret as tenyear','incret as incret','mf_scanner_avg.classcode','mf_scanner_avg.plan_code','accord_sclass_mst.classname',
                                            'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                                    ])->get();

                // dd($scanner_avg_list);

                $data['scanner_avg_list'] = [];

                foreach ($scanner_avg_list as $key => $value) {
                    $data['scanner_avg_list'][$value->classcode."_".$value->plan_code] = $value;
                }
                $data['category_list'] = DB::table("accord_sclass_mst")
                                ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                                ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                                ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();
                $data['assets_list'] = ["Equity","Hybrid","Debt","Other"];
                $data['product_list'] = DB::table("roei_products")->select(['id','name'])->orderBy('name','ASC')->get();
                $data['product_type_list'] = DB::table("roei_product_types")->select(['id','name'])->orderBy('name','ASC')->get();
                return view('frontend.calculators.review_of_existing_investment.edit',$data);
                
            }else{
                return redirect()->route('frontend.review_of_existing_investment');
            }
        }else{
            if (session()->has('review_of_existing_investment')){
                session()->forget('review_of_existing_investment');
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
                'page_id' => 37,
                'ip' => $ip_address
            ]);

            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','review_of_existing_investment')->first();

            $data['scheme_list'] = DB::table("mf_scanner")
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                        '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','incret as incret','mf_scanner.plan','mf_scanner.classcode','accord_sclass_mst.classname',
                                        'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                                ])
                            ->orderBy('s_name','ASC')->get();
            $scanner_avg_list = DB::table("mf_scanner_avg")
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner_avg.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->select(['1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                        '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yearret as tenyear','incret as incret','mf_scanner_avg.classcode','mf_scanner_avg.plan_code','accord_sclass_mst.classname',
                                        'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                                ])->get();

            // dd($scanner_avg_list);

            $data['scanner_avg_list'] = [];

            foreach ($scanner_avg_list as $key => $value) {
                $data['scanner_avg_list'][$value->classcode."_".$value->plan_code] = $value;
            }
            $data['category_list'] = DB::table("accord_sclass_mst")
                            ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();
            $data['assets_list'] = ["Equity","Hybrid","Debt","Other"];
            $data['product_list'] = DB::table("roei_products")->select(['id','name'])->orderBy('name','ASC')->get();
            $data['product_type_list'] = DB::table("roei_product_types")->select(['id','name'])->orderBy('name','ASC')->get();

            $data['details'] = DB::table("calculators")->where('url','premium-calculator/review_of_existing_investment')->first();

            return view('frontend.calculators.review_of_existing_investment.index',$data);
        }

            
    }
    //Recover emis through spis
    public function output(Request $request){
        if ($request->isMethod('post')) {

            $input = $request->all();
            // dd($input);
            $data = [];

            $data['mutual_fund_list'] = [];
            foreach ($input['schemecode_id'] as $key => $value) {
                $scanner_list = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','mf_scanner.classcode','accord_sclass_mst.classname',
                                'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name','mf_scanner.plan'
                            ])
                        ->where('schemecode',$input['schemecode_id'][$key])->first();
                $insertData = (array) $scanner_list;

                $scanner_avg = DB::table("mf_scanner_avg")
                        ->select(['1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yearret as tenyear','mf_scanner_avg.classcode','mf_scanner_avg.plan_code'
                            ])
                        ->where('classcode',$insertData['classcode'])->where('plan_code',$insertData['plan'])->first();

                // dd($insertData);
                if($scanner_avg){
                    $insertData['category_oneday'] = $scanner_avg->oneday;
                    $insertData['category_oneweek'] = $scanner_avg->oneweek;
                    $insertData['category_onemonth'] = $scanner_avg->onemonth;
                    $insertData['category_threemonth'] = $scanner_avg->threemonth;
                    $insertData['category_sixmonth'] = $scanner_avg->sixmonth;
                    $insertData['category_oneyear'] = $scanner_avg->oneyear;
                    $insertData['category_twoyear'] = $scanner_avg->twoyear;
                    $insertData['category_threeyear'] = $scanner_avg->threeyear;
                    $insertData['category_fiveyear'] = $scanner_avg->fiveyear;
                    $insertData['category_tenyear'] = $scanner_avg->tenyear;
                }else{

                }
                $insertData['classname'] = ($scanner_list)?($scanner_list->class_name)?$scanner_list->class_name:$scanner_list->classname:"";
                $insertData['comments'] = isset($input['mutual_fund_comment'][$key])?$input['mutual_fund_comment'][$key]:"";
                $insertData['day1'] = 0;
                $insertData['day7'] = 0;
                $insertData['month1'] = 0;
                $insertData['month3'] = 0;
                $insertData['month6'] = 0;
                $insertData['year1'] = 0;
                $insertData['year3'] = 0;
                $insertData['year5'] = 0;
                $insertData['year10'] = 0;
                $insertData['return_count'] = 0;
                if(isset($input['day1'][$key])){
                    $insertData['day1'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['day7'][$key])){
                    $insertData['day7'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['month1'][$key])){
                    $insertData['month1'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['month3'][$key])){
                    $insertData['month3'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['month6'][$key])){
                    $insertData['month6'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['year1'][$key])){
                    $insertData['year1'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['year3'][$key])){
                    $insertData['year3'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['year5'][$key])){
                    $insertData['year5'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                if(isset($input['year10'][$key])){
                    $insertData['year10'] = 1;
                    $insertData['return_count'] = $insertData['return_count'] + 1;
                }
                $insertData['category_checkbox'] = isset($input['category_checkbox'][$key])?1:0;
                array_push($data['mutual_fund_list'], $insertData);
            }
            // dd($data);
            $data['non_mutual_fund_list'] = [];
            foreach ($input['product_id'] as $key => $value) {
                
                if($value){
                    $product_list = DB::table("roei_products")->select(['id','name'])->where('id',$value)->first();
                    $product_list = $product_list->name;
                }else{
                    $product_list = isset($input['product_name'][$key])?$input['product_name'][$key]:"";
                }

                $insertData = [];
                $insertData['id'] = $value;
                $insertData['name'] = $product_list;
                $insertData['comments'] = isset($input['non_mutual_fund_comment'][$key])?$input['non_mutual_fund_comment'][$key]:"";
                array_push($data['non_mutual_fund_list'], $insertData);
            }

            $data['insurance_list'] = [];
            foreach ($input['product_type_id'] as $key => $value) {
                if($value){
                    $product_type_list = DB::table("roei_product_types")->select(['id','name'])->where('id',$value)->first();
                    $product_type_list = $product_type_list->name;
                }else{
                    $product_type_list = isset($input['product_type_name'][$key])?$input['product_type_name'][$key]:"";
                }
                
                $insertData = [];
                $insertData['id'] = $value;
                $insertData['name'] = $product_type_list;
                $insertData['user'] = isset($input['insurance_user'][$key])?$input['insurance_user'][$key]:"";
                $insertData['comments'] = isset($input['insurance_comment'][$key])?$input['insurance_comment'][$key]:"";
                array_push($data['insurance_list'], $insertData);
            }

            $data['insurance'] = (isset($input['insurance']))?$input['insurance']:"";
            $data['non_mutual_fund'] = (isset($input['non_mutual_fund']))?$input['non_mutual_fund']:"";
            $data['mutual_fund'] = (isset($input['mutual_fund']))?$input['mutual_fund']:"";
            $data['client_name'] = isset($input['client_name'])?$input['client_name']:"";
            // dd($data);

            Session::put("review_of_existing_investment",$data);
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
            'page_id' => 37,
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

        $calculators = DB::table("calculators")->where("url","premium-calculator/review_of_existing_investment")->first();
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
        
        return view('frontend.calculators.review_of_existing_investment.output',$data);
    }
    
    public function save(Request $request){
        $requestData = $request->all();
        if(Session::has("review_of_existing_investment")){
            $data = Session::get("review_of_existing_investment");
            $savedData = $data;
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
            $view = (string)View::make('frontend.calculators.review_of_existing_investment.pdf',$data);

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
                    'calculator_id' => 37,
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
                'page_id' => 37,
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
        }
    }
    
    public function pdf(Request $request){
        // dd("ok");
        if ($request->isMethod('get')) {
            if (Session::has('review_of_existing_investment')) {

                $data = Session::get('review_of_existing_investment');
                $data['pdf_title_line1'] = $request->pdf_title_line1;
                $data['pdf_title_line2'] = $request->pdf_title_line2;
                $data['client_name'] = $request->client_name;
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

                $data['details'] = DB::table("calculators")->where("url","premium-calculator/review_of_existing_investment")->first();

                $ip_address = getIp();
                $scheme_count = 0;
                if(session()->has('suggested_scheme_list')){
                    $scheme_count = count(session()->get('suggested_scheme_list'));
                }
            
                $history = History::create([
                    'download_count' => 1,
                    'user_id' => Auth::user()->id,
                    'page_type' => "Calculator",
                    'page_id' => 37,
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
                    
                    $pdf = PDF::loadView('frontend.calculators.review_of_existing_investment.pdf', $data);
                    $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                    
                    $oMerger->merge();
                    $oMerger->setFileName($data['details']->name.'.pdf');
                    return $oMerger->download();
                }else{
                    $pdf = PDF::loadView('frontend.calculators.review_of_existing_investment.pdf', $data);
                    return $pdf->download($data['details']->name.'.pdf');
                }
            }
        }
    }

    public function edit(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.review_of_existing_investment');
        }

        $data = unserialize($saveCalculators['data']);

        // dd($data);
        $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','mf_scanner.plan','mf_scanner.classcode','accord_sclass_mst.classname',
                                    'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                            ])
                        ->orderBy('s_name','ASC')->get();
        $scanner_avg_list = DB::table("mf_scanner_avg")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner_avg.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yearret as tenyear','mf_scanner_avg.classcode','mf_scanner_avg.plan_code','accord_sclass_mst.classname',
                                    'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                            ])->get();

        // dd($scanner_avg_list);

        $data['scanner_avg_list'] = [];

        foreach ($scanner_avg_list as $key => $value) {
            $data['scanner_avg_list'][$value->classcode."_".$value->plan_code] = $value;
        }
        $data['category_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();
        $data['assets_list'] = ["Equity","Hybrid","Debt","Other"];
        $data['product_list'] = DB::table("roei_products")->select(['id','name'])->orderBy('name','ASC')->get();
        $data['product_type_list'] = DB::table("roei_product_types")->select(['id','name'])->orderBy('name','ASC')->get();

        Session::put('calc_title', $saveCalculators->title);
        Session::put('calculator_form_id', $request->id);
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','review_of_existing_investment')->first();
        // dd($data);
        $data['details'] = DB::table("calculators")->where('url','premium-calculator/review_of_existing_investment')->first();
        return view('frontend.calculators.review_of_existing_investment.edit',$data);
    }
    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.review_of_existing_investment');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('review_of_existing_investment', $data);

        $data['edit_id'] = 0;
        $data['id'] = $request->id;
        $calculators = DB::table("calculators")->where("id",37)->first();
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
        $data['details'] = DB::table("calculators")->where('url','premium-calculator/review_of_existing_investment')->first();
        return view('frontend.calculators.review_of_existing_investment.view',$data);
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

        $data['details'] = DB::table("calculators")->where('url','premium-calculator/review_of_existing_investment')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.review_of_existing_investment.pdf', $data);
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
            $pdf = PDF::loadView('frontend.calculators.review_of_existing_investment.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['details']->name.".pdf");
        return $oMerger->download();

    }

    public function review_of_existing_investment_data(Request $request){
        $input = $request->all();
        // dd($input);
        $data = [];
        $data['category_list'] = [];
        $data['scheme_list'] = [];
        if($input['type'] == 1){
            if($input['asset_class'] == "0"){
                $data['category_list'] = DB::table("accord_sclass_mst")
                            ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name','asset_type'])
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->where('accord_sclass_mst.status',1)->orderBy('classname', 'asc')->get();

                $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','mf_scanner.classcode','accord_sclass_mst.classname',
                                    'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                            ])
                        ->where('mf_scanner.status',1)->orderBy('s_name','ASC')->get();
            }else{
                $data['category_list'] = DB::table("accord_sclass_mst")
                            ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name','asset_type'])
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->where('accord_sclass_mst.status',1)->where('accord_sclass_mst.asset_type',$input['asset_class'])->orderBy('classname', 'asc')->get();
                $classcodeArr = [];
                foreach ($data['category_list'] as $key => $value) {
                    array_push($classcodeArr,$value->classcode);
                }
                // dd($classcodeArr);
                $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','mf_scanner.classcode','accord_sclass_mst.classname',
                                    'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                            ])
                        ->whereIn('mf_scanner.classcode', $classcodeArr)->where('mf_scanner.status',1)->orderBy('s_name','ASC')->get();
            }
        }else if($input['type'] == 2){
            if($input['category_id'] == 0){
                $data['category_list'] = [];
                $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','mf_scanner.classcode','accord_sclass_mst.classname',
                                    'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                            ])
                        ->where('mf_scanner.status',1)->orderBy('s_name','ASC')->get();
            }else{
                $data['category_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name','asset_type'])
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                            ->where('accord_sclass_mst.classcode',$input['category_id'])->first();
                            
                $data['scheme_list'] = DB::table("mf_scanner")
                        ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_scanner.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->select(['schemecode','s_name','1dayret as oneday','1weekret as oneweek','1monthret as onemonth','3monthret as threemonth','6monthret as sixmonth',
                                    '1yrret as oneyear','2yearret as twoyear','3yearret as threeyear','5yearret as fiveyear','10yret as tenyear','mf_scanner.classcode','accord_sclass_mst.classname',
                                    'accord_sclass_mst.asset_type','mf_scanner_classcode.name as class_name'
                            ])
                        ->where('mf_scanner.status',1)->where('mf_scanner.classcode',$input['category_id'])->orderBy('s_name','ASC')->get();
            }
        }


        return response()->json($data);
        //dd($data);
    }

}

?>