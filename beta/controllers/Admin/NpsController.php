<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class NpsController extends Controller
{
   
    public function index()
    {
        $data = [];
        $data['nps'] = DB::table('nps')->first();
        // dd($data);
        return view('admin.nps.index',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calculator_note  $calculator_note
     * @return \Illuminate\Http\Response
     */
    public function updatenps(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $npsDetail = DB::table('nps')->first();
        if (!$npsDetail){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.nps');
        }

        $input = $request->all();
        //
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'link' => $input['link'],
            'click_to_invest' => $input['click_to_invest'],
            'disclaimer' => $input['disclaimer'],
            'existing_investor_click' => $input['existing_investor_click'],
            'existing_investor_click_link' => $input['existing_investor_click_link'],
            'detail_od_nps' => $input['detail_od_nps']
        ];

        if ($image = $request->file('title_logo')){
            $icon = time().'.'.$image->getClientOriginalExtension();
            $saveData['title_logo'] = $icon;

            $destinationPath = public_path('/uploads/nps');
            $image->move($destinationPath, $icon);
            
            if ($npsDetail->title_logo && file_exists(public_path('uploads/nps/'.$npsDetail->title_logo))) {
                unlink(public_path('uploads/nps/'.$npsDetail->title_logo));
            }
        }

        if ($image = $request->file('pdf')){
            $icon = time().'.'.$image->getClientOriginalExtension();
            $saveData['pdf'] = $icon;

            $destinationPath = public_path('/uploads/nps');
            $image->move($destinationPath, $icon);
            
            if ($npsDetail->pdf && file_exists(public_path('uploads/nps/'.$npsDetail->pdf))) {
                unlink(public_path('uploads/nps/'.$npsDetail->pdf));
            }
        }
        // dd($saveData);
        DB::table("nps")->update($saveData);

        toastr()->success('NPS successfully Updated.');
        return redirect()->route('webadmin.nps');
    }
}
