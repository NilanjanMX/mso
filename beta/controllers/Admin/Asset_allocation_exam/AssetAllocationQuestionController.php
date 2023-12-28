<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\Asset_allocation_question;
use App\Models\Asset_allocation_exam\Asset_allocation_answer;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class AssetAllocationQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Asset_allocation_question::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('asset-allocation-asset-allocation-question', 'edit')){
                    $btn = '<a href="'.route('webadmin.asset-allocation-question-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('asset-allocation-asset-allocation-question', 'delete')){
                    $btn .= '<a href="'.route('webadmin.asset-allocation-question-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['question','action'])
                ->make(true);
        }
        return view('admin.asset_allocation_exam.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.asset_allocation_exam.add');
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
            'question' => 'required',
            'totalOption' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'product_order' => $input['product_order'],
            'question' => $input['question'],
            'total_option' => $input['totalOption']
        ];
    
        $res = Asset_allocation_question::create($saveData);

        if ($res){

            if (isset($input['totalOption']) && $input['totalOption']>0){
                for ($i=0;$i<$input['totalOption'];$i++){
                    if(empty($input['ans_mark'][$i]))
                    {
                        $myan='0';
                    }else{
                        $myan=$input['ans_mark'][$i];
                    }
                    $savOpt = [
                        'title' => $input['option'][$i],
                        'ans_mark' => $myan,
                        'asset_allocation_question_id' => $res['id']
                    ];
                    Asset_allocation_answer::create($savOpt);
                }
            }
            toastr()->success('Question successfully saved.');
            return redirect()->route('webadmin.asset-allocation-question');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asset_allocation_question  $asset_allocation_question
     * @return \Illuminate\Http\Response
     */
    public function show(Asset_allocation_question $asset_allocation_question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asset_allocation_question  $asset_allocation_question
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['questiondetails'] = Asset_allocation_question::where('id',$id)->first();
        return view('admin.asset_allocation_exam.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asset_allocation_question  $asset_allocation_question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'question' => 'required',
            'totalOption' => 'required'
        ]);

        $questionBank = Asset_allocation_question::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.asset-allocation-question');
        }

        $input = $request->all();

        $saveData = [
            'product_order' => $input['product_order'],
            'question' => $input['question'],
            'total_option' => $input['totalOption']
        ];


        $res = $questionBank->update($saveData);

        if ($res){
            $delExtOpt = Asset_allocation_answer::where('asset_allocation_question_id',$id);
            if ($delExtOpt){
                $delExtOpt->delete();
            }

            if (isset($input['totalOption']) && $input['totalOption']>0){
                
                for ($i=0;$i<$input['totalOption'];$i++){
                    if(empty($input['ans_mark'][$i]))
                    {
                        $myan='0';
                    }else{
                        $myan=$input['ans_mark'][$i];
                    }
                    $savOpt = [
                        'title' => $input['option'][$i],
                        'ans_mark' => $myan,
                        'asset_allocation_question_id' => $id
                    ];
                    Asset_allocation_answer::create($savOpt);
                }
            }
           // return $input;

            toastr()->success('Question successfully updated.');
            return redirect()->route('webadmin.asset-allocation-question');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asset_allocation_question  $asset_allocation_question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = Asset_allocation_question::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
        $delExtOptAns = Asset_allocation_answer::where('asset_allocation_question_id',$id);
        if ($delExtOptAns){
            $delExtOptAns->delete();
        }
        toastr()->success('Question successfully deleted.');
        return redirect()->route('webadmin.asset-allocation-question');
    }
}
