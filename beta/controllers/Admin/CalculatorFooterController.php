<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calculatorfooter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Input;

use App\Models\Msocsvread;
use App\Models\Msoportclasse;
use DB;
class CalculatorFooterController extends Controller
{
    public function updateFooter(Request $request)
    {
        DB::table('aaswpcomprehensive')->where('id',1)->update(["footer"=>$request->footer]);
        return redirect()->route('webadmin.swpcompfooter');
    }
    
    public function add(){
        return view('admin.calculator_footers.add');
    }
    
    public function save(Request $request)
    {
        $input = [];
        
        $check = Calculatorfooter::where('reference_id',$request->reference_id)->first();
        
        if($check)
        {
            Calculatorfooter::where('reference_id',$request->reference_id)->update(["context"=>$request->context]);
        }
        else{
            $types = $request->transferfrequency .'~'.$request->reporttype;
        Calculatorfooter::insert(["types"=>$types,"reference_id"=>$request->reference_id,"context"=>$request->context]);
        }
        return view('admin.calculator_footers.index');
    }
    
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Calculatorfooter::latest()->orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-stp-custom-disclaimers', 'edit')){
                    $btn = '<a href="'.route('webadmin.calculatorFooterEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-stp-custom-disclaimers', 'delete')){
                    $btn .= '<a href="'.route('webadmin.calculatorFooterDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.calculator_footers.index');
    }
    
    public function msoIndex()
    {
        return view('admin.calculator_footers.msoindex');
    }
    public function msoInput()
    {
        return view('admin.calculator_footers.msoinput');
    }
    public function msoOutput()
    {
        return view('admin.calculator_footers.msooutput');
    }
    public function msoInput2()
    {
        return view('admin.calculator_footers.msoinput2');
    }
    public function AddMsoData()
    {
         return view('admin.calculator_footers.msoaddfield');
    }
    public function AddMsoType(Request $request)
    {
        if(isset($request->classid) && !empty($request->classid))
        {
            Msoportclasse::where('id',$request->classid)->update(['type'=>$request->type,'value'=>$request->value]);
        }
        else
            Msoportclasse::insert(['type'=>$request->type,'value'=>$request->value]);
        return view('admin.calculator_footers.msoaddfield');
    }
    public function ReadCsv(Request $request)
    {
        $input = Input::file('csvffile');
        $destinationPath =  public_path ().'/uploads/'; 
        $filename = $input->getClientOriginalName(); 
        $input->move($destinationPath,$filename);
        if (($handle = fopen ( $destinationPath.$filename, 'r' )) !== FALSE) {
        while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {

            // $csv_data = new Csvdata ();
            // $csv_data->id = $data [0];
            // $csv_data->firstname = $data [1];
            // $csv_data->lastname = $data [2];
            // $csv_data->email = $data [3];
            // $csv_data->gender = $data [4];
            // $csv_data->save ();
            
            $ins = array(
                "Scenario"=>$data[0],
                "AssetClass"=>$data[1],
                "TimeHorizon"=>$data[2],
                "InterestRateScenario"=>$data[3],
                "EquityMarketValuation"=>$data[4],
                "RiskProfile"=>$data[5],
                "Category"=>$data[6],
                "Percent"=>$data[7],
                "Total"=>$data[8]
                );
                
               $inserted = Msocsvread::insert($ins);

        }
        fclose ( $handle );
    }
    return view('admin.calculator_footers.msoindex');
    }
    
    public function EditField(Request $request)
    {
        $ins = array(
                "Scenario"=>$request->scenario,
                "AssetClass"=>$request->assetclass,
                "TimeHorizon"=>$request->timehorizon,
                "InterestRateScenario"=>$request->interestrate,
                "EquityMarketValuation"=>$request->equitymarket,
                "RiskProfile"=>$request->riskprofile,
                "Category"=>$request->category,
                "Percent"=>$request->percent,
                "Total"=>$request->total
                );
        Msocsvread::where('id',$request->id)->update($ins);
        return view('admin.calculator_footers.msoindex');
    }
    
    public function DeleteField(Request $request)
    {
        Msocsvread::where('id',$request->id)->delete();
        return view('admin.calculator_footers.msoindex');
    }
    
    public function DeleteMsoData(Request $request)
    {
        Msoportclasse::where('id',$request->id)->delete();
        return view('admin.calculator_footers.msoaddfield');
    }
    
    public function AddField(Request $request)
    {
        $ins = array(
                "Scenario"=>$request->scenario,
                "AssetClass"=>$request->assetclass,
                "TimeHorizon"=>$request->timehorizon,
                "InterestRateScenario"=>$request->interestrate,
                "EquityMarketValuation"=>$request->equitymarket,
                "RiskProfile"=>$request->riskprofile,
                "Category"=>$request->category,
                "Percent"=>$request->percent,
                "Total"=>$request->total
                );
                Msocsvread::insert($ins);
                return view('admin.calculator_footers.msoindex');
    }
    
    public function AddCsvPage(Request $request)
    {
        $data = Msocsvread::where('id',$request->id)->first();
        return view('admin.calculator_footers.msoedit',$data);
    }
    public function AddCsvData(Request $request)
    {
        //$data = Msocsvread::where('id',$request->id)->first();
        return view('admin.calculator_footers.msoedit');
    }
    
    public function ShowSwpCompFooters()
    {
       
                                
    $data = DB::table('aaswpcomprehensive')->select('*')->first();
    $inputs = [];
    $inputs["id"] = $data->id;
    $inputs["applied"] = $data->applied;
    $inputs["footer"] = $data->footer;
    return view('admin.calculator_footers.swpcomprehensivefooter',$inputs);
    }
}
    