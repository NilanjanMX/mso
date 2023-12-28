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
use App\Models\MsoLumpsum;
use App\Models\MsoSip;
use App\Models\MsoStp;
use App\Models\MsoSwp;
use DB;
class MsoModelPortfolioController extends Controller
{
    
    public function lumpsum(Request $request)
    {
        if ($request->ajax()) {
            $data = MsoLumpsum::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mso-model-portfolio-lumpsum', 'delete')){
                    $btn = '<a href="'.route('webadmin.msomodelportfolio_lumpsum_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.lumpsum');
    }

    public function lumpsum_delete(Request $request)
    {
        MsoLumpsum::where('id',$request->id)->delete();

        toastr()->success('Deleted successfully.');
        return redirect()->route('webadmin.msomodelportfolio_lumpsum');
    }

    public function sip(Request $request)
    {
        if ($request->ajax()) {
            $data = MsoSip::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mso-model-portfolio-sip', 'delete')){
                    $btn = '<a href="'.route('webadmin.msomodelportfolio_sip_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.sip');
    }

    public function sip_delete(Request $request)
    {
        MsoSip::where('id',$request->id)->delete();

        toastr()->success('Deleted successfully.');
        return redirect()->route('webadmin.msomodelportfolio_sip');
    }

    public function stp(Request $request)
    {
        if ($request->ajax()) {
            $data = MsoStp::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mso-model-portfolio-stp', 'delete')){
                    $btn = '<a href="'.route('webadmin.msomodelportfolio_stp_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.stp');
    }

    public function stp_delete(Request $request)
    {
        MsoStp::where('id',$request->id)->delete();

        toastr()->success('Deleted successfully.');
        return redirect()->route('webadmin.msomodelportfolio_stp');
    }

    public function swp(Request $request)
    {
        if ($request->ajax()) {
            $data = MsoSwp::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mso-model-portfolio-swp', 'delete')){
                    $btn = '<a href="'.route('webadmin.msomodelportfolio_swp_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.swp');
    }

    public function swp_delete(Request $request)
    {
        MsoSwp::where('id',$request->id)->delete();

        toastr()->success('Deleted successfully.');
        return redirect()->route('webadmin.msomodelportfolio_swp');
    }

    public function uploadcsv()
    {
        return view('admin.mso_model_portfolio.csv');
    }
    
    public function ReadCsv(Request $request)
    {
        echo $type = $request->type;

        $input = Input::file('csvffile');
        $destinationPath =  public_path ().'/uploads/'; 
        $filename = $input->getClientOriginalName(); 
        $input->move($destinationPath,$filename);

        if($type == "Lumpsum"){
            if (($handle = fopen ( $destinationPath.$filename, 'r' )) !== FALSE) {
                while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                    
                    $ins = array(
                        "scenario"=>$data[0],
                        "asset_class"=>$data[1],
                        "time_horizon"=>$data[2],
                        "interest_rate"=>($data[3])?$data[3]:"Stable",
                        "equity_market"=>($data[4])?$data[4]:"Fair",
                        "risk_profile"=>$data[5],
                        "category"=>$data[6],
                        "percent"=>$data[7],
                        "category1"=>$data[8],
                        "percent1"=>$data[9],
                        "category2"=>$data[10],
                        "percent2"=>$data[11],
                        "Total"=>$data[12]
                    );
                    $inserted = MsoLumpsum::insert($ins);
                }
                fclose ( $handle );
            }
            toastr()->success('Lumpsum Csv file uploaded successfully.');
        }else if($type == "SIP"){
            if (($handle = fopen ( $destinationPath.$filename, 'r' )) !== FALSE) {
                while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                    
                    $ins = array(
                        "scenario"=>$data[0],
                        "asset_class"=>$data[1],
                        "time_horizon"=>$data[2],
                        "interest_rate"=>($data[3])?$data[3]:"Stable",
                        "equity_market"=>($data[4])?$data[4]:"Fair",
                        "risk_profile"=>$data[5],
                        "category"=>$data[6],
                        "percent"=>$data[7],
                        "category1"=>$data[8],
                        "percent1"=>$data[9],
                        "category2"=>$data[10],
                        "percent2"=>$data[11],
                        "Total"=>$data[12]
                    );
                    $inserted = MsoSip::insert($ins);
                }
                fclose ( $handle );
            }
            toastr()->success('SIP Csv file uploaded successfully.');
        }else if($type == "STP"){
            if (($handle = fopen ( $destinationPath.$filename, 'r' )) !== FALSE) {
                while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                    
                    $ins = array(
                        "scenario"=>$data[0],
                        "stp_period"=>$data[1],
                        "investment_holding_period"=>$data[2],
                        "interest_rate"=>($data[3])?$data[3]:"Stable",
                        "equity_market"=>($data[4])?$data[4]:"Fair",
                        "risk_profile"=>$data[5],
                        "category"=>$data[6],
                        "percent"=>$data[7],
                        "category1"=>$data[8],
                        "percent1"=>$data[9],
                        "category2"=>$data[10],
                        "percent2"=>$data[11],
                        "Total"=>$data[12]
                    );
                    $inserted = MsoStp::insert($ins);
                }
                fclose ( $handle );
            }
            toastr()->success('STP Csv file uploaded successfully.');
        }else if($type == "SWP"){
            if (($handle = fopen ( $destinationPath.$filename, 'r' )) !== FALSE) {
                while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                    
                    $ins = array(
                        "scenario"=>$data[0],
                        "investment_mode"=>$data[1],
                        "sip_period"=>$data[2],
                        "deferment_period"=>$data[3],
                        "swp_period"=>$data[4],
                        "accumalation_risk_profile"=>$data[5],
                        "distribution_risk_profile"=>$data[6],
                        "category"=>$data[7],
                        "percent"=>$data[8],
                        "category1"=>$data[9],
                        "percent1"=>$data[10],
                        "category2"=>$data[11],
                        "percent2"=>$data[12],
                        "Total"=>$data[13]
                    );
                    $inserted = MsoSwp::insert($ins);
                }
                fclose ( $handle );
            }
            toastr()->success('SWP Csv file uploaded successfully.');
        }else {
            toastr()->success('Please select type.');
        }

        return redirect()->route('webadmin.msomodelportfoliouploadcsv');
    }

