<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\Investment_period;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class InvestmentPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Investment_period::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('asset-allocation-investment-period', 'edit')){
                    $btn = '<a href="'.route('webadmin.asset-allocation-period-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('asset-allocation-investment-period', 'delete')){
                    $btn .= '<a href="'.route('webadmin.asset-allocation-period-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['period_name','action'])
                ->make(true);
        }
        return view('admin.asset_allocation_exam.period');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.asset_allocation_exam.add_period');
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
            'period_name' => 'required',
            'min_period' => 'required',
            'max_period' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'period_name' => $input['period_name'],
            'min_period' => $input['min_period'],
            'max_period' => $input['max_period']
        ];
    
        $res = Investment_period::create($saveData);

        toastr()->success('Period successfully saved.');
        return redirect()->route('webadmin.asset-allocation-periods');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Investment_period  $investment_period
     * @return \Illuminate\Http\Response
     */
    public function show(Investment_period $investment_period)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Investment_period  $investment_period
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['period'] = Investment_period::where('id',$id)->first();
        return view('admin.asset_allocation_exam.edit_period',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Investment_period  $investment_period
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'period_name' => 'required',
            'min_period' => 'required',
            'max_period' => 'required',
        ]);

        $questionBank = Investment_period::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.asset-allocation-periods');
        }

        $input = $request->all();

         $saveData = [
            'period_name' => $input['period_name'],
            'min_period' => $input['min_period'],
            'max_period' => $input['max_period']
        ];


        $res = $questionBank->update($saveData);

        toastr()->success('Period successfully Updated.');
        return redirect()->route('webadmin.asset-allocation-periods');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Investment_period  $investment_period
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = Investment_period::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
        toastr()->success('Score successfully deleted.');
        return redirect()->route('webadmin.asset-allocation-periods');
    }
}
