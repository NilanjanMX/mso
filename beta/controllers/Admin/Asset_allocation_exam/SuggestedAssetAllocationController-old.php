<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\Suggested_asset_allocation;
use App\Models\Asset_allocation_exam\Suggested_asset_allocation_more;
use App\Models\Asset_allocation_exam\Asset_allocation_product;
use App\Models\Asset_allocation_exam\Asset_allocation_score;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class SuggestedAssetAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Suggested_asset_allocation::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('webadmin.asset-allocation-group-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.asset-allocation-group-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['group_name','action'])
                ->make(true);
        }
        return view('admin.asset_allocation_exam.group');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['products'] = Asset_allocation_product::latest()->get();
        $data['scores'] = Asset_allocation_score::latest()->get();
        return view('admin.asset_allocation_exam.add_group',$data);
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
            'group_name' => 'required',
            'age_min' => 'required',
            'age_max' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'group_name' => $input['group_name'],
            'age_min' => $input['age_min'],
            'age_max' => $input['age_max']
        ];
    
        $res = Suggested_asset_allocation::create($saveData);

        $total_cnt=count($input['asset_allocation_product_id']);

        if ($res){

            if (isset($input['asset_allocation_product_id']) && $total_cnt>0){
                for ($i=0;$i<$total_cnt;$i++){
                    $savOpt = [
                        'suggested_asset_allocation_id' => $res['id'],
                        'asset_allocation_product_id' => $input['asset_allocation_product_id'][$i],
                        'asset_allocation_score_id' => $input['asset_allocation_score_id'][$i],
                        'value' => $input['value'][$i]
                    ];
                    Suggested_asset_allocation_more::create($savOpt);
                }
            }
            toastr()->success('Group successfully saved.');
            return redirect()->route('webadmin.asset-allocation-groups');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Suggested_asset_allocation  $suggested_asset_allocation
     * @return \Illuminate\Http\Response
     */
    public function show(Suggested_asset_allocation $suggested_asset_allocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Suggested_asset_allocation  $suggested_asset_allocation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['group'] = Suggested_asset_allocation::where('id',$id)->first();
        $data['products'] = Asset_allocation_product::latest()->get();
        $data['scores'] = Asset_allocation_score::latest()->get();
        return view('admin.asset_allocation_exam.edit_group',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Suggested_asset_allocation  $suggested_asset_allocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'group_name' => 'required',
            'age_min' => 'required',
            'age_max' => 'required',
        ]);

        $questionBank = Suggested_asset_allocation::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.asset-allocation-groups');
        }

        $input = $request->all();
        $saveData = [
            'group_name' => $input['group_name'],
            'age_min' => $input['age_min'],
            'age_max' => $input['age_max']
        ];
    
        $res = $questionBank->update($saveData);

        if ($res){
            $delExtOpt = Suggested_asset_allocation_more::where('suggested_asset_allocation_id',$id);
            if ($delExtOpt){
                $delExtOpt->delete();
            }

        $total_cnt=count($input['asset_allocation_product_id']);

        if ($res){

            if (isset($input['asset_allocation_product_id']) && $total_cnt>0){
                for ($i=0;$i<$total_cnt;$i++){
                    $savOpt = [
                        'suggested_asset_allocation_id' => $id,
                        'asset_allocation_product_id' => $input['asset_allocation_product_id'][$i],
                        'asset_allocation_score_id' => $input['asset_allocation_score_id'][$i],
                        'value' => $input['value'][$i]
                    ];
                    Suggested_asset_allocation_more::create($savOpt);
                }
            }
            toastr()->success('Group successfully Updated.');
            return redirect()->route('webadmin.asset-allocation-groups');
        }
    }

        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Suggested_asset_allocation  $suggested_asset_allocation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = Suggested_asset_allocation::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
        $delExtOptAns = Suggested_asset_allocation_more::where('suggested_asset_allocation_id',$id);
        if ($delExtOptAns){
            $delExtOptAns->delete();
        }
        toastr()->success('Group successfully deleted.');
        return redirect()->route('webadmin.asset-allocation-groups');
    }
}