    public function AddMsoType(Request $request)
    {
        if(isset($request->classid) && !empty($request->classid)){
            Msoportclasse::where('id',$request->classid)->update(['value'=>$request->value]);
            toastr()->success('Updated successfully.');
        }else{
            Msoportclasse::insert(['type'=>$request->type,'value'=>$request->value]);
            toastr()->success('Added successfully.');
        }


        if($request->type == "Scenario"){
            return redirect()->route('webadmin.addmsoinvestmentmode');
        }else if($request->type == "Asset Class"){
            return redirect()->route('webadmin.addmsoassetclass');
        }else if($request->type == "Time Horizon"){
            return redirect()->route('webadmin.addmsotimehorizon');
        }else if($request->type == "Interest Rate"){
            return redirect()->route('webadmin.addmsointerestrate');
        }else if($request->type == "Equity Mark"){
            return redirect()->route('webadmin.addmsoequitymark');
        }else if($request->type == "Risk Profile"){
            return redirect()->route('webadmin.addmsoriskprofile');
        }else {
            return redirect()->route('webadmin.addmsoinvestmentmode');
        }
        
        

    }
    
    public function DeleteMsoData(Request $request)
    {
        $msoportclasse = Msoportclasse::where('id',$request->id)->first();

        if($msoportclasse){
            Msoportclasse::where('id',$request->id)->delete();

            if($msoportclasse->type == "Scenario"){
                toastr()->success('Investment Mode Deleted successfully.');
                return redirect()->route('webadmin.addmsoinvestmentmode');
            }else if($msoportclasse->type == "Asset Class"){
                toastr()->success('Asset Class Deleted successfully.');
                return redirect()->route('webadmin.addmsoassetclass');
            }else if($msoportclasse->type == "Time Horizon"){
                toastr()->success('Time Horizon Deleted successfully.');
                return redirect()->route('webadmin.addmsotimehorizon');
            }else if($msoportclasse->type == "Interest Rate"){
                toastr()->success('Interest Rate Deleted successfully.');
                return redirect()->route('webadmin.addmsointerestrate');
            }else if($msoportclasse->type == "Equity Mark"){
                toastr()->success('Equity Mark Deleted successfully.');
                return redirect()->route('webadmin.addmsoequitymark');
            }else if($msoportclasse->type == "Risk Profile"){
                toastr()->success('Risk Profile Deleted successfully.');
                return redirect()->route('webadmin.addmsoriskprofile');
            }
        }else{
            return redirect()->route('webadmin.addmsoinvestmentmode');
        }
        
    }

    public function investment_mode(Request $request)
    {
        if ($request->ajax()) {
            $data = Msoportclasse::where('type','Scenario')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="#" onclick="editdetails('.$row->id.')" data-toggle="modal" data-target="#myModal" class="edit btn btn-primary btn-sm ml-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.deletemsodata',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.investment_mode');
    }

    public function asset_class(Request $request)
    {
        if ($request->ajax()) {
            $data = Msoportclasse::where('type','Asset Class')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="#" onclick="editdetails('.$row->id.')" data-toggle="modal" data-target="#myModal" class="edit btn btn-primary btn-sm ml-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.deletemsodata',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.asset_class');
    }

    public function time_horizon(Request $request)
    {
        if ($request->ajax()) {
            $data = Msoportclasse::where('type','Time Horizon')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="#" onclick="editdetails('.$row->id.')" data-toggle="modal" data-target="#myModal" class="edit btn btn-primary btn-sm ml-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.deletemsodata',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.time_horizon');
    }

    public function interest_rate(Request $request)
    {
        if ($request->ajax()) {
            $data = Msoportclasse::where('type','Interest Rate')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="#" onclick="editdetails('.$row->id.')" data-toggle="modal" data-target="#myModal" class="edit btn btn-primary btn-sm ml-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.deletemsodata',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.interest_rate');
    }

    public function equity_mark(Request $request)
    {
        if ($request->ajax()) {
            $data = Msoportclasse::where('type','Equity Mark')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="#" onclick="editdetails('.$row->id.')" data-toggle="modal" data-target="#myModal" class="edit btn btn-primary btn-sm ml-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.deletemsodata',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.equity_mark');
    }

    public function risk_profile(Request $request)
    {
        if ($request->ajax()) {
            $data = Msoportclasse::where('type','Risk Profile')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="#" onclick="editdetails('.$row->id.')" data-toggle="modal" data-target="#myModal" class="edit btn btn-primary btn-sm ml-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.deletemsodata',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mso_model_portfolio.risk_profile');
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
}
    