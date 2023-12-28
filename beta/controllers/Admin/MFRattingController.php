<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

use DB;
use Response;


class MFRattingController extends Controller
{
    
    public function __construct(){
        //$this->middleware('auth');  
    }
    
    public function index(Request $request){
        $data = [];
        $data['type'] = $request->type;
        $data['classcode'] = $request->classcode;
        $data['action'] = $request->action;
        $data['result'] = [];

        if($data['action']){
            $date = date("Y-m-d H:i:s");
            $date = date('Y-m-d H:i:s', strtotime("-36 months", strtotime($date)));
            $result = DB::table("mf_ratting_scanner")
                            ->select(['mf_ratting_scanner.*','mf_scanner_classcode.name as class_name','accord_sclass_mst.asset_type'])
                            ->LeftJoin('accord_sclass_mst', 'accord_sclass_mst.classcode', '=', 'mf_ratting_scanner.classcode')
                            ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_ratting_scanner.classcode')
                            ->where("total",">",20000)
                            ->where("Incept_date","<",$date);

            if($data['type']){
                $result = $result->where('accord_sclass_mst.asset_type','=',$data['type']);
            }

            if($data['classcode']){
                $result = $result->where('mf_ratting_scanner.classcode','=',$data['classcode']);
            }

            $data['result'] = $result->get();

            if($data['action'] == "DOWNLOAD"){
                $filename = "mf-ratting-scheme";
                $handle = fopen("./storage/app/".$filename, 'w');

                fputcsv($handle, array('SN', 'Scheme Name', 'Asset Type', 'Category Name', 'AUM', 'AUM Date'));
                $subscription_type = "";
                foreach($data['result'] as $key=>$row) {
                    fputcsv($handle, array(
                        $key+1, 
                        $row->s_name, 
                        $row->asset_type, 
                        ($row->class_name)?$row->class_name:$row->classname, 
                        $row->total,
                        $row->total_date
                    ));
                }
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
                return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            }
        }
        $data['class_list'] = DB::table("mf_ratting_category")
                        ->select(['mf_ratting_category.classcode','mf_ratting_category.asset_type','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_ratting_category.classcode')
                        ->where('mf_ratting_category.status',1)
                        ->whereIn('asset_type',['Debt','Equity','Hybrid'])
                        ->orderBy('classname', 'asc')->get();
        $data['type_list'] = ['Debt','Equity','Hybrid'];


        return view('admin.mf_ratting.index',$data);
    }

    public function downloadCSV(Request $request){
        $type = $request->type;
        $classcode = $request->classcode;
    }

    public function equity(){
        $data['type'] =  DB::table('mf_ratting_types')
                        ->where("mf_ratting_types.is_equity",1)->get();

        $data['class_list'] = DB::table("mf_ratting_category")
                        ->select(['mf_ratting_category.classcode','mf_ratting_category.asset_type','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_ratting_category.classcode')
                        ->where('mf_ratting_category.status',1)
                        ->where('asset_type','Equity')
                        ->orderBy('classname', 'asc')->get();

        $mf_ratting_all = DB::table('mf_ratting_all')->where("assert_id",1)->get();
        $data['result'] = [];
        foreach ($mf_ratting_all as $key => $value) {
            $data['result'][$value->type_id][$value->classcode]['value'] = $value->values;
            $data['result'][$value->type_id][$value->classcode]['flag'] = $value->flag;
        }
        // dd($data['class_list']);
        return view('admin.mf_ratting.equity',$data);
    }

    public function update_equity(Request $request){
        
        $input = $request->all();

        $type_id = $request->type_id;
        $large_cap = $request->large_cap;
        $large_cap_flag = $request->large_cap_flag;
        DB::table('mf_ratting_all')->where("assert_id",1)->delete();
        foreach ($large_cap as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $insertData = [];
                $insertData['assert_id'] = 1;
                $insertData['type_id'] = $key1;
                $insertData['classcode'] = $key2;
                $insertData['values'] = $value2;
                $insertData['flag'] = $large_cap_flag[$key1][$key2];

                DB::table('mf_ratting_all')->insert($insertData);
            }
        }

        toastr()->success('Successfully updated.');
        return redirect()->back()->withInput();
    }

    public function debt(){
        $data['type'] =  DB::table('mf_ratting_types')
                        ->where("mf_ratting_types.is_debt",1)->get();

        $data['class_list'] = DB::table("mf_ratting_category")
                        ->select(['mf_ratting_category.classcode','mf_ratting_category.asset_type','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_ratting_category.classcode')
                        ->where('mf_ratting_category.status',1)
                        ->where('asset_type','Debt')
                        ->orderBy('classname', 'asc')->get();

        $mf_ratting_all = DB::table('mf_ratting_all')->where("assert_id",2)->get();
        $data['result'] = [];
        foreach ($mf_ratting_all as $key => $value) {
            $data['result'][$value->type_id][$value->classcode]['value'] = $value->values;
            $data['result'][$value->type_id][$value->classcode]['flag'] = $value->flag;
        }
        return view('admin.mf_ratting.debt',$data);
    }

    public function update_debt(Request $request){
        
        $input = $request->all();

        $type_id = $request->type_id;
        $large_cap = $request->large_cap;
        $large_cap_flag = $request->large_cap_flag;
        DB::table('mf_ratting_all')->where("assert_id",2)->delete();
        foreach ($large_cap as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $insertData = [];
                $insertData['assert_id'] = 2;
                $insertData['type_id'] = $key1;
                $insertData['classcode'] = $key2;
                $insertData['values'] = $value2;
                $insertData['flag'] = $large_cap_flag[$key1][$key2];

                DB::table('mf_ratting_all')->insert($insertData);
            }
        }

        toastr()->success('Successfully updated.');
        return redirect()->back()->withInput();
    }

    public function hybrid(){
        $data['type'] =  DB::table('mf_ratting_types')
                        ->where("mf_ratting_types.is_hybrid",1)->get();

        $data['class_list'] = DB::table("mf_ratting_category")
                        ->select(['mf_ratting_category.classcode','mf_ratting_category.asset_type','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'mf_ratting_category.classcode')
                        ->where('mf_ratting_category.status',1)
                        ->where('asset_type','Hybrid')
                        ->orderBy('classname', 'asc')->get();

        $mf_ratting_all = DB::table('mf_ratting_all')->where("assert_id",3)->get();
        $data['result'] = [];
        foreach ($mf_ratting_all as $key => $value) {
            $data['result'][$value->type_id][$value->classcode]['value'] = $value->values;
            $data['result'][$value->type_id][$value->classcode]['flag'] = $value->flag;
        }
        return view('admin.mf_ratting.hybrid',$data);
    }

    public function update_hybrid(Request $request){
        
        $input = $request->all();

        $type_id = $request->type_id;
        $large_cap = $request->large_cap;
        $large_cap_flag = $request->large_cap_flag;
        DB::table('mf_ratting_all')->where("assert_id",3)->delete();
        foreach ($large_cap as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $insertData = [];
                $insertData['assert_id'] = 3;
                $insertData['type_id'] = $key1;
                $insertData['classcode'] = $key2;
                $insertData['values'] = $value2;
                $insertData['flag'] = $large_cap_flag[$key1][$key2];

                DB::table('mf_ratting_all')->insert($insertData);
            }
        }

        toastr()->success('Successfully updated.');
        return redirect()->back()->withInput();
    }

    public function point(Request $request){
        $data = [];
        if ($request->ajax()) {
            $data = DB::table("mf_ratting_point")
                    ->select(["mf_ratting_point.*","mf_ratting_scanner.s_name"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting_point.schemecode')
                    ->orderBy('s_name','ASC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-rating-point', 'edit')){
                    $btn = '<a href="'.route('webadmin.mf_rating_point_edit',['id'=> $row->schemecode ]).'" class="btn btn-danger btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['schemecode','s_name','short_name','action'])
                ->make(true);
        }
        return view('admin.mf_ratting.point',$data);
    }

    public function point_edit(Request $request){

        $data['detail'] = DB::table("mf_ratting_point")
                    ->select(["mf_ratting_point.*","mf_ratting_scanner.s_name"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting_point.schemecode')
                    ->where("mf_ratting_point.schemecode",$request->id)
                    ->first();

        $data["form_data"] = [
            ["key"=>"performance_1_year","name"=>"Performance 1 Year","value"=>$data['detail']->performance_1_year],
            ["key"=>"performance_3_year","name"=>"Performance 3 Year","value"=>$data['detail']->performance_3_year],
            ["key"=>"alpha","name"=>"Alpha","value"=>$data['detail']->alpha],
            ["key"=>"standard_deviation","name"=>"Standard Deviation","value"=>$data['detail']->standard_deviation],
            ["key"=>"sharpe","name"=>"Sharpe","value"=>$data['detail']->sharpe],
            ["key"=>"treynor","name"=>"Treynor","value"=>$data['detail']->treynor],
            ["key"=>"sortino","name"=>"Sortino","value"=>$data['detail']->sortino],
            ["key"=>"beta","name"=>"Beta","value"=>$data['detail']->beta],
            ["key"=>"pe_ratio","name"=>"PE Ratio","value"=>$data['detail']->pe_ratio],
            ["key"=>"pb_ratio","name"=>"PB Ratio","value"=>$data['detail']->pb_ratio],
            ["key"=>"expense_ratio","name"=>"Expense Ratio","value"=>$data['detail']->expense_ratio],
            ["key"=>"top_3_sector_concentration","name"=>"Top 3 Sector Concentration","value"=>$data['detail']->top_3_sector_concentration],
            ["key"=>"top_10_holding_concentration","name"=>"Top 10 Holding Concentration","value"=>$data['detail']->top_10_holding_concentration],
            ["key"=>"credit_for_5_yr_existance","name"=>"Credit for 5 Yr Existance","value"=>$data['detail']->credit_for_5_yr_existance],
            ["key"=>"credit_for_10_yr_existance","name"=>"Credit for 10 Yr Existance","value"=>$data['detail']->credit_for_10_yr_existance],
            ["key"=>"credit_for_15_yr_existance","name"=>"Credit for 15 Yr Existance","value"=>$data['detail']->credit_for_15_yr_existance],
            ["key"=>"turnover_ratio","name"=>"Turnover Ratio","value"=>$data['detail']->turnover_ratio],
            ["key"=>"fund_manager","name"=>"Fund Manager","value"=>$data['detail']->fund_manager],
            ["key"=>"scheme_aum","name"=>"Scheme AUM","value"=>$data['detail']->scheme_aum],
            ["key"=>"amc_aum","name"=>"AMC AUM","value"=>$data['detail']->amc_aum],
            ["key"=>"tracking_error","name"=>"Tracking Error","value"=>$data['detail']->tracking_error],
            ["key"=>"mso_special_consideration","name"=>"MSO Special Consideration","value"=>$data['detail']->mso_special_consideration],
            ["key"=>"performance_7_days","name"=>"Performance 7 Days","value"=>$data['detail']->performance_7_days],
            ["key"=>"performance_1_mth","name"=>"Performance 1 Mth","value"=>$data['detail']->performance_1_mth],
            ["key"=>"performance_3_mths","name"=>"Performance 3 Mths","value"=>$data['detail']->performance_3_mths],
            ["key"=>"performance_6_mths","name"=>"Performance 6 Mths","value"=>$data['detail']->performance_6_mths],
            ["key"=>"ytm","name"=>"YTM","value"=>$data['detail']->ytm],
            ["key"=>"modified_duration","name"=>"Modified Duration","value"=>$data['detail']->modified_duration],
            ["key"=>"credit_rating_of_holdings","name"=>"Credit Rating of holdings","value"=>$data['detail']->credit_rating_of_holdings]
        ];
        // dd($data);
        return view('admin.mf_ratting.edit_point',$data);

    }

    public function point_update(Request $request){
        $input = $request->all();
        // dd($input);

        $id = $request->id;
        $insertData = [
                "performance_1_year"=>$request->performance_1_year,
                "performance_3_year"=>$request->performance_3_year,
                "alpha"=>$request->alpha,
                "standard_deviation"=>$request->standard_deviation,
                "sharpe"=>$request->sharpe,
                "treynor"=>$request->treynor,
                "sortino"=>$request->sortino,
                "beta"=>$request->beta,
                "pe_ratio"=>$request->pe_ratio,
                "pb_ratio"=>$request->pb_ratio,
                "expense_ratio"=>$request->expense_ratio,
                "turnover_ratio"=>$request->turnover_ratio,
                "scheme_aum"=>$request->scheme_aum,
                "amc_aum"=>$request->amc_aum,
                "performance_7_days"=>$request->performance_7_days,
                "performance_1_mth"=>$request->performance_1_mth,
                "performance_3_mths"=>$request->performance_3_mths,
                "performance_6_mths"=>$request->performance_6_mths,
                "ytm"=>$request->ytm,
                "modified_duration"=>$request->modified_duration
            ];

        DB::table("mf_ratting_point")->where("schemecode",$id)->update($insertData);

        toastr()->success('MF Ratting point successfully updated.');
        return redirect()->route('webadmin.mf_rating_point');
    }

    public function export(Request $request){

        $filename = "mf-ratting-point";
        $handle = fopen("./storage/app/".$filename, 'w');
        

        $date = date("Y-m-d H:i:s");
        $date = date('Y-m-d H:i:s', strtotime("-36 months", strtotime($date)));

        $result = DB::table("mf_ratting_point")
                    ->select(["mf_ratting_point.*","mf_ratting_scanner.s_name","mf_ratting_scanner.classcode"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting_point.schemecode')
                    ->orderBy('mf_ratting_scanner.s_name','ASC')->get();

        // dd($result);
        fputcsv($handle, array(
                'SN', 
                'Scheme Name',  
                'Rating',  
                'Performance 1 Year',
                'Performance 3 Year',
                'Alpha',
                'Standard Deviation',
                'Sharpe',
                'Treynor',
                'Sortino',
                'Beta',
                'PE Ratio',
                'PB Ratio',
                'Expense Ratio',
                'Top 3 Sector Concentration',
                'Top 10 Holding Concentration',
                'Credit for 5 Yr Existance',
                'Credit for 10 Yr Existance',
                'Credit for 15 Yr Existance',
                'Turnover Ratio',
                'Fund Manager',
                'Scheme AUM',
                'AMC AUM',
                'Tracking Error',
                'MSO Special Consideration',
                'Performance 7 Days',
                'Performance 1 Mth',
                'Performance 3 Mths',
                'Performance 6 Mths',
                'YTM',
                'Modified Duration',
                'Credit Rating of holdings',
            ));

        foreach($result as $key=>$row) {
            fputcsv($handle, array(
                $key+1, 
                $row->s_name, 
                $row->classcode,
                $row->performance_1_year,
                $row->performance_3_year,
                $row->alpha,
                $row->standard_deviation,
                $row->sharpe,
                $row->treynor,
                $row->sortino,
                $row->beta,
                $row->pe_ratio,
                $row->pb_ratio,
                $row->expense_ratio,
                $row->top_3_sector_concentration,
                $row->top_10_holding_concentration,
                $row->credit_for_5_yr_existance,
                $row->credit_for_10_yr_existance,
                $row->credit_for_15_yr_existance,
                $row->turnover_ratio,
                $row->fund_manager,
                $row->scheme_aum,
                $row->amc_aum,
                $row->tracking_error,
                $row->mso_special_consideration,
                $row->performance_7_days,
                $row->performance_1_mth,
                $row->performance_3_mths,
                $row->performance_6_mths,
                $row->ytm,
                $row->modified_duration,
                $row->credit_rating_of_holdings
            ));
        }
        // dd("row");
        fclose($handle);


        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
    }

    public function score(Request $request){
        $data = [];
        if ($request->ajax()) {
            $data = DB::table("mf_ratting_score")
                    ->select(["mf_ratting_score.*","mf_ratting_scanner.s_name"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting_score.schemecode')
                    ->orderBy('s_name','ASC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-rating-score', 'edit')){
                    $btn = '<a href="'.route('webadmin.mf_rating_score_edit',['id'=> $row->schemecode ]).'" class="btn btn-danger btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['schemecode','s_name','short_name','action'])
                ->make(true);
        }
        return view('admin.mf_ratting.score',$data);
    }

    public function score_edit(Request $request){

        $data['detail'] = DB::table("mf_ratting_score")
                    ->select(["mf_ratting_score.*","mf_ratting_scanner.s_name"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting_score.schemecode')
                    ->where("mf_ratting_score.schemecode",$request->id)
                    ->first();

        $data["form_data"] = [
            ["key"=>"performance_1_year","name"=>"Performance 1 Year","value"=>$data['detail']->performance_1_year],
            ["key"=>"performance_3_year","name"=>"Performance 3 Year","value"=>$data['detail']->performance_3_year],
            ["key"=>"alpha","name"=>"Alpha","value"=>$data['detail']->alpha],
            ["key"=>"standard_deviation","name"=>"Standard Deviation","value"=>$data['detail']->standard_deviation],
            ["key"=>"sharpe","name"=>"Sharpe","value"=>$data['detail']->sharpe],
            ["key"=>"treynor","name"=>"Treynor","value"=>$data['detail']->treynor],
            ["key"=>"sortino","name"=>"Sortino","value"=>$data['detail']->sortino],
            ["key"=>"beta","name"=>"Beta","value"=>$data['detail']->beta],
            ["key"=>"pe_ratio","name"=>"PE Ratio","value"=>$data['detail']->pe_ratio],
            ["key"=>"pb_ratio","name"=>"PB Ratio","value"=>$data['detail']->pb_ratio],
            ["key"=>"expense_ratio","name"=>"Expense Ratio","value"=>$data['detail']->expense_ratio],
            ["key"=>"turnover_ratio","name"=>"Turnover Ratio","value"=>$data['detail']->turnover_ratio],
            ["key"=>"scheme_aum","name"=>"Scheme AUM","value"=>$data['detail']->scheme_aum],
            ["key"=>"amc_aum","name"=>"AMC AUM","value"=>$data['detail']->amc_aum],
            ["key"=>"performance_7_days","name"=>"Performance 7 Days","value"=>$data['detail']->performance_7_days],
            ["key"=>"performance_1_mth","name"=>"Performance 1 Mth","value"=>$data['detail']->performance_1_mth],
            ["key"=>"performance_3_mths","name"=>"Performance 3 Mths","value"=>$data['detail']->performance_3_mths],
            ["key"=>"performance_6_mths","name"=>"Performance 6 Mths","value"=>$data['detail']->performance_6_mths],
            ["key"=>"ytm","name"=>"YTM","value"=>$data['detail']->ytm],
            ["key"=>"modified_duration","name"=>"Modified Duration","value"=>$data['detail']->modified_duration],
            ["key"=>"total_score","name"=>"Total Score","value"=>$data['detail']->total_score]
        ];
        // dd($data);
        return view('admin.mf_ratting.edit_score',$data);

    }

    public function score_update(Request $request){
        $input = $request->all();
        // dd($input);

        $id = $request->id;
        $insertData = [
                "performance_1_year"=>$request->performance_1_year,
                "performance_3_year"=>$request->performance_3_year,
                "alpha"=>$request->alpha,
                "standard_deviation"=>$request->standard_deviation,
                "sharpe"=>$request->sharpe,
                "treynor"=>$request->treynor,
                "sortino"=>$request->sortino,
                "beta"=>$request->beta,
                "pe_ratio"=>$request->pe_ratio,
                "pb_ratio"=>$request->pb_ratio,
                "expense_ratio"=>$request->expense_ratio,
                "turnover_ratio"=>$request->turnover_ratio,
                "scheme_aum"=>$request->scheme_aum,
                "amc_aum"=>$request->amc_aum,
                "performance_7_days"=>$request->performance_7_days,
                "performance_1_mth"=>$request->performance_1_mth,
                "performance_3_mths"=>$request->performance_3_mths,
                "performance_6_mths"=>$request->performance_6_mths,
                "ytm"=>$request->ytm,
                "modified_duration"=>$request->modified_duration,
                "total_score"=>$request->total_score
            ];

        DB::table("mf_ratting_score")->where("schemecode",$id)->update($insertData);

        toastr()->success('MF Ratting point successfully updated.');
        return redirect()->route('webadmin.mf_rating_score');
    }

    public function score_export(Request $request){

        $filename = "mf-ratting-score";
        $handle = fopen("./storage/app/".$filename, 'w');
        
        $result = DB::table("mf_ratting_score")
                    ->select(["mf_ratting_score.*","mf_ratting_scanner.s_name","mf_ratting_scanner.classcode"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting_score.schemecode')
                    ->orderBy('mf_ratting_scanner.s_name','ASC')->get();

        // dd($result);
        fputcsv($handle, array(
                'SN', 
                'Scheme Name', 
                'Rating',  
                'Performance 1 Year',
                'Performance 3 Year',
                'Alpha',
                'Standard Deviation',
                'Sharpe',
                'Treynor',
                'Sortino',
                'Beta',
                'PE Ratio',
                'PB Ratio',
                'Expense Ratio',
                'Top 3 Sector Concentration',
                'Top 10 Holding Concentration',
                'Credit for 5 Yr Existance',
                'Credit for 10 Yr Existance',
                'Credit for 15 Yr Existance',
                'Turnover Ratio',
                'Fund Manager',
                'Scheme AUM',
                'AMC AUM',
                'Tracking Error',
                'MSO Special Consideration',
                'Performance 7 Days',
                'Performance 1 Mth',
                'Performance 3 Mths',
                'Performance 6 Mths',
                'YTM',
                'Modified Duration',
                'Credit Rating of holdings',
                'Total'
            )
        );

        foreach($result as $key=>$row) {
            fputcsv($handle, array(
                $key+1, 
                $row->s_name, 
                $row->classcode,
                $row->performance_1_year,
                $row->performance_3_year,
                $row->alpha,
                $row->standard_deviation,
                $row->sharpe,
                $row->treynor,
                $row->sortino,
                $row->beta,
                $row->pe_ratio,
                $row->pb_ratio,
                $row->expense_ratio,
                $row->top_3_sector_concentration,
                $row->top_10_holding_concentration,
                $row->credit_for_5_yr_existance,
                $row->credit_for_10_yr_existance,
                $row->credit_for_15_yr_existance,
                $row->turnover_ratio,
                $row->fund_manager,
                $row->scheme_aum,
                $row->amc_aum,
                $row->tracking_error,
                $row->mso_special_consideration,
                $row->performance_7_days,
                $row->performance_1_mth,
                $row->performance_3_mths,
                $row->performance_6_mths,
                $row->ytm,
                $row->modified_duration,
                $row->credit_rating_of_holdings,
                $row->total_score
            ));
        }
        // dd("row");
        fclose($handle);


        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
    }

    public function point_history(Request $request){
        $data = [];
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['schemecode'] = $request->schemecode;
        $data['result'] = [];

        $data['scanner_list'] = DB::table("mf_ratting_scanner")->select(['schemecode','s_name'])->get();

        // dd($data);
        return view('admin.mf_ratting.point_history',$data);
    }
    
    public function point_history_date(Request $request){
        $data = [];
        if ($request->ajax()) {
            $data = DB::table("mf_ratting_point_history")
                    ->groupBy('date')
                    ->orderBy('date','DESC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-rating-point-history-delete', 'delete')){
                    $btn = '<a href="'.route('webadmin.mf_rating_point_history_delete',['id'=> $row->date ]).'" class="btn btn-danger btn-sm mr-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['date','action'])
                ->make(true);
        }
        return view('admin.mf_ratting.delete_point',$data);
    }

    public function point_history_delete(Request $request){

        DB::table("mf_ratting_point_history")->where('date',$request->id)->delete();

        toastr()->success('MF Rating point history successfully deleted.');
        return redirect()->route('webadmin.mf_rating_point_history_date');
    }

    public function score_history(Request $request){
        $data = [];
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['schemecode'] = $request->schemecode;
        $data['result'] = [];

        $data['scanner_list'] = DB::table("mf_ratting_scanner")->select(['schemecode','s_name'])->get();

        // dd($data);
        return view('admin.mf_ratting.point_history',$data);
    }

    public function score_history_date(Request $request){
        $data = [];
        if ($request->ajax()) {
            $data = DB::table("mf_ratting_score_history")
                    ->groupBy('date')
                    ->orderBy('date','DESC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-rating-score-history-delete', 'delete')){
                    $btn = '<a href="'.route('webadmin.mf_rating_score_history_delete',['id'=> $row->date ]).'" class="btn btn-danger btn-sm mr-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['date','action'])
                ->make(true);
        }
        return view('admin.mf_ratting.delete_score',$data);
    }

    public function score_history_delete(Request $request){

        DB::table("mf_ratting_score_history")->where('date',$request->id)->delete();

        toastr()->success('MF Rating score history successfully deleted.');
        return redirect()->route('webadmin.mf_rating_score_history_date');
    }

    public function cron(){
        $date = date("Y-m-d H:i:s");
        $date = date('Y-m-d H:i:s', strtotime("-36 months", strtotime($date)));

        $result = DB::table("mf_scanner")
                ->select(['mf_scanner.schemecode','mf_scanner.s_name','mf_scanner.1weekret as oneweek','mf_scanner.classcode','mf_scanner.plan','mf_scanner_avg.1weekret as avg_oneweek'])
                ->join('mf_scanner_avg', function($join){
                    $join->on('mf_scanner_avg.classcode', '=', 'mf_scanner.classcode')
                            ->on('mf_scanner_avg.plan_code', '=', 'mf_scanner.plan');
                })
                ->where("mf_scanner.total",">",20000)
                ->where("mf_scanner.Incept_date","<",$date)
                ->where("mf_scanner.1weekret","!=","")
                ->where("mf_scanner.1weekret","!=",NULL)
                ->orderBy("mf_scanner.1weekret","ASC")
                ->get();
        $pre_data = "";
        $response = [];
        $top_bottom = $result[0]->oneweek - $result[count($result)-1]->oneweek;
        foreach ($result as $key => $value) {
            $avg_oneweek = (float) $value->avg_oneweek;

            $value->deviation = $value->oneweek - $avg_oneweek;
            $value->top_bottom = $top_bottom;
            $value->adjusted = $value->deviation + $value->top_bottom;
            if(!$pre_data){
                $value->point = 100;
                $pre_data = $value;
            }else{
                $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                $pre_data = $value;
            }

            $response[] = (array) $value;
        }

        dd($response);

    }

    public function rating(Request $request){
        $data = [];
        if ($request->ajax()) {
            $data = DB::table("mf_ratting")
                    ->select(["mf_ratting.*","mf_ratting_scanner.s_name"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting.schemecode')
                    ->orderBy('s_name','ASC')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->rawColumns(['schemecode','s_name','rate'])
                ->make(true);
        }
        return view('admin.mf_ratting.ratting',$data);
    }

    public function rating_export(Request $request){

        $filename = "mf-ratting";
        $handle = fopen("./storage/app/".$filename, 'w');
        

        $result = DB::table("mf_ratting")
                    ->select(["mf_ratting.*","mf_ratting_scanner.s_name","mf_ratting_scanner.classcode"])
                    ->LeftJoin('mf_ratting_scanner', 'mf_ratting_scanner.schemecode', '=', 'mf_ratting.schemecode')
                    ->orderBy('mf_ratting_scanner.s_name','ASC')->get();

        // dd($result);
        fputcsv($handle, array('SN', 'Scheme Name','Classcode', 'Rating'));

        foreach($result as $key=>$row) {
            fputcsv($handle, array(
                $key+1, 
                $row->s_name,
                $row->classcode,
                $row->rate
            ));
        }
        // dd("row");
        fclose($handle);


        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
    }

    public function cron_list(){
        $data = [];
        return view('admin.mf_ratting.cron_list',$data);
    }

    public function category_cron(){
        DB::table("mf_ratting_category")->delete();
        $class_list = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.*'])
                        ->where('accord_sclass_mst.status',1)->whereIn('asset_type',['Debt','Equity','Hybrid'])
                        ->orderBy('classname', 'asc')->get();

        $date = date("Y-m-d H:i:s");
        $date = date('Y-m-d H:i:s', strtotime("-36 months", strtotime($date)));
        foreach ($class_list as $key => $value) {
            // echo $value->classcode."--";
            $mf_scanner = DB::table("mf_scanner")->select(['mf_scanner.*'])
                    ->where("total",">",20000)
                    ->where("Incept_date","<",$date)
                    ->where('mf_scanner.classcode','=',$value->classcode)->groupBy("primary_fd_code")->get();
                    
            // echo count($mf_scanner);
            // echo "<br>";
            if(count($mf_scanner) >= 5){
                $insertData = (array) $value;
                DB::table("mf_ratting_category")->insert($insertData);
            }
        }
        
        // exit;

        toastr()->success('Category updated successfully.');
        return redirect()->back()->withInput();
    }

    public function scheme_cron(){

        DB::table("mf_ratting_scanner")->delete();
        $date = date("Y-m-d H:i:s");
        $date = date('Y-m-d H:i:s', strtotime("-36 months", strtotime($date)));
        $mf_scanner = DB::table("mf_scanner")
                    ->select(['mf_scanner.*','mf_ratting_category.classcode as classcode_new'])
                    ->LeftJoin('mf_ratting_category', 'mf_ratting_category.classcode', '=', 'mf_scanner.classcode')
                    ->where("total",">",20000)
                    ->where("Incept_date","<",$date)
                    ->get();

        foreach ($mf_scanner as $key => $value) {
            if($value->classcode_new){
                $insertData = (array) $value;
                unset($insertData['classcode_new']);
                DB::table("mf_ratting_scanner")->insert($insertData);
            }
        }
        toastr()->success('Scheme updated successfully.');
        return redirect()->back()->withInput();
    }

    public function oneweek($date){
        // ->where("classcode","=",28)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.1weekret as oneweek','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->where("mf_ratting_scanner.1weekret","!=","")
                    ->where("mf_ratting_scanner.1weekret","!=",NULL)
                    ->orderBy("mf_ratting_scanner.1weekret","DESC");
                    
            $avg_oneweek = $result->avg('1weekret');
            
            $result = $result->get();
            
            $pre_data = "";
            $response = [];
            $top_bottom = $result[0]->oneweek - $result[count($result)-1]->oneweek;
            foreach ($result as $key => $value) {
                $avg_oneweek = (float) $avg_oneweek;
                $value->top_bottom = $top_bottom;
                $value->deviation = $value->oneweek - $avg_oneweek;
                $value->adjusted = $value->deviation + $top_bottom;
                array_push($response, (array) $value);
            }

            usort($response,function($first,$second){
                return ($first['adjusted'] < $second['adjusted']);
            });

            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $value['point'] = 100;
                    $pre_data = $value;
                }else{
                    if($pre_data['adjusted']){
                        $value['point'] = $value['adjusted'] / $pre_data['adjusted'] * $pre_data['point'];
                    }else{
                        $value['point'] = 0;
                    }
                    
                    $pre_data = $value;
                }
                $response[$key]['point'] = $value['point'];
                
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "performance_7_days"=>$value['point'],
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
            
            // dd($response);
        }
    }

    public function onemonth($date){
        // ->where("classcode","=",28)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.1monthret as onemonth','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->where("mf_ratting_scanner.1monthret","!=","")
                    ->where("mf_ratting_scanner.1monthret","!=",NULL)
                    ->orderBy("mf_ratting_scanner.1monthret","DESC");
                    
            $avg_onemonth = $result->avg('1monthret');
            
            $result = $result->get();

            $pre_data = "";
            $response = [];
            $top_bottom = $result[0]->onemonth - $result[count($result)-1]->onemonth;
            foreach ($result as $key => $value) {
                $avg_onemonth = (float) $avg_onemonth;
                $value->top_bottom = $top_bottom;
                $value->deviation = $value->onemonth - $avg_onemonth;
                $value->adjusted = $value->deviation + $top_bottom;
                array_push($response, (array) $value);
            }

            usort($response,function($first,$second){
                return ($first['adjusted'] < $second['adjusted']);
            });

            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $value['point'] = 100;
                    $pre_data = $value;
                }else{
                    if($pre_data['adjusted']){
                        $value['point'] = $value['adjusted'] / $pre_data['adjusted'] * $pre_data['point'];
                    }else{
                        $value['point'] = 0;
                    }
                    
                    $pre_data = $value;
                }
                $response[$key]['point'] = $value['point'];
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "performance_1_mth"=>$value['point'],
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }
    }

    public function threemonth($date){
        // ->where("classcode","=",28)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.3monthret as threemonth','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->where("mf_ratting_scanner.3monthret","!=","")
                    ->where("mf_ratting_scanner.3monthret","!=",NULL)
                    ->orderBy("mf_ratting_scanner.3monthret","DESC");
                    
            $avg_threemonth = $result->avg('3monthret');
            
            $result = $result->get();
            
            $avg_threemonth = (float) $avg_threemonth;

            $pre_data = "";
            $response = [];
            $top_bottom = $result[0]->threemonth - $result[count($result)-1]->threemonth;
            foreach ($result as $key => $value) {
                $value->avg_threemonth = $avg_threemonth;
                $value->top_bottom = $top_bottom;
                $value->deviation = $value->threemonth - $avg_threemonth;
                $value->adjusted = $value->deviation + $top_bottom;
                array_push($response, (array) $value);
            }

            usort($response,function($first,$second){
                return ($first['adjusted'] < $second['adjusted']);
            });

            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $value['point'] = 100;
                    $pre_data = $value;
                }else{
                    if($pre_data['adjusted']){
                        $value['point'] = $value['adjusted'] / $pre_data['adjusted'] * $pre_data['point'];
                    }else{
                        $value['point'] = 0;
                    }
                    
                    $pre_data = $value;
                }
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "performance_3_mths"=>$value['point'],
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }
    }

    public function sixmonth($date){
        // ->where("classcode","=",28)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.6monthret as sixmonth','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->where("mf_ratting_scanner.6monthret","!=","")
                    ->where("mf_ratting_scanner.6monthret","!=",NULL)
                    ->orderBy("mf_ratting_scanner.6monthret","DESC");
                    
            $avg_sixmonth = $result->avg('6monthret');
            
            $result = $result->get();
            
            $avg_sixmonth = (float) $avg_sixmonth;

            $pre_data = "";
            $response = [];
            $top_bottom = $result[0]->sixmonth - $result[count($result)-1]->sixmonth;
            foreach ($result as $key => $value) {
                $value->avg_sixmonth = $avg_sixmonth;
                $value->top_bottom = $top_bottom;
                $value->deviation = $value->sixmonth - $avg_sixmonth;
                $value->adjusted = $value->deviation + $top_bottom;
                array_push($response, (array) $value);
            }

            usort($response,function($first,$second){
                return ($first['adjusted'] < $second['adjusted']);
            });

            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $value['point'] = 100;
                    $pre_data = $value;
                }else{
                    if($pre_data['adjusted']){
                        $value['point'] = $value['adjusted'] / $pre_data['adjusted'] * $pre_data['point'];
                    }else{
                        $value['point'] = 0;
                    }
                    
                    $pre_data = $value;
                }
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "performance_6_mths"=>$value['point'],
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }
    }

    public function oneyear($date){
        // ->where("classcode","=",28)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.1yrret as oneyear','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->where("mf_ratting_scanner.1yrret","!=","")
                    ->where("mf_ratting_scanner.1yrret","!=",NULL)
                    ->orderBy("mf_ratting_scanner.1yrret","DESC");
                    
            $avg_oneyear = $result->avg('1yrret');
            
            $result = $result->get();
            
            $avg_oneyear = (float) $avg_oneyear;

            $pre_data = "";
            $response = [];
            $top_bottom = $result[0]->oneyear - $result[count($result)-1]->oneyear;
            foreach ($result as $key => $value) {
                $value->avg_oneyear = $avg_oneyear;
                $value->top_bottom = $top_bottom;
                $value->deviation = $value->oneyear - $avg_oneyear;
                $value->adjusted = $value->deviation + $top_bottom;
                array_push($response, (array) $value);
            }

            usort($response,function($first,$second){
                return ($first['adjusted'] < $second['adjusted']);
            });

            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $value['point'] = 100;
                    $pre_data = $value;
                }else{
                    if($pre_data['adjusted']){
                        $value['point'] = $value['adjusted'] / $pre_data['adjusted'] * $pre_data['point'];
                    }else{
                        $value['point'] = 0;
                    }
                    
                    $pre_data = $value;
                }
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "performance_1_year"=>$value['point'],
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }
    }

    public function threeyear($date){
        // ->where("classcode","=",28)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.3yearret as threeyear','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->where("mf_ratting_scanner.3yearret","!=","")
                    ->where("mf_ratting_scanner.3yearret","!=",NULL)
                    ->orderBy("mf_ratting_scanner.3yearret","DESC");
                    
            $avg_threeyear = $result->avg('3yearret');
            
            $result = $result->get();
            
            $avg_threeyear = (float) $avg_threeyear;

            $pre_data = "";
            $response = [];
            $top_bottom = $result[0]->threeyear - $result[count($result)-1]->threeyear;
            foreach ($result as $key => $value) {
                $value->avg_threeyear = $avg_threeyear;
                $value->top_bottom = $top_bottom;
                $value->deviation = $value->threeyear - $avg_threeyear;
                $value->adjusted = $value->deviation + $top_bottom;
                array_push($response, (array) $value);
            }

            usort($response,function($first,$second){
                return ($first['adjusted'] < $second['adjusted']);
            });

            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $value['point'] = 100;
                    $pre_data = $value;
                }else{
                    if($pre_data['adjusted']){
                        $value['point'] = $value['adjusted'] / $pre_data['adjusted'] * $pre_data['point'];
                    }else{
                        $value['point'] = 0;
                    }
                    
                    $pre_data = $value;
                }
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "performance_3_year"=>$value['point'],
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }
    }

    public function ytm($date){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.ytm as ytm','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.ytm","DESC");
                    
            $avg_ytm = $result->avg('ytm');
            
            $result = $result->get();
            
            $avg_ytm = (float) $avg_ytm;

            $pre_data = "";
            $response = [];
            foreach ($result as $key => $value) {
                if(!$pre_data){
                    $value->adjusted_return = 1;
                    $pre_data = $value;
                }else{
                    if($value->ytm && $pre_data->ytm){
                        $value->adjusted_return = $value->ytm / $pre_data->ytm * $pre_data->adjusted_return;
                    }else{
                        $value->adjusted_return = 0;
                    }
                    
                    $pre_data = $value;
                }
                $value->avg_ytm = $avg_ytm;
                if($avg_ytm != 0){
                    $cat_avg_ytm = 100/$avg_ytm;
                    $value->score = ($avg_ytm+($value->ytm-$avg_ytm))*$cat_avg_ytm;
                }else{
                    $value->score = 0;
                }
                $response[] = (array) $value;
            }

            usort($response,function($first,$second){
                return ($first['score'] < $second['score']);
            });
            
            $pre_data = "";
            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $response[$key]['point'] = 100;
                    $pre_data = $response[$key];
                }else{
                    if($pre_data['score'] && $pre_data['point']){
                        $response[$key]['point'] = $value['score'] / $pre_data['score'] * $pre_data['point'];
                    }else{
                        $response[$key]['point'] = 0;
                    }
                    $pre_data = $response[$key];
                }
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "classcode"=>$value['classcode'],
                    "ytm"=>$response[$key]['point']
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }

        // dd($response);
    }

    public function mod_dur_num($date){
        
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.mod_dur_num as mod_dur_num','mf_ratting_scanner.classcode','mf_ratting_scanner.plan','mf_scanner_avg.mod_dur_num as avg_mod_dur_num'])
                    ->join('mf_scanner_avg', function($join){
                        $join->on('mf_scanner_avg.classcode', '=', 'mf_ratting_scanner.classcode')
                                ->on('mf_scanner_avg.plan_code', '=', 'mf_ratting_scanner.plan');
                    })
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.mod_dur_num","DESC")
                    ->get();

            $pre_data = "";
            $response = [];
            foreach ($result as $key => $value) {
                if(!$pre_data){
                    $value->adjusted_return = 1;
                    $pre_data = $value;
                }else{
                    if($value->mod_dur_num && $pre_data->mod_dur_num){
                        $value->adjusted_return = $value->mod_dur_num / $pre_data->mod_dur_num * $pre_data->adjusted_return;
                    }else{
                        $value->adjusted_return = 0;
                    }
                    
                    $pre_data = $value;
                }
                $avg_mod_dur_num = (float) $value->avg_mod_dur_num;
                if($avg_mod_dur_num != 0){
                    $cat_avg_mod_dur_num = 100/$avg_mod_dur_num;
                    $value->score = ($avg_mod_dur_num+($value->mod_dur_num-$avg_mod_dur_num))*$cat_avg_mod_dur_num;
                }else{
                    $value->score = 0;
                }
                $response[] = (array) $value;
            }

            usort($response,function($first,$second){
                return ($first['score'] < $second['score']);
            });
            
            $pre_data = "";
            foreach ($response as $key => $value) {
                if(!$pre_data){
                    $response[$key]['point'] = 100;
                    $pre_data = $response[$key];
                }else{
                    if($pre_data['score'] && $pre_data['point'])
                        $response[$key]['point'] = $value['score'] / $pre_data['score'] * $pre_data['point'];
                    else
                        $response[$key]['point'] = 0;

                    $pre_data = $response[$key];
                }
                $insertData = [
                    "schemecode"=>$value['schemecode'],
                    "modified_duration"=>$response[$key]['point']
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
            }
        }
    }

    public function alpha($date){
        // ->where("classcode",79)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.alpha as alpha','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.alpha","DESC");
            
            // dd($result);
            
            $avg_alpha = $result->avg('alpha');
            
            $result = $result->get();
                    
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->alpha - $result[count($result)-1]->alpha;
                foreach ($result as $key => $value) {
                    // dd($value);
                    // echo "<br>".$value->schemecode; 
                    $value->avg_alpha = (float) $avg_alpha;
                    $value->deviation = $value->alpha - $value->avg_alpha;
                    $value->adjusted = $value->deviation + $top_bottom;
                    $value->top_bottom = $top_bottom;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->adjusted && $pre_data->point && $value->adjusted){
                            // echo $value->adjusted."--".$pre_data->adjusted."--".$pre_data->point; exit;
                            $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    $response[] =$value;
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "alpha"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
                
            }
        }
    }

    public function beta($date){

        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.beta as beta','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.beta","DESC");
                    
            $avg_beta = $result->avg('beta');
            
            $result = $result->get();
            
            $avg_beta = (float) $avg_beta;
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->beta - $result[count($result)-1]->beta;
                foreach ($result as $key => $value) {
                    $value->avg_beta = (float) $avg_beta;
                    $value->deviation = $value->beta - $value->avg_beta;
                    $value->adjusted = $value->deviation + $top_bottom;
                    $value->top_bottom = $top_bottom;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->adjusted && $pre_data->point && $value->adjusted){
                            $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    $insertData = [
                     "schemecode"=>$value->schemecode,
                     "beta"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                     DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                     DB::table("mf_ratting_point")->insert($insertData);
                    }
                    $response[] = $value;
                }
            }
                

        }
    }

    public function sd($date){

        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.sd as sd','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.sd","DESC");
                    
            $avg_sd = $result->avg('sd');
            
            $result = $result->get();
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->sd - $result[count($result)-1]->sd;
                foreach ($result as $key => $value) {
                    $value->avg_sd = (float) $avg_sd;
                    $value->deviation = $value->sd - $value->avg_sd;
                    $value->adjusted = $value->deviation + $top_bottom;
                    $value->top_bottom = $top_bottom;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->adjusted && $pre_data->point && $value->adjusted){
                            $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "standard_deviation"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                    $response[] = $value;
                }
            }
        }
    }

    public function sharpe($date){
        //->where("classcode","=",49)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.sharpe as sharpe','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.sharpe","DESC");
                    
            $avg_sharpe = $result->avg('sharpe');
            
            $result = $result->get();
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->sharpe - $result[count($result)-1]->sharpe;
                foreach ($result as $key => $value) {
                    $value->avg_sharpe = (float) $avg_sharpe;
                    $value->deviation = $value->sharpe - $value->avg_sharpe;
                    $value->adjusted = $value->deviation + $top_bottom;
                    $value->top_bottom = $top_bottom;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->adjusted && $pre_data->point && $value->adjusted){
                            $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    // echo "<br>";
                    // echo $value->schemecode."==".$value->sharpe."==".$value->avg_sharpe."==".$value->deviation."==".$value->adjusted."==".$value->top_bottom."==".$value->point;
                    $insertData = [
                     "schemecode"=>$value->schemecode,
                     "sharpe"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                     DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                     DB::table("mf_ratting_point")->insert($insertData);
                    }
                    $response[] = $value;
                }
            }
        }
    }

    public function sortino($date){
        //->where("classcode","=",49)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.sortino as sortino','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.sortino","DESC");
                    
            $avg_sortino = $result->avg('sortino');
            
            $result = $result->get();
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->sortino - $result[count($result)-1]->sortino;
                foreach ($result as $key => $value) {
                    $value->avg_sortino = (float) $avg_sortino;
                    $value->deviation = $value->sortino - $value->avg_sortino;
                    $value->adjusted = $value->deviation + $top_bottom;
                    $value->top_bottom = $top_bottom;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->adjusted && $pre_data->point && $value->adjusted){
                            $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    // echo "<br>";
                    // echo $value->schemecode."==".$value->sortino."==".$value->avg_sortino."==".$value->deviation."==".$value->adjusted."==".$value->top_bottom."==".$value->point;
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "sortino"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                    $response[] = $value;
                }
            }
        }
    }

    public function treynor($date){
        //49->where("classcode","=",49)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.treynor as treynor','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.treynor","DESC");
                    
            $avg_treynor = $result->avg('treynor');
            
            $result = $result->get();
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->treynor - $result[count($result)-1]->treynor;
                foreach ($result as $key => $value) {
                    // echo "<br>".$value->schemecode;
                    $value->avg_treynor = (float) $avg_treynor;
                    $value->treynor = (float) $value->treynor;
                    
                    // $value->avg_treynor = abs($value->avg_treynor);
                    $value->deviation = $value->treynor - $value->avg_treynor;
                    $value->adjusted = $value->deviation + $top_bottom;
                    $value->top_bottom = $top_bottom;
                    
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->adjusted && $pre_data->point && $value->adjusted){
                            $value->point = $value->adjusted / $pre_data->adjusted * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    // echo "<br>";
                    // echo $value->schemecode."==".$value->treynor."==".$value->avg_treynor."==".$value->deviation."==".$value->adjusted."==".$value->top_bottom."==".$value->point;
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "treynor"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                    $response[] = $value;
                }
            }
        }
    }

    public function schemeaum($date){

        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.total as total','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.total","DESC")
                    ->get();
            // dd($result);
            $pre_data = "";
            $response = [];
            foreach ($result as $key => $value) {
                if(!$key){
                    $value->point = 100;
                    $pre_data = $value;
                }else{
                    // dd($pre_data);
                    if($pre_data->total){
                        $value->point = $value->total * $pre_data->point / $pre_data->total;
                    }else{
                        $value->point = 0;
                    }
                    $pre_data = $value;
                }
                
                $insertData = [
                    "schemecode"=>$value->schemecode,
                    "scheme_aum"=>$value->point,
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
                $response[] = $value;
            }
        }
    }

    public function expense_ratio($date){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.expratio as expratio','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.expratio","ASC");
                    
            $avg_expratio = $result->avg('expratio');
            
            $result = $result->get();
            // dd($result);
            
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                $top_bottom = $result[0]->expratio - $result[count($result)-1]->expratio;
                foreach ($result as $key => $value) {
                    // echo $key."<br>";
                    $value->avg_expratio = (float) $avg_expratio;
                    $value->cat_avg_oneweek = 100/$value->avg_expratio;
                    $value->score = ($value->avg_expratio+($value->expratio-$value->avg_expratio))*$value->cat_avg_oneweek;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->score && $pre_data->point && $value->score){
                            $value->point = $pre_data->score / $value->score * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "expense_ratio"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
            }
        }
    }

    public function pb_ratio($date){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.PB as pb','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.PB","ASC");
                    
            $avg_PB = $result->avg('PB');
            
            $result = $result->get();
            
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                foreach ($result as $key => $value) {
                    $value->avg_PB = (float) $avg_PB;
                    $value->cat_avg_PB = ($value->avg_PB)?(100/$value->avg_PB):0;
                    $value->score = ($value->avg_PB+($value->pb-$value->avg_PB))*$value->cat_avg_PB;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->score && $pre_data->point && $value->score){
                            $value->point = $pre_data->score / $value->score * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                    }
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "pb_ratio"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
            }
        }
    }

    public function pe_ratio($date){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.PE as PE','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.PE","ASC");
                    
            $avg_PE = $result->avg('PE');
            
            $result = $result->get();
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                foreach ($result as $key => $value) {
                    $value->avg_PE = (float) $avg_PE;
                    $value->cat_avg = ($value->avg_PE)?(100/$value->avg_PE):0;
                    $value->score = ($value->avg_PE+($value->PE-$value->avg_PE))*$value->cat_avg;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->score && $pre_data->point && $value->score){
                            $value->point = $pre_data->score / $value->score * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                    }
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "pe_ratio"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
            }
        }
    }

    public function turnover($date){
        //->where("classcode","=",14)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.turnover_ratio as turnover_ratio','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.turnover_ratio","ASC");
                    
            $avg_turnover_ratio = $result->avg('turnover_ratio');
            // echo "turnover_ratio==cat_avg==score==point";
            $result = $result->get();
            
            // dd($result);
            if(count($result) > 2){
                $pre_data = "";
                $response = [];
                foreach ($result as $key => $value) {
                    
                    $value->avg_turnover_ratio = (float) $avg_turnover_ratio;
                    if($avg_turnover_ratio){
                        $value->cat_avg = 100/$value->avg_turnover_ratio;
                        $value->score = ($value->avg_turnover_ratio+($value->turnover_ratio-$value->avg_turnover_ratio))*$value->cat_avg;
                        if(!$pre_data){
                            $value->point = 100;
                            $pre_data = $value;
                        }else{
                            if($pre_data->score && $pre_data->point && $value->score){
                                $value->point = $pre_data->score / $value->score * $pre_data->point;
                            }else{
                                $value->point = 0;
                            }
                            $pre_data = $value;
                        }
                        
                        // echo "<br>";
                        // echo $category->classcode."==".$value->schemecode."==".$value->turnover_ratio."==".$value->cat_avg."==".$value->avg_turnover_ratio."==".$value->score."==".$value->point;
                    }else{
                        $value->point = 0;
                    }
                        
                    
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "turnover_ratio"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
            }
        }
    }

    public function amc_aum($date){

        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.total as total','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.total","DESC")
                    ->get();
            // dd($result);
            $pre_data = "";
            $response = [];
            foreach ($result as $key => $value) {
                if(!$key){
                    $value->point = 100;
                    $pre_data = $value;
                }else{
                    // dd($pre_data);
                    if($pre_data->total){
                        $value->point = $value->total * $pre_data->point / $pre_data->total;
                    }else{
                        $value->point = 0;
                    }
                    $pre_data = $value;
                }
                
                $insertData = [
                    "schemecode"=>$value->schemecode,
                    "amc_aum"=>$value->point,
                ];
                $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                if($mf_ratting_point){
                    DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                }else{
                    DB::table("mf_ratting_point")->insert($insertData);
                }
                $response[] = $value;
            }
        }
    }
    
    public function credit_rating($date){
        $result = DB::table("mf_ratting_scanner")
                ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.primary_fd_code','mf_ratting_scanner.s_name','mf_ratting_scanner.total as total','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                ->get();
                
        $last_date = DB::table('accord_mf_portfolio')->orderBy('invdate','DESC')->first();
        
        foreach($result as $key => $value){
            // echo "<br>".$value->schemecode;
            $result1 = DB::table('accord_mf_portfolio')->select(['rattings.short_name','accord_mf_portfolio.rating',DB::raw("SUM(accord_mf_portfolio.holdpercentage) as holdpercentage")])->join('rattings', 'rattings.category_name', '=', 'accord_mf_portfolio.rating')->where('accord_mf_portfolio.schemecode','=',$value->primary_fd_code)->where('invdate','=',$last_date->invdate)->groupBy('rattings.short_name')->orderByRaw('SUM(accord_mf_portfolio.holdpercentage) DESC')->get();
            $total_value = 0;
            foreach($result1 as $val){
                if($val->short_name == "Sovereign"){
                    $total_value = $total_value + $val->holdpercentage;
                }else if($val->short_name == "AAA"){
                    $total_value = $total_value +  ($val->holdpercentage * 0.8);
                }else if($val->short_name == "AA"){
                    $total_value = $total_value +  ($val->holdpercentage * 0.5);
                }else if($val->short_name == "A"){
                    $total_value = $total_value +  ($val->holdpercentage * 0.2);
                }
            }
            
            $insertData = [
                "schemecode"=>$value->schemecode,
                "credit_rating_of_holdings"=>$total_value,
            ];
            $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
            if($mf_ratting_point){
                DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
            }else{
                DB::table("mf_ratting_point")->insert($insertData);
            }
        }     
    }
    
    public function top_three_sector_concentration($date){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.primary_fd_code','mf_ratting_scanner.s_name','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->get();
                    
            if(count($result)){
                $response = [];
                $class_total_value = 0;
                $class_total_count = count($result);
                
                foreach($result as $key => $value){
                    $mf_portfolio_analysis= DB::table('mf_portfolio_analysis')->select(['sector_name',DB::raw("SUM(holdpercentage) as holdpercentage")])->where("sector_name","!=",NULL)->where('schemecode','=',$value->primary_fd_code)->groupBy('sector_name')->orderByRaw('SUM(holdpercentage) DESC')->take(3)->get();
                    
                    $holdpercentage = 0;
                    foreach($mf_portfolio_analysis as $k1 => $v1){
                        $holdpercentage = $holdpercentage + $v1->holdpercentage;
                    }
                    $value->top_three_sector = $holdpercentage;
                    
                    $class_total_value = $class_total_value + $holdpercentage;
                    
                    $response[] = (array) $value;
                }
                
                usort($response,function($first,$second){
                    return ($first['top_three_sector'] > $second['top_three_sector']);
                });
                
                $cat_avg = $class_total_value/$class_total_count;
                
                $cat_avg_hun = 100 / $cat_avg;
                
                $pre_data = "";
                foreach ($response as $key => $value) {
                    
                    $response[$key]['score'] = ($cat_avg+($value['top_three_sector']-$cat_avg))*$cat_avg_hun;
                    
                    if(!$pre_data){
                        $response[$key]['point'] = 100;
                        $pre_data = $response[$key];
                    }else{
                        if($pre_data['score'] && $pre_data['point']){
                            $response[$key]['point'] = $pre_data['score'] / $response[$key]['score'] * $pre_data['point'];
                        }else{
                            $response[$key]['point'] = 0;
                        }
                        $pre_data = $response[$key];
                    }
                    $insertData = [
                        "schemecode"=>$value['schemecode'],
                        "classcode"=>$value['classcode'],
                        "top_3_sector_concentration"=>$response[$key]['point']
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
                
            }
                
        }
    }
    
    public function top_ten_holding_concentration($date){
        //->where("classcode","=",49)
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            // echo "<br>".$category->classcode;
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.primary_fd_code','mf_ratting_scanner.s_name','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->get();
                    
            if(count($result)){
                $response = [];
                $class_total_value = 0;
                $class_total_count = count($result);
                foreach($result as $key => $value){
                    $mf_portfolio_analysis= DB::table('mf_portfolio_analysis')->select(['compname',DB::raw("SUM(holdpercentage) as holdpercentage")])->where("compname","!=",NULL)
                    ->where('schemecode','=',$value->primary_fd_code)->groupBy('compname')->orderByRaw('SUM(holdpercentage) DESC')->take(10)->get();
                    
                    $holdpercentage = 0;
                    foreach($mf_portfolio_analysis as $k1 => $v1){
                        $holdpercentage = $holdpercentage + $v1->holdpercentage;
                    }
                    $value->top_ten_holding = $holdpercentage;
                    
                    $class_total_value = $class_total_value + $holdpercentage;
                    
                    $response[] = (array) $value;
                }
                
                usort($response,function($first,$second){
                    return ($first['top_ten_holding'] > $second['top_ten_holding']);
                });
                
                $cat_avg = $class_total_value/$class_total_count;
                
                $cat_avg_hun = 100 / $cat_avg;
                
                $pre_data = "";
                foreach ($response as $key => $value) {
                    $response[$key]['score'] = ($cat_avg+($value['top_ten_holding']-$cat_avg))*$cat_avg_hun;
                    
                    if(!$pre_data){
                        $response[$key]['point'] = 100;
                        $pre_data = $response[$key];
                    }else{
                        if($pre_data['score'] && $pre_data['point']){
                            $response[$key]['point'] = $pre_data['score'] / $response[$key]['score'] * $pre_data['point'];
                        }else{
                            $response[$key]['point'] = 0;
                        }
                        $pre_data = $response[$key];
                    }
                    $insertData = [
                        "schemecode"=>$value['schemecode'],
                        "classcode"=>$value['classcode'],
                        "top_10_holding_concentration"=>$response[$key]['point']
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value['schemecode'])->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
            }
        }
    }
    
    public function credit_for_5_year_existence($date){
        //
        $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.Incept_date as incept_date','mf_ratting_scanner.primary_fd_code','mf_ratting_scanner.s_name','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    // ->where("mf_ratting_scanner.schemecode",2167)
                    ->get();
        
        foreach($result as $key => $value){
            $incept_date = date('Y-m-d', strtotime($value->incept_date));
            $incept_date = date('Y-m-d', strtotime($incept_date. ' + 5 years'));
            $current_date = date('Y-m-d');
            $current_date = strtotime($current_date);
            $incept_date = strtotime($incept_date);
            $datediff = $current_date - $incept_date;
            
            // dd($datediff);
            // $total_days = round($datediff / (60 * 60 * 24));
            
            if($datediff > 0){
                $credit_for_5_yr_existance = 100;
            }else{
                $credit_for_5_yr_existance = 0;
            }
            
            $insertData = [
                "schemecode"=>$value->schemecode,
                "credit_for_5_yr_existance"=>$credit_for_5_yr_existance,
            ];
            
            // dd($insertData);
            $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
            if($mf_ratting_point){
                DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
            }else{
                DB::table("mf_ratting_point")->insert($insertData);
            }
        }
    }
    
    public function credit_for_10_year_existence($date){
        $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.Incept_date as incept_date','mf_ratting_scanner.primary_fd_code','mf_ratting_scanner.s_name','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    // ->where("mf_ratting_scanner.schemecode",7889)
                    ->get();

        foreach($result as $key => $value){
            $incept_date = date('Y-m-d', strtotime($value->incept_date));
            $incept_date = date('Y-m-d', strtotime($incept_date. ' + 10 years'));
            $current_date = date('Y-m-d');
            $current_date = strtotime($current_date);
            $incept_date = strtotime($incept_date);
            $datediff =  $current_date - $incept_date;
            
            // $total_days = round($datediff / (60 * 60 * 24));
            
            if($datediff > 0){
                $credit_for_10_yr_existance = 100;
            }else{
                $credit_for_10_yr_existance = 0;
            }
            
            $insertData = [
                "schemecode"=>$value->schemecode,
                "credit_for_10_yr_existance"=>$credit_for_10_yr_existance,
            ];
            
            // dd($insertData);
            $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
            if($mf_ratting_point){
                DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
            }else{
                DB::table("mf_ratting_point")->insert($insertData);
            }
        }
    }
    
    public function credit_for_15_year_existence($date){
        $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.Incept_date as incept_date','mf_ratting_scanner.primary_fd_code','mf_ratting_scanner.s_name','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->get();
                    
        foreach($result as $key => $value){
            $incept_date = date('Y-m-d', strtotime($value->incept_date));
            $incept_date = date('Y-m-d', strtotime($incept_date. ' + 15 years'));
            $current_date = date('Y-m-d');
            $current_date = strtotime($current_date);
            $incept_date = strtotime($incept_date);
            $datediff = $current_date - $incept_date;
            
            // $total_days = round($datediff / (60 * 60 * 24));
            
            if($datediff > 0){
                $credit_for_15_yr_existance = 100;
            }else{
                $credit_for_15_yr_existance = 0;
            }
            
            $insertData = [
                "schemecode"=>$value->schemecode,
                "credit_for_15_yr_existance"=>$credit_for_15_yr_existance,
            ];
            $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
            if($mf_ratting_point){
                DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
            }else{
                DB::table("mf_ratting_point")->insert($insertData);
            }
        }
    }
    
    public function fund_manager($date){
        $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.fund_mgr_code1','accord_fundmanager_mst.experience','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->LeftJoin('accord_fundmanager_mst', 'accord_fundmanager_mst.id', '=', 'mf_ratting_scanner.fund_mgr_code1')
                    // ->where("schemecode",1131)
                    ->get();
                    
        // dd($result);
        
        $response = [];
                    
        foreach($result as $key => $value){
            $experience = $value->experience;
            $fund_manager = 0;
            
            $experiences = explode(" ",$experience);
            // dd($experiences);
            $experienc = 0;
            if(count($experiences) == 2){
                if($experiences[1] == "years" || $experiences[1] == "year" || $experiences[1] == "Years" || $experiences[1] == "Year"){
                    $experiences = $experiences[0];
                    if(is_numeric($experiences)){
                        $experiences = (float) $experiences;
                        if($experience > 20){
                            $fund_manager = 100;
                        }else if($experience > 15){
                            $fund_manager = 75;
                        }else if($experience > 10){
                            $fund_manager = 50;
                        }else if($experience > 5){
                            $fund_manager = 25;
                        }
                    }
                }
            }else if(count($experiences) == 1){
                $experiences = $experiences[0];
                if(is_numeric($experiences)){
                    $experiences = (float) $experiences;
                    if($experience > 20){
                        $fund_manager = 100;
                    }else if($experience > 15){
                        $fund_manager = 75;
                    }else if($experience > 10){
                        $fund_manager = 50;
                    }else if($experience > 5){
                        $fund_manager = 25;
                    }
                }
            }
            
            // echo "<br>".$value->schemecode."==".$value->fund_mgr_code1."==".$fund_manager;
            
            $response[$value->fund_mgr_code1] = $fund_manager;
            
            $insertData = [
                "schemecode"=>$value->schemecode,
                "fund_manager"=>$fund_manager,
            ];
            
            // dd($insertData);
            $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
            if($mf_ratting_point){
                DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
            }else{
                DB::table("mf_ratting_point")->insert($insertData);
            }
        }
    }

    public function tracking_error($date){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $result = DB::table("mf_ratting_scanner")
                    ->select(['mf_ratting_scanner.schemecode','mf_ratting_scanner.s_name','mf_ratting_scanner.trackingError as trackingError','mf_ratting_scanner.classcode','mf_ratting_scanner.plan'])
                    ->where("mf_ratting_scanner.classcode","=",$category->classcode)
                    ->orderBy("mf_ratting_scanner.trackingError","ASC");
                    
            $avg_trackingError = $result->avg('trackingError');
            
            $result = $result->get();
            
            $avg_trackingError = (float) $avg_trackingError;
            
            if(count($result) && $avg_trackingError){
                // echo $category->classcode."<br>";
                $pre_data = "";
                $response = [];
                foreach ($result as $key => $value) {
                    // echo "<br>";
                    
                    $value->avg_trackingError = (float) $avg_trackingError;
                    // echo $value->classcode."==".$value->schemecode."==".$value->trackingError."==".$value->avg_trackingError;
                    $value->cat_avg = 100/$value->avg_trackingError;
                    $value->score = ($value->avg_trackingError+($value->trackingError-$value->avg_trackingError))*$value->cat_avg;
                    if(!$pre_data){
                        $value->point = 100;
                        $pre_data = $value;
                    }else{
                        if($pre_data->score && $pre_data->point && $value->score){
                            $value->point = $pre_data->score / $value->score * $pre_data->point;
                        }else{
                            $value->point = 0;
                        }
                        $pre_data = $value;
                    }
                    
                    // echo "<br>";
                    // echo $value->classcode."==".$value->schemecode."==".$value->trackingError."==".$value->cat_avg."==".$value->avg_trackingError."==".$value->score."==".$value->point;
                    
                    $insertData = [
                        "schemecode"=>$value->schemecode,
                        "tracking_error"=>$value->point,
                    ];
                    $mf_ratting_point = DB::table("mf_ratting_point")->select(["id"])->where("schemecode",$value->schemecode)->first();
                    if($mf_ratting_point){
                        DB::table("mf_ratting_point")->where("id",$mf_ratting_point->id)->update($insertData);
                    }else{
                        DB::table("mf_ratting_point")->insert($insertData);
                    }
                }
                
            }
                
        }

        // dd($response);
    }

    public function point_cron($type){
        $date = "";
        if($type == 1){
            echo "oneweek <br>";
            $this->oneweek($date);
            echo "onemonth <br>";
            $this->onemonth($date);
            echo "threemonth <br>";
            $this->threemonth($date);
            echo "sixmonth <br>";
            $this->sixmonth($date);
            echo "oneyear <br>";
            $this->oneyear($date);
            echo "threeyear <br>";
            $this->threeyear($date);
        }else if($type == 2){
            echo "ytm <br>";
            $this->ytm($date);
            echo "mod_dur_num <br>";
            $this->mod_dur_num($date);
            echo "alpha <br>";
            $this->alpha($date);
            echo "beta <br>";
            $this->beta($date);
            echo "sd <br>";
            $this->sd($date);
            echo "sharpe <br>";
            $this->sharpe($date);
        }else if($type == 3){
            echo "sortino <br>";
            $this->sortino($date);
            echo "treynor <br>";
            $this->treynor($date);
            echo "schemeaum <br>";
            $this->schemeaum($date);
            echo "expense_ratio <br>";
            $this->expense_ratio($date);
            echo "pb_ratio <br>";
            $this->pb_ratio($date);
            echo "pe_ratio <br>";
            $this->pe_ratio($date);
        }else if($type == 4){
            echo "turnover <br>";
            $this->turnover($date);
            echo "fund_manager<br>";
            $this->fund_manager($date);
            echo "tracking_error<br>";
            $this->tracking_error($date);
            echo "amc_aum<br>";
            $this->amc_aum($date);
        }else if($type == 5){
            echo "credit_rating<br>";
            $this->credit_rating($date);
        }else if($type == 6){
            echo "top_ten_holding_concentration<br>";
            $this->top_ten_holding_concentration($date);
            echo "credit_for_10_year_existence<br>";
            $this->credit_for_10_year_existence($date);
            echo "credit_for_15_year_existence<br>";
            $this->credit_for_15_year_existence($date);
            echo "credit_for_5_year_existence<br>";
            $this->credit_for_5_year_existence($date);
            echo "top_three_sector_concentration<br>";
            $this->top_three_sector_concentration($date);
        }

        toastr()->success('Point successfully updated.');
        return redirect()->route('webadmin.mf_rating_cron_list');
    }

    public function score_cron(){
        $mf_ratting_point = DB::table("mf_ratting_point")
                ->select(['mf_ratting_point.*','mf_ratting_category.asset_type'])
                ->LeftJoin('mf_ratting_category', 'mf_ratting_category.classcode', '=', 'mf_ratting_point.classcode')
                // ->where("mf_ratting_point.id",1111)
                ->orderBy("mf_ratting_point.id","ASC")
                ->get();

        $mf_ratting_all = DB::table("mf_ratting_all")->get();

        $result = [];
        foreach ($mf_ratting_all as $key => $value) {
            $result[$value->type_id][$value->classcode]['value'] = $value->values;
            $result[$value->type_id][$value->classcode]['flag'] = $value->flag;
        }
        
        // dd($mf_ratting_point);

        foreach ($mf_ratting_point as $key => $value) {
            // dd($value);
            $insertData = [];
            $total_sum = 0;
            if($value->asset_type == "Equity"){
                if(isset($result[1][$value->classcode])){
                        
                    if($value->performance_1_year){
                        if(isset($result[1])){
                            if(isset($result[1][$value->classcode])){
                                if($result[1][$value->classcode]['flag'] && $result[1][$value->classcode]['value']){
                                    $insertData['performance_1_year'] = $value->performance_1_year * $result[1][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['performance_1_year'];
                                }
                            }
                        }
                                
                    }
                    if($value->performance_3_year){
                        if(isset($result[2])){
                            if(isset($result[2][$value->classcode])){
                                if($result[2][$value->classcode]['flag'] && $result[2][$value->classcode]['value']){
                                    $insertData['performance_3_year'] = $value->performance_3_year * $result[2][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['performance_3_year'];
                                }
                            }
                        }
                    }
                    if($value->alpha){
                        if(isset($result[3])){
                            if(isset($result[3][$value->classcode])){
                                if($result[3][$value->classcode]['flag'] && $result[3][$value->classcode]['value']){
                                    $insertData['alpha'] = $value->alpha * $result[3][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['alpha'];
                                }
                            }
                        }
                    }
                    if($value->standard_deviation){
                        if(isset($result[4])){
                            if(isset($result[4][$value->classcode])){
                                if($result[4][$value->classcode]['flag'] && $result[4][$value->classcode]['value']){
                                    $insertData['standard_deviation'] = $value->standard_deviation * $result[4][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['standard_deviation'];
                                }
                            }
                        }
                    }
                    if($value->sharpe){
                        if(isset($result[5])){
                            if(isset($result[5][$value->classcode])){
                                if($result[5][$value->classcode]['flag'] && $result[5][$value->classcode]['value']){
                                    $insertData['sharpe'] = $value->sharpe * $result[5][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['sharpe'];
                                }
                            }
                        }
                    }
                    if($value->treynor){
                        if(isset($result[6])){
                            if(isset($result[6][$value->classcode])){
                                if($result[6][$value->classcode]['flag'] && $result[6][$value->classcode]['value']){
                                    $insertData['treynor'] = $value->treynor * $result[6][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['treynor'];
                                }
                            }
                        }
                    }
                    if($value->sortino){
                        if(isset($result[7])){
                            if(isset($result[7][$value->classcode])){
                                if($result[7][$value->classcode]['flag'] && $result[7][$value->classcode]['value']){
                                    $insertData['sortino'] = $value->sortino * $result[7][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['sortino'];
                                }
                            }
                        }
                    }   
                    if($value->beta){
                        if(isset($result[8])){
                            if(isset($result[8][$value->classcode])){
                                if($result[8][$value->classcode]['flag'] && $result[8][$value->classcode]['value']){
                                    $insertData['beta'] = $value->beta * $result[8][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['beta'];
                                }
                            }
                        }
                    }   
                    if($value->pe_ratio){
                        if(isset($result[9])){
                            if(isset($result[9][$value->classcode])){
                                if($result[9][$value->classcode]['flag'] && $result[9][$value->classcode]['value']){
                                    $insertData['pe_ratio'] = $value->pe_ratio * $result[9][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['pe_ratio'];
                                }
                            }
                        }
                    }   
                    if($value->pb_ratio){
                        if(isset($result[10])){
                            if(isset($result[10][$value->classcode])){
                                if($result[10][$value->classcode]['flag'] && $result[10][$value->classcode]['value']){
                                    $insertData['pb_ratio'] = $value->pb_ratio * $result[10][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['pb_ratio'];
                                }
                            }
                        }
                    }   
                    if($value->expense_ratio){
                        if(isset($result[11])){
                            if(isset($result[11][$value->classcode])){
                                if($result[11][$value->classcode]['flag'] && $result[11][$value->classcode]['value']){
                                    $insertData['expense_ratio'] = $value->expense_ratio * $result[11][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['expense_ratio'];
                                }
                            }
                        }
                    }   
                    if($value->top_3_sector_concentration){
                        if(isset($result[12])){
                            if(isset($result[12][$value->classcode])){
                                if($result[12][$value->classcode]['flag'] && $result[12][$value->classcode]['value']){
                                    $insertData['top_3_sector_concentration'] = $value->top_3_sector_concentration * $result[12][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['top_3_sector_concentration'];
                                }
                            }
                        }
                    }   
                    if($value->top_10_holding_concentration){
                        if(isset($result[13])){
                            if(isset($result[13][$value->classcode])){
                                if($result[13][$value->classcode]['flag'] && $result[13][$value->classcode]['value']){
                                    $insertData['top_10_holding_concentration'] = $value->top_10_holding_concentration * $result[13][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['top_10_holding_concentration'];
                                }
                            }
                        }
                    }   
                    if($value->credit_for_5_yr_existance){
                        if(isset($result[14])){
                            if(isset($result[14][$value->classcode])){
                                if($result[14][$value->classcode]['flag'] && $result[14][$value->classcode]['value']){
                                    $insertData['credit_for_5_yr_existance'] = $value->credit_for_5_yr_existance * $result[14][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['credit_for_5_yr_existance'];
                                }
                            }
                        }
                    }   
                    if($value->credit_for_10_yr_existance){
                        if(isset($result[15])){
                            if(isset($result[15][$value->classcode])){
                                if($result[15][$value->classcode]['flag'] && $result[15][$value->classcode]['value']){
                                    $insertData['credit_for_10_yr_existance'] = $value->credit_for_10_yr_existance * $result[15][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['credit_for_10_yr_existance'];
                                }
                            }
                        }
                    }   
                    if($value->credit_for_15_yr_existance){
                        if(isset($result[16])){
                            if(isset($result[16][$value->classcode])){
                                if($result[16][$value->classcode]['flag'] && $result[16][$value->classcode]['value']){
                                    $insertData['credit_for_15_yr_existance'] = $value->credit_for_15_yr_existance * $result[16][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['credit_for_15_yr_existance'];
                                }
                            }
                        }
                    }   
                    if($value->turnover_ratio){
                        if(isset($result[17])){
                            if(isset($result[17][$value->classcode])){
                                if($result[17][$value->classcode]['flag'] && $result[17][$value->classcode]['value']){
                                    $insertData['turnover_ratio'] = $value->turnover_ratio * $result[17][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['turnover_ratio'];
                                }
                            }
                        }
                    }   
                    if($value->fund_manager){
                        if(isset($result[18])){
                            if(isset($result[18][$value->classcode])){
                                if($result[18][$value->classcode]['flag'] && $result[18][$value->classcode]['value']){
                                    $insertData['fund_manager'] = $value->fund_manager * $result[18][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['fund_manager'];
                                }
                            }
                        }
                    }   
                    if($value->scheme_aum){
                        if(isset($result[19])){
                            if(isset($result[19][$value->classcode])){
                                if($result[19][$value->classcode]['flag'] && $result[19][$value->classcode]['value']){
                                    $insertData['scheme_aum'] = $value->scheme_aum * $result[19][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['scheme_aum'];
                                }
                            }
                        }
                    }   
                    if($value->amc_aum){
                        if(isset($result[20])){
                            if(isset($result[20][$value->classcode])){
                                if($result[20][$value->classcode]['flag'] && $result[20][$value->classcode]['value']){
                                    $insertData['amc_aum'] = $value->amc_aum * $result[20][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['amc_aum'];
                                }
                            }
                        }
                    }   
                    if($value->tracking_error){
                        if(isset($result[21])){
                            if(isset($result[21][$value->classcode])){
                                if($result[21][$value->classcode]['flag'] && $result[21][$value->classcode]['value']){
                                    $insertData['tracking_error'] = $value->tracking_error * $result[21][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['tracking_error'];
                                }
                            }
                        }
                    }   
                    if($value->mso_special_consideration){
                        if(isset($result[22])){
                            if(isset($result[22][$value->classcode])){
                                if($result[22][$value->classcode]['flag'] && $result[22][$value->classcode]['value']){
                                    $insertData['mso_special_consideration'] = $value->mso_special_consideration * $result[22][$value->classcode]['value'] / 100;
                                    $total_sum = $total_sum + $insertData['mso_special_consideration'];
                                }
                            }
                        }
                    }
                }
            }else if($value->asset_type == "Debt"){
                // dd($value);
                if(isset($result[1][$value->classcode])){
                    // echo "1";
                    if($value->performance_1_year){
                        if($result[1][$value->classcode]['flag'] && $result[1][$value->classcode]['value']){
                            $insertData['performance_1_year'] = $value->performance_1_year * $result[1][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_1_year'];
                        }
                    }
                    // echo "2";
                    if($value->performance_3_year){
                        if($result[2][$value->classcode]['flag'] && $result[2][$value->classcode]['value']){
                            $insertData['performance_3_year'] = $value->performance_3_year * $result[2][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_3_year'];
                        }
                    }
                    // echo "3";
                    if($value->standard_deviation){
                        if($result[4][$value->classcode]['flag'] && $result[4][$value->classcode]['value']){
                            $insertData['standard_deviation'] = $value->standard_deviation * $result[4][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['standard_deviation'];
                        }
                    }
                    // echo "4";
                    if($value->sharpe){
                        if($result[5][$value->classcode]['flag'] && $result[5][$value->classcode]['value']){
                            $insertData['sharpe'] = $value->sharpe * $result[5][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['sharpe'];
                        }
                    }
                    // echo "5";
                    if($value->sortino){
                        if($result[7][$value->classcode]['flag'] && $result[7][$value->classcode]['value']){
                            $insertData['sortino'] = $value->sortino * $result[7][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['sortino'];
                        }
                    }
                    if($value->expense_ratio){
                        if($result[11][$value->classcode]['flag'] && $result[11][$value->classcode]['value']){
                            $insertData['expense_ratio'] = $value->expense_ratio * $result[11][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['expense_ratio'];
                        }
                    }
                    if($value->credit_for_5_yr_existance){
                        if($result[14][$value->classcode]['flag'] && $result[14][$value->classcode]['value']){
                            $insertData['credit_for_5_yr_existance'] = $value->credit_for_5_yr_existance * $result[14][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_for_5_yr_existance'];
                        }
                    }
                    if($value->credit_for_10_yr_existance){
                        if($result[15][$value->classcode]['flag'] && $result[15][$value->classcode]['value']){
                            $insertData['credit_for_10_yr_existance'] = $value->credit_for_10_yr_existance * $result[15][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_for_10_yr_existance'];
                        }
                    }
                    if($value->credit_for_15_yr_existance){
                        if($result[16][$value->classcode]['flag'] && $result[16][$value->classcode]['value']){
                            $insertData['credit_for_15_yr_existance'] = $value->credit_for_15_yr_existance * $result[16][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_for_15_yr_existance'];
                        }
                    }
                    if($value->fund_manager){
                        if($result[18][$value->classcode]['flag'] && $result[18][$value->classcode]['value']){
                            $insertData['fund_manager'] = $value->fund_manager * $result[18][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['fund_manager'];
                        }
                    }
                    if($value->scheme_aum){
                        if($result[19][$value->classcode]['flag'] && $result[19][$value->classcode]['value']){
                            $insertData['scheme_aum'] = $value->scheme_aum * $result[19][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['scheme_aum'];
                        }
                    }
                    if($value->amc_aum){
                        if($result[20][$value->classcode]['flag'] && $result[20][$value->classcode]['value']){
                            $insertData['amc_aum'] = $value->amc_aum * $result[20][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['amc_aum'];
                        }
                    }
                    if($value->mso_special_consideration){
                        if($result[22][$value->classcode]['flag'] && $result[22][$value->classcode]['value']){
                            $insertData['mso_special_consideration'] = $value->mso_special_consideration * $result[22][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['mso_special_consideration'];
                        }
                    }
                    if($value->performance_7_days){
                        if($result[23][$value->classcode]['flag'] && $result[23][$value->classcode]['value']){
                            $insertData['performance_7_days'] = $value->performance_7_days * $result[23][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_7_days'];
                        }
                    }
                    if($value->performance_1_mth){
                        if($result[24][$value->classcode]['flag'] && $result[24][$value->classcode]['value']){
                            $insertData['performance_1_mth'] = $value->performance_1_mth * $result[24][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_1_mth'];
                        }
                    }
                    if($value->performance_3_mths){
                        if($result[25][$value->classcode]['flag'] && $result[25][$value->classcode]['value']){
                            $insertData['performance_3_mths'] = $value->performance_3_mths * $result[25][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_3_mths'];
                        }
                    }
                    if($value->performance_6_mths){
                        if($result[26][$value->classcode]['flag'] && $result[26][$value->classcode]['value']){
                            $insertData['performance_6_mths'] = $value->performance_6_mths * $result[26][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_6_mths'];
                        }
                    }
                    if($value->ytm){
                        if($result[27][$value->classcode]['flag'] && $result[27][$value->classcode]['value']){
                            $insertData['ytm'] = $value->ytm * $result[27][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['ytm'];
                        }
                    }
                    if($value->modified_duration){
                        if($result[28][$value->classcode]['flag'] && $result[28][$value->classcode]['value']){
                            $insertData['modified_duration'] = $value->modified_duration * $result[28][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['modified_duration'];
                        }
                    }
                    if($value->credit_rating_of_holdings){
                        if($result[29][$value->classcode]['flag'] && $result[29][$value->classcode]['value']){
                            $insertData['credit_rating_of_holdings'] = $value->credit_rating_of_holdings * $result[29][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_rating_of_holdings'];
                        }
                    }
                }
                    
            } else if($value->asset_type == "Hybrid"){
                if(isset($result[1][$value->classcode])){
                    if($value->alpha){
                        if($result[3][$value->classcode]['flag'] && $result[3][$value->classcode]['value']){
                            $insertData['alpha'] = $value->alpha * $result[3][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['alpha'];
                        }
                    }
                    if($value->standard_deviation){
                        if($result[4][$value->classcode]['flag'] && $result[4][$value->classcode]['value']){
                            $insertData['standard_deviation'] = $value->standard_deviation * $result[4][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['standard_deviation'];
                        }
                    }
                    if($value->sharpe){
                        if($result[5][$value->classcode]['flag'] && $result[5][$value->classcode]['value']){
                            $insertData['sharpe'] = $value->sharpe * $result[5][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['sharpe'];
                        }
                    }
                    if($value->treynor){
                        if($result[6][$value->classcode]['flag'] && $result[6][$value->classcode]['value']){
                            $insertData['treynor'] = $value->treynor * $result[6][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['treynor'];
                        }
                    }
                    if($value->sortino){
                        if($result[7][$value->classcode]['flag'] && $result[7][$value->classcode]['value']){
                            $insertData['sortino'] = $value->sortino * $result[7][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['sortino'];
                        }
                    }
                    if($value->beta){
                        if($result[8][$value->classcode]['flag'] && $result[8][$value->classcode]['value']){
                            $insertData['beta'] = $value->beta * $result[8][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['beta'];
                        }
                    }
                    if($value->pe_ratio){
                        if($result[9][$value->classcode]['flag'] && $result[9][$value->classcode]['value']){
                            $insertData['pe_ratio'] = $value->pe_ratio * $result[9][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['pe_ratio'];
                        }
                    }
                    if($value->pb_ratio){
                        if($result[10][$value->classcode]['flag'] && $result[10][$value->classcode]['value']){
                            $insertData['pb_ratio'] = $value->pb_ratio * $result[10][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['pb_ratio'];
                        }
                    }
                    if($value->expense_ratio){
                        if($result[11][$value->classcode]['flag'] && $result[11][$value->classcode]['value']){
                            $insertData['expense_ratio'] = $value->expense_ratio * $result[11][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['expense_ratio'];
                        }
                    }
                    if($value->top_3_sector_concentration){
                        if($result[12][$value->classcode]['flag'] && $result[12][$value->classcode]['value']){
                            $insertData['top_3_sector_concentration'] = $value->top_3_sector_concentration * $result[12][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['top_3_sector_concentration'];
                        }
                    } 
                    if($value->top_10_holding_concentration){
                        if($result[13][$value->classcode]['flag'] && $result[13][$value->classcode]['value']){
                            $insertData['top_10_holding_concentration'] = $value->top_10_holding_concentration * $result[13][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['top_10_holding_concentration'];
                        }
                    }
                    if($value->credit_for_5_yr_existance){
                        if($result[14][$value->classcode]['flag'] && $result[14][$value->classcode]['value']){
                            $insertData['credit_for_5_yr_existance'] = $value->credit_for_5_yr_existance * $result[14][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_for_5_yr_existance'];
                        }
                    }
                    if($value->credit_for_10_yr_existance){
                        if($result[15][$value->classcode]['flag'] && $result[15][$value->classcode]['value']){
                            $insertData['credit_for_10_yr_existance'] = $value->credit_for_10_yr_existance * $result[15][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_for_10_yr_existance'];
                        }
                    }
                    if($value->credit_for_15_yr_existance){
                        if($result[16][$value->classcode]['flag'] && $result[16][$value->classcode]['value']){
                            $insertData['credit_for_15_yr_existance'] = $value->credit_for_15_yr_existance * $result[16][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_for_15_yr_existance'];
                        }
                    }
                    if($value->turnover_ratio){
                        if($result[17][$value->classcode]['flag'] && $result[17][$value->classcode]['value']){
                            $insertData['turnover_ratio'] = $value->turnover_ratio * $result[17][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['turnover_ratio'];
                        }
                    }
                    if($value->fund_manager){
                        if($result[18][$value->classcode]['flag'] && $result[18][$value->classcode]['value']){
                            $insertData['fund_manager'] = $value->fund_manager * $result[18][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['fund_manager'];
                        }
                    }
                    if($value->scheme_aum){
                        if($result[19][$value->classcode]['flag'] && $result[19][$value->classcode]['value']){
                            $insertData['scheme_aum'] = $value->scheme_aum * $result[19][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['scheme_aum'];
                        }
                    }
                    if($value->amc_aum){
                        if($result[20][$value->classcode]['flag'] && $result[20][$value->classcode]['value']){
                            $insertData['amc_aum'] = $value->amc_aum * $result[20][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['amc_aum'];
                        }
                    }
                    if($value->mso_special_consideration){
                        if($result[22][$value->classcode]['flag'] && $result[22][$value->classcode]['value']){
                            $insertData['mso_special_consideration'] = $value->mso_special_consideration * $result[22][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['mso_special_consideration'];
                        }
                    }
                    if($value->performance_3_mths){
                        if($result[25][$value->classcode]['flag'] && $result[25][$value->classcode]['value']){
                            $insertData['performance_3_mths'] = $value->performance_3_mths * $result[25][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_3_mths'];
                        }
                    }
                    if($value->performance_6_mths){
                        if($result[26][$value->classcode]['flag'] && $result[26][$value->classcode]['value']){
                            $insertData['performance_6_mths'] = $value->performance_6_mths * $result[26][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['performance_6_mths'];
                        }
                    }
                    if($value->ytm){
                        if($result[27][$value->classcode]['flag'] && $result[27][$value->classcode]['value']){
                            $insertData['ytm'] = $value->ytm * $result[27][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['ytm'];
                        }
                    }
                    if($value->modified_duration){
                        if($result[28][$value->classcode]['flag'] && $result[28][$value->classcode]['value']){
                            $insertData['modified_duration'] = $value->modified_duration * $result[28][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['modified_duration'];
                        }
                    }
                    if($value->credit_rating_of_holdings){
                        if($result[29][$value->classcode]['flag'] && $result[29][$value->classcode]['value']){
                            $insertData['credit_rating_of_holdings'] = $value->credit_rating_of_holdings * $result[29][$value->classcode]['value'] / 100;
                            $total_sum = $total_sum + $insertData['credit_rating_of_holdings'];
                        }
                    }
                }
            }
            
            $insertData['total_score'] = $total_sum;

            
            $mf_ratting_score = DB::table("mf_ratting_score")->where("schemecode",$value->schemecode)->first();

            if($mf_ratting_score){
                DB::table("mf_ratting_score")->where("schemecode",$value->schemecode)->update($insertData);
            }else{
                $insertData['classcode'] = $value->classcode;
                $insertData['schemecode'] = $value->schemecode;
                DB::table("mf_ratting_score")->insert($insertData);
            }
        }

        toastr()->success('Score successfully updated.');
        return redirect()->route('webadmin.mf_rating_cron_list');
    }

    public function rating_cron(){
        $mf_ratting_category = DB::table("mf_ratting_category")->get();

        foreach ($mf_ratting_category as $categorykey => $category) {
            $mf_ratting_score = DB::table("mf_ratting_score")->where("classcode",$category->classcode)->orderBy("total_score","DESC")->get();

            $count_star = (int) count($mf_ratting_score)/5;

            foreach ($mf_ratting_score as $key => $value) {
                $insertData = [];
                if($key+1 < $count_star){
                    $insertData['rate'] = 5;
                }else if($key+1 < $count_star*2){
                    $insertData['rate'] = 4;
                }else if($key+1 < $count_star*3){
                    $insertData['rate'] = 3;
                }else if($key+1 < $count_star*4){
                    $insertData['rate'] = 2;
                }else{
                    $insertData['rate'] = 1;
                }

                $mf_ratting_score = DB::table("mf_ratting")->where("schemecode",$value->schemecode)->first();

                if($mf_ratting_score){
                    DB::table("mf_ratting")->where("schemecode",$value->schemecode)->update($insertData);
                }else{
                    $insertData['classcode'] = $value->classcode;
                    $insertData['schemecode'] = $value->schemecode;
                    DB::table("mf_ratting")->insert($insertData);
                }
            }
        }
        
        toastr()->success('Rating successfully updated.');
        return redirect()->route('webadmin.mf_rating_cron_list');
    }

    public function point_history_cron(){
        $date = date("Y-m-d");
        $mf_ratting_point_history = DB::table("mf_ratting_point_history")->where("date",$date)->first();
        if($mf_ratting_point_history){
            toastr()->success('Point history already updated.');
            return redirect()->route('webadmin.mf_rating_cron_list');
        }else{
            $mf_ratting_point = DB::table("mf_ratting_point")->get();
            foreach ($mf_ratting_point as $key => $value) {
                $insertData = (array) $value;
                $insertData['date'] = $date;
                DB::table("mf_ratting_point_history")->insert($insertData);
            }

            toastr()->success('Point history successfully updated.');
            return redirect()->route('webadmin.mf_rating_cron_list');
        }
    }

    public function score_history_cron(){
        $date = date("Y-m-d");
        $mf_ratting_point_history = DB::table("mf_ratting_score_history")->where("date",$date)->first();
        if($mf_ratting_point_history){
            // echo "Already saved ".$date;
            toastr()->success('Score history already updated.');
            return redirect()->route('webadmin.mf_rating_cron_list');
        }else{
            $mf_ratting_point = DB::table("mf_ratting_score")->get();
            foreach ($mf_ratting_point as $key => $value) {
                $insertData = (array) $value;
                $insertData['date'] = $date;
                DB::table("mf_ratting_score_history")->insert($insertData);
            }
            toastr()->success('Score history successfully updated.');
            return redirect()->route('webadmin.mf_rating_cron_list');
        }
    }

}