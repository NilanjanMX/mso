<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\Asset_allocation_file;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class AssetAllocationFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['file'] = Asset_allocation_file::where('id','1')->first();
        return view('admin.asset_allocation_exam.file',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asset_allocation_file  $asset_allocation_file
     * @return \Illuminate\Http\Response
     */
    public function show(Asset_allocation_file $asset_allocation_file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asset_allocation_file  $asset_allocation_file
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset_allocation_file $asset_allocation_file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asset_allocation_file  $asset_allocation_file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'file_name' => 'required|mimes:csv,xlsx'
        ]);

        $previousSamplereport = Asset_allocation_file::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.upload-questionaire');
        }

        $input = $request->all();
        
       

        if ($image = $request->file('file_name')){

            
            if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['file_name']))) {
                //chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['file_name']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['file_name']));
            }

            $file = time().'-free.'.$image->getClientOriginalExtension();
            $saveData['file_name'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
            //dd($saveData); 
            $res = $previousSamplereport->update($saveData);
            if ($res){
                toastr()->success('File successfully saved.');
                return redirect()->route('webadmin.upload-questionaire');
            }

            return redirect()->back()->withInput();
            
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asset_allocation_file  $asset_allocation_file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset_allocation_file $asset_allocation_file)
    {
        //
    }
}
