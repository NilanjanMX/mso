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
            $data = DB::table('mf_research_cron_list')->where("status","1")->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-live-report-cron', 'start_cron')){
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

}
