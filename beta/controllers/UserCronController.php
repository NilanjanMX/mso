<?php

namespace App\Http\Controllers;

use App\Models\Displayinfo;
use App\Models\Membership;
use App\Models\PackageCreationSetting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserCronController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getAccordData($file_name,$date,$section){
        $token = "41J3y6vo6MOY577GDHs41Dehsv2NZEzG";
        echo "<br>"; 
        echo $endpoint = "https://contentapi.accordwebservices.com/RawData/GetTxtFile?filename=".$file_name."&section=".$section."&sub=&token=".$token."&date=".$date;
        // exit;
        echo "<br>";
        echo "Date : ".$date."--- File Name : ".$file_name;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        // dd($response);
        $statusCode = $response->getStatusCode();
        if($statusCode == 200){
            $content = json_decode($response->getBody());
            if($content){
                echo " --- Count : ".count($content->Table);
                return $content->Table;
            }else{
                echo " --- Count : 0";
                return [];
            }
            
        }else{
            echo " --- Count : No data";
            return [];
        }
    }
    
    public function updateMfPortfolio(Request $request,$date){
        // echo $date; exit;
        $count_start = $date;
        $date = 15102023;
        $count_end = $count_start + 5000;
        $list_data = $this->getAccordData('Mf_portfolio',$date,"MFPortfolio");
        //dd($list_data);
        $list_data_count = count($list_data);
        if($list_data_count){
            $count = count($list_data);
            if($count>$count_start){
                $count = ($count>=$count_end)?$count_end:$count;
                for($i=$count_start; $i<= $count;$i++){
                    echo "<br>".$i;
                    if(isset($list_data[$i])){
                        $value = $list_data[$i];
                        $detail = DB::table("accord_mf_portfolio22")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->first();
                        $insertData = (array)$value;
                        if($detail){
                            DB::table("accord_mf_portfolio22")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->update($insertData);
                        }else{
                            DB::table("accord_mf_portfolio22")->insert($insertData);
                        }
                    }
                }   
            }
        }
        exit;
    }

    public function updateNavhistData(){

        // $accord_navhist_demo = DB::table("accord_navhist_today")->groupBy('schemecode')->orderBy("schemecode","ASC")->get();
        
        $mf_scanner = DB::table('mf_scanner')->select(["schemecode"])->where('status','=', 1)->orderBy("schemecode","ASC")->get();
        // $mf_scanner_array = [];
        // foreach($mf_scanner as $value){
        //     array_push($mf_scanner_array,$value->schemecode);
        // }
        // DB::table('mf_navhist')->whereBetween('navdate', ["2023-09-01 00:00:00", "2023-10-31 00:00:00"])->delete();
        // dd($mf_scanner);
        
        foreach($mf_scanner as $key => $value){
            //if(in_array($value->schemecode,$mf_scanner_array)){

                echo $value->schemecode;
                echo "<br>";

                $accord_navhist = DB::table("accord_navhist_today")->where("schemecode",$value->schemecode)->get();
                
                if(count($accord_navhist)){
                    $insertData = [];
    
                    foreach ($accord_navhist as $key => $value) {
                        array_push($insertData, (array) $value);
                    }
                    DB::table("mf_navhist")->insert($insertData);
                    
                }
            //}
        }
        
        dd("ok");
        
        ini_set('memory_limit', -1);


        $mf_scanner = DB::table('mf_scanner')->select(["schemecode"])->where('status','=', 1)->orderBy("schemecode","ASC")->get();

        $mf_scanner_array = [];
        foreach($mf_scanner as $value){
            array_push($mf_scanner_array,$value->schemecode);
        }

        $accord_navhist_today = DB::table('accord_navhist')->get();
        
        foreach ($accord_navhist_today as $key => $value) {
            echo "<br>".$key;
            $insertData = (array)$value;
            if(in_array($value->schemecode,$mf_scanner_array)){
                DB::table("mf_navhist")->insert($insertData);
            }
        }

        exit;
        $file_name = "Navhist_28Dec2022.txt";
        $path = public_path('/uploads/navhist');
        
        $table_data = file_get_contents($path."/".$file_name);
        
        
        $table_data = json_decode($table_data);
        // dd($table_data);
        $list_data = $table_data->Table;
        $insertData = [];
        $i = 0;
        foreach ($list_data as $k1 => $v1) {
            echo "<br>".$k1."==".$v1->schemecode.", "; 
            $insertData = (array) $v1;
            DB::table('accord_navhist')->insert($insertData);
        }
        
        echo "ok";
    }
    
    public function updateMFPortfolioDataFromFile($count_start){
        
        ini_set('memory_limit', -1);
        $skip_number = $count_start * 50;
        $mf_scanner = DB::table('mf_scanner')->select(["schemecode"])->where('status','=', 1)->orderBy("schemecode","ASC")->skip($skip_number)->take(50)->get();
        
        
        // DB::table('mf_navhist')->whereBetween('navdate', ["2023-09-01 00:00:00", "2023-10-31 00:00:00"])->delete();
        // dd(count($mf_scanner));
        foreach($mf_scanner as $key => $value){
            //if(in_array($value->schemecode,$mf_scanner_array)){

                echo $value->schemecode;
                echo "<br>";

                $accord_navhist = DB::table("accord_navhist_today")->where("schemecode",$value->schemecode)->get();
                
                if(count($accord_navhist)){
                    $insertData = [];
    
                    foreach ($accord_navhist as $key => $value) {
                        array_push($insertData, (array) $value);
                    }
                    DB::table("mf_navhist")->insert($insertData);
                    
                }
            //}
        }
        
        dd("ok");
        
        // $file_name = "Navhist-oct.txt";
        // $path = public_path('/uploads/accord_zip/navhist');
        
        // $table_data = file_get_contents($path."/".$file_name);
        // $table_data = json_decode($table_data);
        // $list_data = $table_data->Table;
        
        // foreach($list_data as $value){
        //     $insertData = (array)$value;
        //     DB::table('accord_navhist_today')->insert($insertData);
        // }
        dd("ok");
        
        $count_end = $count_start + 20002;
        
        echo $file_name = "Navhist_08Mar_2023.txt";
        $path = public_path('/uploads/navhist');
        
        $table_data = file_get_contents($path."/".$file_name);
        $table_data = json_decode($table_data);
        $list_data = $table_data->Table;
        
        $table_name = "accord_mf_portfolio";
        echo "<br>";
        echo $count = count($list_data);
        echo "<br>";
        if($count>$count_start){
            $count = ($count>=$count_end)?$count_end:$count;
            for($i=$count_start; $i<= $count;$i++){
                echo "<br>".$i;
                if(isset($list_data[$i])){
                    $value = $list_data[$i];
                    dd($value);
                    $detail = DB::table($table_name)->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->first();
                    $insertData = (array)$value;
                    if($detail){
                        DB::table($table_name)->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->update($insertData);
                    }else{
                        DB::table($table_name)->insert($insertData);
                    }
                }
                    
            }   
        }
        echo $table_name; exit;
    }

    public function renewalNotificationMail(){
        
        $packageCreationSetting = PackageCreationSetting::where('id','1')->first();
        
        echo $days1 = $packageCreationSetting->subcription_expiry_reminder_day;
        echo $days2 = (int)($packageCreationSetting->subcription_expiry_reminder_day/2);
        
        echo $date['current_date1'] = date('Y-m-d', strtotime('+'.$days1.' days'));
        echo $date['current_date2'] = date('Y-m-d', strtotime('+'.$days2.' days'));
        echo $date['current_date3'] = date('Y-m-d');
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','paid')
                    ->where(function($query) use ($date) {
                        $query->where('memberships.expire_at', $date['current_date1'])
                              ->orWhere('memberships.expire_at', $date['current_date2'])
                              ->orWhere('memberships.expire_at', $date['current_date3']);
                    })
                    ->get();
        
        
        $dynamic_email = DB::table("dynamic_email")->where('id',2)->first();
        
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at))];
            // return view('emails.renewalNotification',$messageData);
            
            // exit;
            Mail::send('emails.renewalNotification',$messageData,function($message) use($email){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Renewal Notification');
            });
        }
        dd(count($membership));
        
    }
    
    public function updateMfCategoryWise(Request $request){
        ini_set('memory_limit', -1);
        echo $year =date('Y');
        $mf_category_wise_performance = DB::table('mf_category_wise_performance_olds')->get();
        // dd($mf_category_wise_performance);
        foreach($mf_category_wise_performance as $key => $value){
            echo $key."--".$value->schemecode."<br>";
            $inserData = [
                "year"=>$value->year,    
                "schemecode"=>$value->schemecode,    
                "nav"=>$value->nav,    
                "aum"=>$value->aum    
            ];
            
            // DB::table('mf_category_wise_performance')->insert($inserData);
        }
    }
    
    public function updateMfCategoryWise1(Request $request){
        ini_set('memory_limit', -1);
        echo $year =date('Y')-1;
        // DB::table('mf_category_wise_performance')->where("year",$year)->delete();
        $count = ($request->count)?$request->count:0;
        $skip_number = $count*100;
        $mf_scanner = DB::table('mf_scanner')->select(["schemecode"])->where('status','=', 1)->orderBy("schemecode","ASC")->skip($skip_number)->take(100)->get();
        
        foreach($mf_scanner as $key => $value){
            echo "<br>".$key;
            $mf_category_wise_performance_olds = DB::table('mf_category_wise_performance_olds')->where("schemecode",$value->schemecode)->first();
            if(!$mf_category_wise_performance_olds){
                $current_nav = 0;
                $pre_nav= 0;
                $accord_mf_portfolio = DB::table('mf_navhist')->whereYear('navdate', '=', $year)->where("schemecode",$value->schemecode)->orderBy('navdate','DESC')->first();
                if($accord_mf_portfolio){
                    $current_nav = $accord_mf_portfolio->navrs;
                }
                $accord_mf_portfolio = DB::table('mf_navhist')->whereYear('navdate', '=', $year-1)->where("schemecode",$value->schemecode)->orderBy('navdate','DESC')->first();
                
                if($accord_mf_portfolio){
                    $pre_nav = $accord_mf_portfolio->navrs;
                }
                
                echo "<br>nav = ".$current_nav."--".$pre_nav."--".$value->schemecode;
                
                if($current_nav && $pre_nav){
                    $current_nav = (float) $current_nav;
                    $pre_nav = (float) $pre_nav;
                    $nav = ($current_nav - $pre_nav)/ $pre_nav * 100;
                    $inserData = [
                        "year"=>$year,
                        "aum"=>$nav,
                        "nav"=>$current_nav,
                        "schemecode"=>$value->schemecode
                    ];
                    DB::table('mf_category_wise_performance_olds')->insert($inserData);
                }
            }
                
        }
        
        
        
    }
        
    public function updateMfCategoryWise12(Request $request){
        
        $mf_scanner = DB::table('mf_scanner')->select(["schemecode"])->where('status','=', 1)->get();
        $schemecodes =[];
        foreach($mf_scanner as $key => $value){
            array_push($schemecodes,$value->schemecode);
        }
        
        echo $date_from = date("2021-11-01");
        echo $date_to = date("2022-12-31");
        
        $result = DB::table('mf_navhist')->whereBetween('navdate', [$date_from, $date_to])->orderBy("navdate","ASC")->get();
        
        dd($schemecodes);
        
        
        $year =date('Y')-1;
        for($i=0; $i<10; $i++){
            // echo $year-$i."--";
            $accord_mf_portfolio = DB::table('accord_navhist')->whereYear('navdate', '=', $year-$i)->groupBy('schemecode')->orderBy('navdate','DESC')->get();
            foreach($accord_mf_portfolio as $value){
                $mf_category_wise_performance = DB::table('mf_category_wise_performance')->where('year',$year-$i)->where('schemecode',$value->schemecode)->first();
                if($mf_category_wise_performance){
                    $inserData = [
                        "aum"=>$value->aum
                    ];
                    DB::table('mf_category_wise_performance')->where('year',$year-$i)->where('schemecode',$value->schemecode)->update($inserData);
                }else{
                    $inserData = [
                        "year"=>$year-$i,
                        "aum"=>$value->aum,
                        "schemecode"=>$value->schemecode
                    ];
                    DB::table('mf_category_wise_performance')->insert($inserData);
                }
            }
        }
        
    }


    public function updateMfPortfolioAnalysis(){
        $mf_scanner = DB::table('mf_scanner')->select(["schemecode"])->where('status','=', 1)->get();
        echo count($mf_scanner)."<br>"; 
        foreach($mf_scanner as $key => $value){
            echo $key."--".$value->schemecode."<br>";
            
            $accord_mf_portfolio = DB::table('accord_mf_portfolio')
                ->select(["accord_mf_portfolio.*","accord_industry_mst.Sector","accord_industry_mst.Ind_code"])
                ->LeftJoin('accord_companymaster', 'accord_companymaster.fincode', '=', 'accord_mf_portfolio.fincode')
                ->leftJoin('accord_industry_mst','accord_companymaster.ind_code', '=', 'accord_industry_mst.Ind_code')
                ->where('accord_mf_portfolio.schemecode',$value->schemecode)
                ->orderBy('accord_mf_portfolio.invdate','DESC')
                ->groupBy('accord_mf_portfolio.fincode')
                ->get();
            // dd($accord_mf_portfolio);
            foreach($accord_mf_portfolio as $k1 => $v1){
                $insertData = [
                    "schemecode"=>$v1->schemecode,
                    "invdate"=>$v1->invdate,
                    "srno"=>$v1->srno,
                    "fincode"=>$v1->fincode,
                    "noshares"=>$v1->noshares,
                    "mktval"=>$v1->mktval,
                    "aum"=>$v1->aum,
                    "holdpercentage"=>$v1->holdpercentage,
                    "compname"=>$v1->compname,
                    "asect_name"=>$v1->asect_name,
                    "sector_name"=>$v1->Sector,
                    "ind_code"=>$v1->Ind_code,
                ];
                // dd($insertData);
                DB::table('mf_portfolio_analysis')->create($insertData);
                
            }
        }
        
    }

}
