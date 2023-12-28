<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use DB;

class MfPortfolioController extends Controller
{

    public function index(Request $request){

        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;

        $data['result'] = DB::table('accord_mf_portfolio');

        if($data['start_date'] && $data['end_date']){
            $ustart_date = date('Y-m-d', strtotime($data['start_date']));
            $uend_date = date('Y-m-d', strtotime($data['end_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";
            $data['result'] = $data['result']->whereBetween('invdate', [$ustart_date , $uend_date]);
        }

        $data['result'] = $data['result']->orderBy('invdate','DESC')->paginate(20);
        return view('admin.mf_portfolio.index',$data);
    }

    public function add(){
        return view('admin.mf_portfolio.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'cover_image' => 'required|mimes:jpeg,png,jpg',
            'pdf_file_free' => 'required|mimes:pdf',
            'pdf_file_free_landscape' => 'required|mimes:pdf',
            'pdf_file' => 'required|mimes:pdf',
            'landscape_pdf' => 'required|mimes:pdf',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'cover_image' => $input['cover_image'],
            'pdf_file_free' => $input['pdf_file_free'],
            'pdf_file_free_landscape' => $input['pdf_file_free_landscape'],
            'pdf_file' => $input['pdf_file'],
            'landscape_pdf' => $input['landscape_pdf'],
            'is_active' => isset($input['status'])?1:0
        ];


        $res = sales_presenter_pdf::create($saveData);
        if ($res){
            toastr()->success('PDF successfully saved.');
            return redirect()->route('webadmin.mfPortfolio');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['sales_presenter_pdf'] = sales_presenter_pdf::where('id',$id)->first();
        return view('admin.mf_portfolio.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
        ]);

        $previousSamplereport = sales_presenter_pdf::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.mfPortfolio');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousSamplereport->update($saveData);
        if ($res){
            toastr()->success('PDF successfully saved.');
            return redirect()->route('webadmin.mfPortfolio');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$schemecode,$invdate,$srno){
        $previousSamplereport = DB::table('accord_mf_portfolio')
                    ->where('schemecode',$schemecode)
                    ->where('invdate',$invdate)
                    ->where('srno',$srno)
                    ->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.mfPortfolio');
        }

        $res = DB::table('accord_mf_portfolio')
                    ->where('schemecode',$schemecode)
                    ->where('invdate',$invdate)
                    ->where('srno',$srno)->delete();
        if ($res){
            toastr()->success('MF portfolio successfully deleted.');
            return redirect()->route('webadmin.mfPortfolio');
        }

        return redirect()->back()->withInput();
    }

    public function deleteAll(Request $request,$start_date,$end_date){
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $ustart_date = date('Y-m-d', strtotime($data['start_date']));
        $uend_date = date('Y-m-d', strtotime($data['end_date']));

        $ustart_date = $ustart_date." 00:00:01";
        $uend_date = $uend_date."  23:59:59";
        $res = DB::table('accord_mf_portfolio')->whereBetween('invdate', [$ustart_date , $uend_date])->delete();
        if ($res){
            toastr()->success('MF portfolio successfully deleted.');
            return redirect()->route('webadmin.mfPortfolio');
        }

        return redirect()->back()->withInput();
    }

    public function indexAnalysis(Request $request){
        ini_set('memory_limit', -1);
        if ($request->ajax()) {
            $data = DB::table("mf_portfolio_analysis")->orderBy('id','DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    if($row->status == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-portfolio-analysis', 'edit')){
                    $btn = '<a href="'.route('webadmin.mfPortfolioAnalysisEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('mf-research-portfolio-analysis', 'delete')){
                    $btn .= '<a href="'.route('webadmin.mfPortfolioAnalysisDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['schemecode','invdate','fincode','status','action'])
                ->make(true);
        }
        return view('admin.mf_portfolio.index_analysis');
    }

    public function addAnalysis(){
        return view('admin.mf_portfolio.add_analysis');
    }

    public function saveAnalysis(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'cover_image' => 'required|mimes:jpeg,png,jpg',
            'pdf_file_free' => 'required|mimes:pdf',
            'pdf_file_free_landscape' => 'required|mimes:pdf',
            'pdf_file' => 'required|mimes:pdf',
            'landscape_pdf' => 'required|mimes:pdf',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'cover_image' => $input['cover_image'],
            'pdf_file_free' => $input['pdf_file_free'],
            'pdf_file_free_landscape' => $input['pdf_file_free_landscape'],
            'pdf_file' => $input['pdf_file'],
            'landscape_pdf' => $input['landscape_pdf'],
            'is_active' => isset($input['status'])?1:0
        ];


        $res = DB::table("mf_portfolio_analysis")->create($saveData);
        if ($res){
            toastr()->success('PDF successfully saved.');
            return redirect()->route('webadmin.mfPortfolioAnalysis');
        }

        return redirect()->back()->withInput();
    }

    public function editAnalysis($id){
        $data['details'] = DB::table("mf_portfolio_analysis")->where('id',$id)->first();
        $data['asset_class_list'] = DB::table("mf_asset_classes")->where('status',1)->get();
        return view('admin.mf_portfolio.edit_analysis',$data);
    }

    public function updateAnalysis(Request $request,$id){
        $request->validate([
            'schemecode' => 'required',
            'invdate' => 'required',
            'srno' => 'required',
            'fincode' => 'required',
            'noshares' => 'required',
            'mktval' => 'required',
            'aum' => 'required',
            'holdpercentage' => 'required',
            'compname' => 'required',
            'asect_name' => 'required',
            'sector_name' => 'required',
            'mode' => 'required',
        ]);

        $previousSamplereport = DB::table("mf_portfolio_analysis")->where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.mfPortfolioAnalysis');
        }

        $input = $request->all();
        $saveData = [
            'schemecode' => $input['schemecode'],
            'invdate' => $input['invdate'],
            'srno' => $input['srno'],
            'fincode' => $input['fincode'],
            'noshares' => $input['noshares'],
            'mktval' => $input['mktval'],
            'aum' => $input['aum'],
            'holdpercentage' => $input['holdpercentage'],
            'compname' => $input['compname'],
            'asect_name' => $input['asect_name'],
            'asset_class_id' => isset($input['asset_class_id'])?$input['asset_class_id']:"",
            'sector_name' => $input['sector_name'],
            'mode' => $input['mode'],
            'status' => isset($input['status'])?1:0
        ];

        $res = DB::table("mf_portfolio_analysis")->where('id',$id)->update($saveData);
        if ($res){
            toastr()->success('Portfolio analysis successfully saved.');
            return redirect()->route('webadmin.mfPortfolioAnalysis');
        }

        return redirect()->back()->withInput();
    }

    public function deleteAnalysis(Request $request,$id){
        $previousSamplereport = DB::table("mf_portfolio_analysis")->where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.mfPortfolioAnalysis');
        }

        $res = DB::table("mf_portfolio_analysis")->where('id',$id)->delete();
        if ($res){
            toastr()->success('Portfolio analysis successfully deleted.');
            return redirect()->route('webadmin.mfPortfolioAnalysis');
        }

        return redirect()->back()->withInput();
    }
}
