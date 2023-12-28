<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Samplereport;
use DB;

class SamplereportController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Samplereport::select(["samplereports.*","calculators.name"])->LeftJoin('calculators', 'calculators.id', '=', 'samplereports.calculator_id')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pdf', function ($row) {
                    $url=asset("uploads/samplereport/$row->sample_pdf");
                    return '<a href='.$url.' border="0" width="40" class="img-rounded" align="center" target="_blank" />PDF</a>';
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-sample-report', 'edit')){
                    $btn = '<a href="'.route('webadmin.samplereportEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-sample-report', 'delete')){
                    $btn .= '<a href="'.route('webadmin.samplereportDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['pdf','status','action'])
                ->make(true);
        }
        return view('admin.samplereport.index');
    }

    public function add(){
        $data = [];
        $data['calculator_list'] = DB::table("calculators")->where("status",1)->get();
        return view('admin.samplereport.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'calculator' => 'required',
            'sample_pdf' => 'required|mimes:pdf',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'sample_pdf' => $input['sample_pdf'],
            'calculator_id' => $input['calculator'],
            'is_active' => isset($input['status'])?1:0
        ];

        
        if ($image = $request->file('sample_pdf')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['sample_pdf'] = $file;

            $destinationPath = public_path('/uploads/samplereport');
            $image->move($destinationPath, $file);
            
        }

        $res = Samplereport::create($saveData);
        if ($res){
            toastr()->success('Sample Report successfully saved.');
            return redirect()->route('webadmin.samplereport');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['samplereport'] = Samplereport::where('id',$id)->first();
        $data['calculator_list'] = DB::table("calculators")->where("status",1)->get();
        return view('admin.samplereport.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'calculator' => 'required',
        ]);

        $previousSamplereport = Samplereport::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.samplereport');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'calculator_id' => $input['calculator'],
            'is_active' => isset($input['status'])?1:0
        ];
        

        if ($image = $request->file('sample_pdf')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['sample_pdf'] = $file;

            $destinationPath = public_path('/uploads/samplereport');
            $image->move($destinationPath, $file);
            if (file_exists(public_path('uploads/samplereport/'.$previousSamplereport['sample_pdf']))) {
                chmod(public_path('uploads/samplereport/'.$previousSamplereport['sample_pdf']), 0644);
                unlink(public_path('uploads/samplereport/'.$previousSamplereport['sample_pdf']));
            }
        }

        $res = $previousSamplereport->update($saveData);
        if ($res){
            toastr()->success('Sample Report successfully saved.');
            return redirect()->route('webadmin.samplereport');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousSamplereport = Samplereport::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.samplereport');
        }

        
        if (file_exists(public_path('uploads/samplereport/'.$previousSamplereport['sample_pdf']))) {
            chmod(public_path('uploads/samplereport/'.$previousSamplereport['sample_pdf']), 0644);
            unlink(public_path('uploads/samplereport/'.$previousSamplereport['sample_pdf']));
        }
        $res = $previousSamplereport->delete();
        if ($res){
            toastr()->success('Sample Report successfully deleted.');
            return redirect()->route('webadmin.samplereport');
        }

        return redirect()->back()->withInput();
    }
}
