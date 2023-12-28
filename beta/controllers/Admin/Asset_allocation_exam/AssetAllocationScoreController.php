<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\Asset_allocation_score;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class AssetAllocationScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Asset_allocation_score::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('asset-allocation-asset-allocation-score', 'edit')){
                    $btn = '<a href="'.route('webadmin.asset-allocation-score-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('asset-allocation-asset-allocation-score', 'delete')){
                    $btn .= '<a href="'.route('webadmin.asset-allocation-score-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['title','action'])
                ->make(true);
        }
        return view('admin.asset_allocation_exam.score');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.asset_allocation_exam.add_score');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'min_val' => 'required',
            'max_val' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'min_val' => $input['min_val'],
            'max_val' => $input['max_val']
        ];
    
        $res = Asset_allocation_score::create($saveData);

        toastr()->success('Score successfully saved.');
        return redirect()->route('webadmin.asset-allocation-score');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asset_allocation_score  $asset_allocation_score
     * @return \Illuminate\Http\Response
     */
    public function show(Asset_allocation_score $asset_allocation_score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asset_allocation_score  $asset_allocation_score
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['score'] = Asset_allocation_score::where('id',$id)->first();
        return view('admin.asset_allocation_exam.edit_score',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asset_allocation_score  $asset_allocation_score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'title' => 'required',
            'min_val' => 'required',
            'max_val' => 'required',
        ]);

        $questionBank = Asset_allocation_score::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.asset-allocation-score');
        }

        $input = $request->all();

        $saveData = [
            'title' => $input['title'],
            'min_val' => $input['min_val'],
            'max_val' => $input['max_val']
        ];


        $res = $questionBank->update($saveData);

        toastr()->success('Score successfully Updated.');
        return redirect()->route('webadmin.asset-allocation-score');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asset_allocation_score  $asset_allocation_score
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = Asset_allocation_score::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
        toastr()->success('Score successfully deleted.');
        return redirect()->route('webadmin.asset-allocation-score');
    }
}
