<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Samplepdf;

class SamplepdfController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Samplepdf::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pdf', function ($row) {
                    $url=url("uploads/samplepdf/".$row->sample_pdf);
                    $pdf = '<a href="'.$url.'" target="_blank">PDF</a>';
                    return $pdf;
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
                    if(is_permitted('sales-presenters-sample-pdf', 'edit')){
                    $btn = '<a href="'.route('webadmin.samplepdfEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('sales-presenters-sample-pdf', 'delete')){
                    $btn .= '<a href="'.route('webadmin.samplepdfDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['pdf','status','action'])
                ->make(true);
        }
        return view('admin.samplepdf.index');
    }

    public function add(){
        //dd("ok");
        return view('admin.samplepdf.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'sample_pdf' => 'required|mimes:pdf',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'sample_pdf' => $input['sample_pdf'],
            'is_active' => isset($input['status'])?1:0
        ];

        
        if ($image = $request->file('sample_pdf')){
            $file = $input['title'].'.'.$image->getClientOriginalExtension();
            $saveData['sample_pdf'] = $file;

            $destinationPath = public_path('/uploads/samplepdf');
            $image->move($destinationPath, $file);
            
        }

        $res = Samplepdf::create($saveData);
        if ($res){
            toastr()->success('Sample PDF successfully saved.');
            return redirect()->route('webadmin.samplepdf');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['samplepdf'] = Samplepdf::where('id',$id)->first();
        return view('admin.samplepdf.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
        ]);

        $previousSamplereport = Samplepdf::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.samplepdf');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('sample_pdf')){
            $file = $input['title'].'.'.$image->getClientOriginalExtension();
            $saveData['sample_pdf'] = $file;

            $destinationPath = public_path('/uploads/samplepdf');
            $image->move($destinationPath, $file);
            /*if (file_exists(public_path('uploads/samplepdf/'.$previousSamplereport['sample_pdf']))) {
                //chmod(public_path('uploads/samplepdf/'.$previousSamplereport['sample_pdf']), 0644);
                unlink(public_path('uploads/samplepdf/'.$previousSamplereport['sample_pdf']));
            }*/
        }

        $res = $previousSamplereport->update($saveData);
        if ($res){
            toastr()->success('Sample PDF successfully saved.');
            return redirect()->route('webadmin.samplepdf');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        //dd($id);
        $previousSamplereport = Samplepdf::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.samplepdf');
        }

        
        if (file_exists(public_path('uploads/samplepdf/'.$previousSamplereport['sample_pdf']))) {
            //chmod(public_path('uploads/samplepdf/'.$previousSamplereport['sample_pdf']), 0644);
            unlink(public_path('uploads/samplepdf/'.$previousSamplereport['sample_pdf']));
        }
        $res = $previousSamplereport->delete();
        if ($res){
            toastr()->success('Sample PDF successfully deleted.');
            return redirect()->route('webadmin.samplepdf');
        }

        return redirect()->back()->withInput();
    }
}
