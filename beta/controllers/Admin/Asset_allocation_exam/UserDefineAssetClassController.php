<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\User_define_asset_class;
use App\Models\Asset_allocation_exam\User_define_product_name;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class UserDefineAssetClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = User_define_asset_class::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('asset-allocation-user-define-asset-class', 'edit')){
                    $btn = '<a href="'.route('webadmin.user-define-asset-class-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('asset-allocation-user-define-asset-class', 'delete')){
                    $btn .= '<a href="'.route('webadmin.user-define-asset-class-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['class_name','action'])
                ->make(true);
        }
        return view('admin.asset_allocation_exam.user-define-asset-class');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.asset_allocation_exam.add_user_define_asset_class');
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
            'class_name' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'class_name' => $input['class_name']
        ];
    
        $res = User_define_asset_class::create($saveData);

        if ($res){

            if(isset($input['product_name']) && !empty($input['product_name'][0]))
            {
                $option_name_count=count($input['product_name']);
                for ($i=0;$i<$option_name_count;$i++){
                    $savOptnew = [
                        'product_name' => $input['product_name'][$i],
                        'user_define_asset_class_id' => $res['id']
                    ];
                    User_define_product_name::create($savOptnew);
                }

            }

            toastr()->success('Class successfully saved.');
            return redirect()->route('webadmin.asset-allocation-user-define-asset-class');

        }
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User_define_asset_class  $user_define_asset_class
     * @return \Illuminate\Http\Response
     */
    public function show(User_define_asset_class $user_define_asset_class)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User_define_asset_class  $user_define_asset_class
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['assets'] = User_define_asset_class::where('id',$id)->first();
        return view('admin.asset_allocation_exam.edit_user_define_asset_class',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User_define_asset_class  $user_define_asset_class
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'class_name' => 'required', 
        ]);

        $questionBank = User_define_asset_class::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.asset-allocation-user-define-asset-class');
        }

        

        $input = $request->all();

         $saveData = [
            'class_name' => $input['class_name']
        ];


        $res = $questionBank->update($saveData);

        if ($res){

        $delExtOptnew = User_define_product_name::where('user_define_asset_class_id',$id);
            if ($delExtOptnew){
                $delExtOptnew->delete();
        }

        if(isset($input['product_name']) && !empty($input['product_name'][0]))
        {
            $option_name_count=count($input['product_name']);
            for ($i=0;$i<$option_name_count;$i++){
                $savOptnew = [
                    'product_name' => $input['product_name'][$i],
                    'user_define_asset_class_id' => $id
                ];
                User_define_product_name::create($savOptnew);
            }

        }

        toastr()->success('Class successfully Updated.');
        return redirect()->route('webadmin.asset-allocation-user-define-asset-class');

        }

        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User_define_asset_class  $user_define_asset_class
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = User_define_asset_class::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
        $delExtOptnew = User_define_product_name::where('user_define_asset_class_id',$id);
            if ($delExtOptnew){
                $delExtOptnew->delete();
        }
        toastr()->success('Score successfully deleted.');
        return redirect()->route('webadmin.asset-allocation-user-define-asset-class');
    }
}
