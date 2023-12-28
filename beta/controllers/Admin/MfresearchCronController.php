<?php

namespace App\Http\Controllers\Admin;

use App\User;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class MfresearchCronController extends Controller
{
	public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table('mf_research_cron')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    if(!empty($row->date)){
                        $date = date('d-m-Y',strtotime($row->date));
                    }
                    return $date;
                })
                ->addColumn('type', function ($row) {
                    if($row->type == "M"){
                        $type = "Morning";
                    }else if($row->type == "N"){
                    	$type = "Night";
                    }else{
                    	$type = "";
                    }
                    return $type;
                })
                ->addColumn('status', function($row){
                    $btn = "Success";
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-accord-cron-history', 'detail')){
                    $btn = '<a href="'.route('webadmin.mf-research-cron-detail',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Detail</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['date','type','action','status'])
                ->make(true);
        }
        return view('admin.mf_research_cron.index');
    }

    public function detail(Request $request, $id){

    	// if ($request->ajax()) {
     //        // dd('hello1');
    	// 	$id = $request->id;
     //        $data = DB::table('mf_research_cron_details')->select(["mf_research_cron_details.id","mf_research_cron_details.mf_research_cron_id","mf_research_cron_details.date","mf_research_cron_details.status","mf_research_cron_details.accord_table_id","accord_tables.name as table_name","accord_tables.table_name as table_key"])->LeftJoin('accord_tables', 'accord_tables.id', '=', 'mf_research_cron_details.accord_table_id')->where("mf_research_cron_id",$id)->get();

     //        // dd($data);
     //        return Datatables::of($data)
     //            ->addIndexColumn()
     //            ->addColumn('status', function($row){
     //                if($row->status){
     //                    $btn = "Success";
     //                }else{
     //                    $btn = "Fail";
     //                }
                    
     //                return $btn;
     //            })
     //            ->addColumn('table_namess', function($row){
     //                $btn = "";
     //                if($row->accord_table_id <= 66){
     //                    $btn = $row->table_name;
     //                }else{
     //                    $count = ($row->accord_table_id == 67)?1:'';
     //                    $count .= ($row->accord_table_id == 68)?2:'';
     //                    $count .= ($row->accord_table_id == 69)?3:'';
     //                    $count .= ($row->accord_table_id == 70)?4:'';
     //                    $count .= ($row->accord_table_id == 71)?5:'';
     //                    $count .= ($row->accord_table_id == 72)?6:'';
     //                    $count .= ($row->accord_table_id == 73)?7:'';
     //                    $count .= ($row->accord_table_id == 74)?8:'';
     //                    $count .= ($row->accord_table_id == 75)?9:'';
     //                    $count .= ($row->accord_table_id == 76)?10:'';
     //                    $btn = "MF Portfolio ".$count;
     //                }
     //                return $btn;
     //            })
     //            ->addColumn('action', function($row){
     //                $btn = '<a href="'.route('webadmin.mf-research-cron-update',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1" onclick="return confirm(\'Are you sure?\')">Update</a>';
     //                return $btn;
     //            })
     //            ->rawColumns(['date','status','table_namess','action'])
     //            ->make(true);
     //    }
        $data['id'] = $id;
        return view('admin.mf_research_cron.details',$data);
    }

    public function detailTable(Request $request){

        
            // dd('hello1');
            $id = $request->id;
            
            $data = DB::table('mf_research_cron_details')->select(["mf_research_cron_details.id","mf_research_cron_details.mf_research_cron_id","mf_research_cron_details.date","mf_research_cron_details.status","mf_research_cron_details.accord_table_id","accord_tables.name as table_name","accord_tables.table_name as table_key"])->LeftJoin('accord_tables', 'accord_tables.id', '=', 'mf_research_cron_details.accord_table_id')->where("mf_research_cron_id",$id)->get();

            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    if($row->status){
                        $btn = "Success";
                    }else{
                        $btn = "Fail";
                    }
                    
                    return $btn;
                })
                ->addColumn('table_namess', function($row){
                    $btn = "";
                    if($row->accord_table_id <= 66){
                        $btn = $row->table_name;
                    }else{
                        $count = ($row->accord_table_id == 67)?1:'';
                        $count .= ($row->accord_table_id == 68)?2:'';
                        $count .= ($row->accord_table_id == 69)?3:'';
                        $count .= ($row->accord_table_id == 70)?4:'';
                        $count .= ($row->accord_table_id == 71)?5:'';
                        $count .= ($row->accord_table_id == 72)?6:'';
                        $count .= ($row->accord_table_id == 73)?7:'';
                        $count .= ($row->accord_table_id == 74)?8:'';
                        $count .= ($row->accord_table_id == 75)?9:'';
                        $count .= ($row->accord_table_id == 76)?10:'';
                        $btn = "MF Portfolio ".$count;
                    }
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('webadmin.mf-research-cron-update',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1" onclick="return confirm(\'Are you sure?\')">Update</a>';
                    return $btn;
                })
                ->rawColumns(['date','status','table_namess','action'])
                ->make(true);
        if ($request->ajax()) {}
        $data['id'] = $id;
        return view('admin.mf_research_cron.details',$data);
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
    
    public function update(Request $request, $id){
        $mf_research_cron_details = DB::table('mf_research_cron_details')->where('id',$id)->first();
        $accord_tables = DB::table('accord_tables')->where('id',$mf_research_cron_details->accord_table_id)->first();
        
        // dd($accord_tables);
        
        toastr()->success('Updated successfully.');
        return redirect()->route('webadmin.mf-research-cron-detail',[$mf_research_cron_details->mf_research_cron_id]);
    }

    public function list(Request $request){
        if ($request->ajax()) {
            //$data = DB::table('mf_research_cron_list')->where("status","1")->latest()->get();          
            $data = DB::table('mf_research_cron_list')->where("cron_type","=","m")->latest()->get();          

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-live-report-cron', 'start_cron')){
                        // if($row->id==1)
                        // {
                        //     $btn .= '<a href="javascript:void(0);" class="edit btn btn-primary btn-sm mr-1" onclick="openStartCronModal('.$row->id.');">Start Cron</a>';
                        // }
                        // else
                        // {
                        //     $btn = '<a href="'.route('webadmin.mf-research-cron-start',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Start Cron</a>';
                        // }
                    $btn = '<a href="'.route('webadmin.mf-research-cron-start',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Start Cron</a>';
                   
                    }
                    if(is_permitted('mf-research-live-report-cron', 'edit')){
                    $btn .= '<a href="javascript:void(0);" class="edit btn btn-primary btn-sm mr-1" onclick="openChangeDesctiptionModa('.$row->id.',\''.$row->description.'\');">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name','description','action'])
                ->make(true);

        }
        return view('admin.mf_research_cron.list');
    }

    public function listStaticCron(Request $request){
        if ($request->ajax()) {
            //$data = DB::table('mf_research_cron_list')->where("cron_type","=","s")->get(); 
            $data = DB::table('mf_research_cron_list')
                ->select(['mf_research_cron_list.*', 'mf_static_cron.status'])
                ->leftJoin('mf_static_cron', 'mf_static_cron.research_cron_id', '=', 'mf_research_cron_list.id')
                ->where('mf_research_cron_list.cron_type', '=', 's')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-live-report-cron', 'start_cron')){
                        if($row->id==1 || $row->id==2 || $row->id==3 || $row->id==7)
                        {
                            if($row->status=='ongoing')
                            {
                                $mf_static_cron = DB::table('mf_static_cron')->where("research_cron_id","=",$row->id)->first(); 

                                $start_time = $mf_static_cron->start_time;
                                $page_count = $mf_static_cron->page_count;
                                $cron_interval_time = $mf_static_cron->cron_interval_time;
                                $no_of_loop = $mf_static_cron->no_of_loop;
                                //$estimated_time = $mf_static_cron->estimated_time;
                                $estimated_time = ($no_of_loop*$cron_interval_time);
                                
                                $btn .= '<span class="edit btn btn-primary btn-sm mr-1 disabled" title="Cron Already Running. It starts at '.$start_time.' and will take around '.$estimated_time.' minutes to complete.">Start Cron</span>';
                                //$btn .= 'Cron Already Running. It starts at '.$start_time.' and will take around '.$estimated_time.' minutes. ';
                            }
                            else
                            {
                                $btn .= '<a href="javascript:void(0);" class="edit btn btn-primary btn-sm mr-1" onclick="openStartCronModal('.$row->id.');">Start Cron</a>';
                            }
                            
                        }
                        // else
                        // {
                        //     $btn = '<a href="'.route('webadmin.mf-research-cron-start',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Start Cron</a>';
                        // }
                    // $btn = '<a href="'.route('webadmin.mf-research-cron-start',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Start Cron</a>';
                   
                    }
                    if(is_permitted('mf-research-live-report-cron', 'edit')){
                    $btn .= '<a href="javascript:void(0);" class="edit btn btn-primary btn-sm mr-1" onclick="openChangeDesctiptionModa('.$row->id.',\''.$row->description.'\');">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name','description','action'])
                ->make(true);

        }
        return view('admin.mf_research_cron.list');
    }

    public function update_description(Request $request){
        $input = $request->all();
        // dd($input);
        $insertData = [
            'description' => $request->description
        ];

        DB::table("mf_research_cron_list")->where("id",$request->id)->update($insertData);

        toastr()->success('Description successfully Updated.');
        return redirect()->route('webadmin.mf-research-cron-list');
    }

    public function cron_history(Request $request){
        // $data = DB::table('mf_research_cron_histories')->select(["mf_research_cron_list.name","mf_research_cron_histories.*"])->LeftJoin('mf_research_cron_list', 'mf_research_cron_list.id', '=', 'mf_research_cron_histories.mf_research_cron_id')->latest()->get();
        // dd($data);
        if ($request->ajax()) {
            $data = DB::table('mf_research_cron_histories')->select(["mf_research_cron_list.name","mf_research_cron_histories.*"])->LeftJoin('mf_research_cron_list', 'mf_research_cron_list.id', '=', 'mf_research_cron_histories.mf_research_cron_id')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    if(!empty($row->date)){
                        $date = date('d-m-Y h:s',strtotime($row->date));
                    }
                    return $date;
                })
                ->addColumn('status', function($row){
                    $btn = "Success";
                    return $btn;
                })
                ->rawColumns(['name','date','status'])
                ->make(true);
        }
        return view('admin.mf_research_cron.cron_histories');
    }

    public function start(Request $request){

        $mf_scanner_cron = DB::table('mf_scanner_cron')->where("id","=","2")->first();

        if($mf_scanner_cron->status == 0){
            $mf_research_cron_list = DB::table('mf_research_cron_list')->where("id",$request->id)->first();

            switch ($mf_research_cron_list->type) {
                case 1:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>4,"page_number"=>0,"flag_count"=>1]);
                    break;
                case 2:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>5,"page_number"=>0]);
                    break;
                case 3:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>6,"page_number"=>0]);
                    break;
                case 4:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>7,"page_number"=>0]);
                    break;
                case 5:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>1,"page_number"=>0]);
                    break;
                case 6:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>1,"page_number"=>0]);
                    break;
                case 7:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>8,"page_number"=>0]);
                    break;
                case 8:
                    DB::table("mf_scanner_cron")->where("id","=",2)->update(['status'=>0,"page_number"=>0]);
                    break;
                
                default:
                    # code...
                    break;
            }
            toastr()->success('Cron Started Successfully.');
            return redirect()->route('webadmin.mf-research-cron-list');
        }else{
            toastr()->warning('Cron Already Started.');
            return redirect()->route('webadmin.mf-research-cron-list');
        }
    }


    public function allStaticCronStart(Request $request){
        //dd($request);
        $id = $request->id;
        $mf_static_cron = DB::table('mf_static_cron')->where("research_cron_id",$id)->first();
        if($mf_static_cron->status!='ongoing')
        {
            if($id==1) //Factsheet
            {
                $fetchData = DB::table('mf_scanner')->select(["schemecode","primary_fd_code"])->where('status','=', 1)->orderBy("schemecode","ASC")->groupBy("primary_fd_code")->get();
            }
            if($id==7) //Stocks Bought
            {
                $fetchData = DB::table('accord_portfolio_inout')
                ->select(['accord_portfolio_inout.invdate','accord_portfolio_inout.compname','accord_portfolio_inout.noshares','accord_portfolio_inout.mktval','accord_portfolio_inout.fincode','accord_companymcap.mode','accord_companymaster.Industry'])
                ->LeftJoin('accord_companymcap', 'accord_companymcap.fincode', '=', 'accord_portfolio_inout.fincode')
                ->LeftJoin('accord_companymaster', 'accord_companymaster.fincode', '=', 'accord_portfolio_inout.fincode')
                ->orderBy('accord_portfolio_inout.mktval','DESC')->get();
            }
            if($id==3) //Debt Held
            {
                $fetchData = DB::table("accord_gsecmaster")->select(['gsec_code','maturity_date','fincode'])->where('flag','=','A')
                ->where("maturity_date","!=","")->where("maturity_date","!=",NULL)
                ->where("securities","!=","")
                ->whereNotIn("status",["Derivatives", "MFU(O)", "GDS"])
                ->orderBy("gsec_code","ASC")
                ->get();
            }
            
            $total_records = count($fetchData); 

            $record_per_loop = $mf_static_cron->record_per_loop;

            $page_number = ceil($total_records/$record_per_loop);
            $no_of_loop = ($page_number+1);

            $cron_interval_time = $mf_static_cron->cron_interval_time;

            $estimated_time = ($no_of_loop*$cron_interval_time);

            $currentdate = date('Y-m-d H:i:s');

            DB::table("mf_static_cron")->where("research_cron_id","=",$id)->update(['page_count'=>0,'status'=>'ongoing','no_of_entry'=>$total_records,'no_of_loop'=>$no_of_loop,'start_time'=>$currentdate,'estimated_time'=>$estimated_time]);

            toastr()->success('Cron Started Successfully.');
            return redirect()->route('webadmin.mf-research-cron-list');
        }
        else
        {
            toastr()->warning('Cron Already Started.');
            return redirect()->route('webadmin.mf-research-cron-list');
        }
    }

}
