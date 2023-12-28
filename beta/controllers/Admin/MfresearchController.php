<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mfresearch_note;
use App\Models\PackageCreationDropdown;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Response;

use Illuminate\Http\Request;

class MfresearchController extends Controller
{
    public function mfResearch(Request $request){
        if ($request->ajax()) {
            $data = DB::table("mf_researches")->orderBy('name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.mfResearchEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('mf-research-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.mfResearchDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.mf_researches.index');
    }

    public function mfResearchAdd(){
        $data = [];
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();
        return view('admin.mf_researches.add',$data);
    }

    public function mfResearchSave(Request $request){
        $request->validate([
            'name' => 'required',
            'url' => 'required'
        ]);

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'url' => $input['url'],
            'description' => isset($input['description'])?$input['description']:"",
            'status' => isset($input['status'])?1:0
        ];
        $mf_research_id = DB::table("mf_researches")->insertGetId($saveData);

        $package_view = $request->package_view;
        $package_download = $request->package_download;
        $package_save = $request->package_save;
        $package_csv = $request->package_csv;
        $package_cover = $request->package_cover;
        $package_list = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();

        $insertData = [];
        $insertData['mf_research_id'] = $mf_research_id;

        foreach ($package_list as $key => $value) {
            $insertData['package_id'] = $value->id;
            $insertData['is_view'] = isset($package_view[$value->id])?1:0;
            $insertData['is_download'] = isset($package_download[$value->id])?1:0;
            $insertData['is_save'] = isset($package_save[$value->id])?1:0;
            $insertData['is_csv'] = isset($package_csv[$value->id])?1:0;
            $insertData['is_cover'] = isset($package_cover[$value->id])?1:0;
            
            DB::table("mf_research_permissions")->insert($insertData);
        }

        toastr()->success('MF Research successfully saved.');
        return redirect()->route('webadmin.mfResearchIndex');
    }

    public function mfResearchEdit($id){
        $data['detail'] = DB::table("mf_researches")->where('id',$id)->first();
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();
        foreach ($data['package_list'] as $key => $value) {
            $mf_research_permissions = DB::table("mf_research_permissions")->where("package_id",$value->id)->where("mf_research_id",$id)->first();
            if($mf_research_permissions){
                $data['package_list'][$key]->is_view = ($mf_research_permissions->is_view)?true:false;
                $data['package_list'][$key]->is_download = ($mf_research_permissions->is_download)?true:false;
                $data['package_list'][$key]->is_save = ($mf_research_permissions->is_save)?true:false;
                $data['package_list'][$key]->is_csv = ($mf_research_permissions->is_csv)?true:false;
                $data['package_list'][$key]->is_cover = ($mf_research_permissions->is_cover)?true:false;
            }else{
                $data['package_list'][$key]->is_view = false;
                $data['package_list'][$key]->is_download = false;
                $data['package_list'][$key]->is_save = false;
                $data['package_list'][$key]->is_csv = false;
                $data['package_list'][$key]->is_cover = false;
            }
        }
        return view('admin.mf_researches.edit',$data);
    }

    public function mfResearchUpdate(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'url' => 'required',
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'url' => $input['url'],
            'description' => isset($input['description'])?$input['description']:"",
            'status' => isset($input['status'])?1:0
        ];

        DB::table("mf_researches")->where('id',$id)->update($saveData);
        DB::table("mf_research_permissions")->where('mf_research_id',$id)->delete();
        $package_view = $request->package_view;
        $package_download = $request->package_download;
        $package_save = $request->package_save;
        $package_csv = $request->package_csv;
        $package_cover = $request->package_cover;
        $package_list = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();

        $insertData = [];
        $insertData['mf_research_id'] = $id;

        foreach ($package_list as $key => $value) {
            $insertData['package_id'] = $value->id;
            $insertData['is_view'] = isset($package_view[$value->id])?1:0;
            $insertData['is_download'] = isset($package_download[$value->id])?1:0;
            $insertData['is_save'] = isset($package_save[$value->id])?1:0;
            $insertData['is_csv'] = isset($package_csv[$value->id])?1:0;
            $insertData['is_cover'] = isset($package_cover[$value->id])?1:0;

            DB::table("mf_research_permissions")->insert($insertData);
        }

        toastr()->success('MF Research successfully updated.');
        return redirect()->route('webadmin.mfResearchIndex');
    }

    public function mfResearchDelete($id){
        if ($id){
            DB::table("mf_researches")->where('id',$id)->delete();
            DB::table("mf_research_permissions")->where('mf_research_id',$id)->delete();
            toastr()->success('MF Research successfully deleted.');
            return redirect()->route('webadmin.mfResearchIndex');
        }
        return redirect()->back()->withInput();
    }

    public function mfResearchReorder(){
        $datas = DB::table("mf_researches")->orderBy('position','ASC')->get();
        return view('admin.mf_researches.reorder',compact('datas'));
    }

    public function mfResearchReorderUpdate(Request $request){
        // dd($datas);
        foreach ($request->order as $order) {
            DB::table("mf_researches")->where('id',$order['id'])->update(['position' => $order['position']]);
        }
        
        return response('Update Successfully.', 200);
    }

    public function index(Request $request){
         if ($request->ajax()) {
            $data = Mfresearch_note::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function($row){
                    return ucfirst(str_replace('_', ' ', $row->category));
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-disclaimer-note', 'edit')){
                    $btn = '<a href="'.route('webadmin.mf-research-note-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name','category','action'])
                ->make(true);
        }
        return view('admin.mf_research.list');
    }

    public function edit($id){
        $data['note'] = Mfresearch_note::where('id',$id)->first();
        return view('admin.mf_research.edit',$data);
    }
    
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $questionBank = Mfresearch_note::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.notes');
        }

        $input = $request->all();
        //
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description']
        ];

        $res = $questionBank->update($saveData);

        toastr()->success('Note successfully Updated.');
        return redirect()->route('webadmin.mf-research-notes');
    }

    public function master(Request $request){
         if ($request->ajax()) {
            $data = DB::table("mf_scanner")
                    ->select(["mf_scanner.*","mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname","accord_sclass_mst.asset_type as asset_type"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner.plan', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                    ->orderBy('s_name','ASC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-master', 'active/inactive')){
                        if($row->status == 1){
                            $btn = '<a href="'.route('webadmin.mf-research-change-status',['id'=> $row->schemecode ]).'" class="btn btn-primary btn-sm mr-1" onclick="return confirm(\'Are you sure?\')"  >Active</a>';
                        }else{
                            $btn = '<a href="'.route('webadmin.mf-research-change-status',['id'=> $row->schemecode ]).'" class="btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure?\')"  >Inactive</a>';
                        }
                    }
                    if(is_permitted('mf-research-master', 'edit')){
                    $btn .= '<a href="javascript:void(0);" class="btn btn-danger btn-sm mr-1" onclick="openEditModal('.$row->schemecode.',\''.$row->short_name.'\');">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['schemecode','s_name','short_name','asset_type','action'])
                ->make(true);
        }
        return view('admin.mf_research.master');
    }

    public function master_change_status($schemecode){
        $data = DB::table("mf_scanner")->where('schemecode',$schemecode)->first();
        if($data){
            $insertData = [
                "status" => !$data->status
            ];
            DB::table("mf_scanner")->where("schemecode",$schemecode)->update($insertData);

            toastr()->success('Master status change successfully.');
        }else{
            toastr()->success('Something wrong.');
        }

        return redirect()->route('webadmin.mf-research-master');
    }

    public function master_export(){
        $table_name = "mf_scanner";
        $table_data = DB::table($table_name)
                    ->select(["mf_scanner.*","mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname","accord_sclass_mst.asset_type as asset_type"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner.plan', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')->get();
        // dd($table_data);
        $filename = $table_name.".csv";
        $handle = fopen("./storage/app/".$filename, 'w');
        // echo "ok"; exit;
        // dd($handle);
        $insertData = [];
        foreach($table_data as $key=>$row) {
            $insertData = (array) $row;
            // dd($insertData);
            if($key == 0){
                $headers_array = array("S. NO.");
                foreach ($insertData as $key1 => $value1) {
                    array_push($headers_array, strtoupper(str_replace("_"," ",$key1)));
                }
                // dd($headers_array);
                fputcsv($handle, $headers_array);
            }

            $body_array = array($key+1);
            foreach ($insertData as $key1 => $value1) {
                array_push($body_array, strtoupper(str_replace("_"," ",$value1)));
            }
            fputcsv($handle, $body_array);
        }
        //dd($handle);
        
        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download('./storage/app/'.$filename, $table_name.".csv", $headers);
    }

    public function master_delete(){
        $table_data = DB::table("mf_scanner")->truncate();
        return redirect()->route('webadmin.mf-research-master');
    }

    public function master_cron(){
        $table_data = DB::table("mf_scanner_cron")->where('id',1)->first();
        if($table_data){
            if($table_data->status == 0){
                 DB::table("mf_scanner_cron")->where('id',1)->update(["status"=>1,"page_number"=>0]);
            }else{
                toastr()->success('Cron already started.');
            }
        }
        return redirect()->route('webadmin.mf-research-master');
    }

    public function upload_master(Request $request){
                
        $input = $request->all();
        // dd($input);
        $insertData = [
            'short_name' => $request->master_short_name
        ];

        DB::table("mf_scanner")->where("schemecode",$request->master_schemecode)->update($insertData);

        toastr()->success('Master successfully Updated.');
        return redirect()->route('webadmin.mf-research-master');
    }

    public function avg(Request $request){
         if ($request->ajax()) {
            $data = DB::table("mf_scanner_avg")
                    ->select(["mf_scanner_avg.*","mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname"])
                    ->LeftJoin('accord_plan_mst', 'mf_scanner_avg.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                    ->LeftJoin('accord_sclass_mst', 'mf_scanner_avg.classcode', '=', 'accord_sclass_mst.classcode')
                    ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                    ->orderBy('classname','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->rawColumns(['classname','planname','1dayret','1weekret','1monthret','3monthret','6monthret','1yrret','2yearret','3yearret','5yearret','10yearret','incret'])
                ->make(true);
        }
        return view('admin.mf_research.avg');
    }

    public function avg_export(){
        $table_name = "mf_scanner";
        $table_data = DB::table('mf_scanner_avg')
                        ->select(["mf_scanner_avg.*","mf_scanner_plan.name as plan_name","mf_scanner_classcode.name as class_name","accord_plan_mst.plan as planname","accord_sclass_mst.classname as classname"])
                        ->LeftJoin('accord_plan_mst', 'mf_scanner_avg.plan_code', '=', 'accord_plan_mst.plan_code')
                        ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                        ->LeftJoin('accord_sclass_mst', 'mf_scanner_avg.classcode', '=', 'accord_sclass_mst.classcode')
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->orderBy('classname','ASC')->get();
        // dd($table_data);
        $filename = $table_name."_avg.csv";
        $handle = fopen("./storage/app/".$filename, 'w');
        // echo "ok"; exit;
        // dd($handle);
        $headers_array = array("S. NO.","Category Name","Plan Name","1 Day","1 Week","1 Month","3 Month","6 Month","1 Year","2 Year","3 Year","5 Year","10 Year","Since Inception");
        fputcsv($handle, $headers_array);
        $insertData = [];
        foreach($table_data as $key=>$row) {
            $row = (array) $row;
            $insertData = [];
            $insertData[] = $key+1;
            $insertData[] = ($row['plan_name'])?$row['plan_name']:$row['planname'];
            $insertData[] = ($row['class_name'])?$row['class_name']:$row['classname'];
            $insertData[] = round($row['1dayret'], 2);
            $insertData[] = round($row['1weekret'], 2);
            $insertData[] = round($row['1monthret'], 2);
            $insertData[] = round($row['3monthret'], 2);
            $insertData[] = round($row['6monthret'], 2);
            $insertData[] = round($row['1yrret'], 2);
            $insertData[] = round($row['2yearret'], 2);
            $insertData[] = round($row['3yearret'], 2);
            $insertData[] = round($row['5yearret'], 2);
            $insertData[] = round($row['10yearret'], 2);
            $insertData[] = round($row['incret'], 2);
            fputcsv($handle, $insertData);
        }
        //dd($handle);
        
        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download('./storage/app/'.$filename, $filename, $headers);
    }

    public function plan(Request $request){
         if ($request->ajax()) {
            $data = DB::table("accord_plan_mst")
                        ->select(['accord_plan_mst.*','mf_scanner_plan.name'])
                        ->LeftJoin('mf_scanner_plan', 'mf_scanner_plan.plan_code', '=', 'accord_plan_mst.plan_code')
                        ->orderBy('plan','ASC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    
                    $btn = '';
                    if(is_permitted('mf-research-plan', 'changestatus')){
                        if($row->status == 1){
                            $btn = '<a href="'.route('webadmin.mf-research-plan-status',['id'=>$row->plan_code]).'" class="btn btn-primary btn-sm mr-1" onclick="return confirm(\'Are you sure?\')"  >Active</a>';
                        }else{
                            $btn = '<a href="'.route('webadmin.mf-research-plan-status',['id'=>$row->plan_code]).'" class="btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure?\')"  >Inactive</a>';
                        }
                    }else{
                        if($row->status == 1){
                            $btn = '<a class="btn btn-primary btn-sm mr-1 text-light" onclick="return alert(\'Not authorized, ask for ChangeStatus permission from Super Admin\')"  >Active</a>';
                        }else{
                            $btn = '<a class="btn btn-danger btn-sm mr-1 text-light" onclick="return alert(\'Not authorized, ask for ChangeStatus permission from Super Admin\')"  >Inactive</a>';
                        }
                    }
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-plan', 'edit')){
                    $btn = '<a href="javascript:void(0);" class="btn btn-danger btn-sm mr-1" onclick="openEditModal('.$row->plan_code.',\''.$row->name.'\');">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['plan','name','status','action'])
                ->make(true);
        }
        return view('admin.mf_research.plan');
    }

    public function status_plan($plan_code){
        $data = DB::table("accord_plan_mst")->where('plan_code',$plan_code)->first();
        if($data){
            $insertData = [
                "status" => !$data->status
            ];
            DB::table("accord_plan_mst")->where("plan_code",$plan_code)->update($insertData);

            toastr()->success('Plan status change successfully.');
        }else{
            toastr()->success('Something wrong.');
        }

        return redirect()->route('webadmin.mf-research-plan');
    }

    public function upload_plan(Request $request){
        $input = $request->all();
        // dd($input);
        $insertData = [
            'name' => $input['category_classname']
        ];

        $data = DB::table("mf_scanner_plan")->where('plan_code',$input['category_classcode'])->first();
        if($data){
            DB::table("mf_scanner_plan")->where("plan_code",$input['category_classcode'])->update($insertData);
        }else{
            $insertData['plan_code'] = $input['category_classcode'];
            DB::table("mf_scanner_plan")->insert($insertData);
        }

        toastr()->success('Plan successfully Updated.');
        return redirect()->route('webadmin.mf-research-plan');
    }

    public function category(Request $request){
         if ($request->ajax()) {
            $data = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.*','mf_scanner_classcode.name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->orderBy('classname','ASC')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-category', 'changestatus')){
                        if($row->status == 1){
                            $btn = '<a href="'.route('webadmin.mf-research-category-status',['id'=>$row->classcode]).'" class="btn btn-primary btn-sm mr-1" onclick="return confirm(\'Are you sure?\')"  >Active</a>';
                        }else{
                            $btn = '<a href="'.route('webadmin.mf-research-category-status',['id'=>$row->classcode]).'" class="btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure?\')"  >Inactive</a>';
                        }
                    }else{
                        if($row->status == 1){
                            $btn = '<a class="btn btn-primary btn-sm mr-1 text-light" onclick="return alert(\'Not authorized, ask for ChangeStatus permission from Super Admin\')"  >Active</a>';
                        }else{
                            $btn = '<a class="btn btn-danger btn-sm mr-1 text-light" onclick="return alert(\'Not authorized, ask for ChangeStatus permission from Super Admin\')"  >Inactive</a>';
                        }
                    }
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-category', 'edit')){
                    $btn = '<a href="javascript:void(0);" class="btn btn-danger btn-sm mr-1" onclick="openEditModal('.$row->classcode.',\''.$row->name.'\');">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['classname','name','status','action'])
                ->make(true);
        }
        return view('admin.mf_research.category');
    }
    
    public function status_category($classcode){
        $data = DB::table("accord_sclass_mst")->where('classcode',$classcode)->first();
        if($data){
            $insertData = [
                "status" => !$data->status
            ];
            DB::table("accord_sclass_mst")->where("classcode",$classcode)->update($insertData);

            toastr()->success('Category status change successfully.');
        }else{
            toastr()->success('Something wrong.');
        }

        return redirect()->route('webadmin.mf-research-category');
    }

    public function upload_category(Request $request){
                
        $input = $request->all();
        // dd($input);
        $insertData = [
            'name' => $input['category_classname']
        ];

        $data = DB::table("mf_scanner_classcode")->where('classcode',$input['category_classcode'])->first();
        if($data){
            DB::table("mf_scanner_classcode")->where("classcode",$input['category_classcode'])->update($insertData);
        }else{
            $insertData['classcode'] = $input['category_classcode'];
            DB::table("mf_scanner_classcode")->insert($insertData);
        }

        toastr()->success('Category successfully Updated.');
        return redirect()->route('webadmin.mf-research-category');
    }

    public function showDatatable(){
        $datas = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.*','mf_scanner_classcode.name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')->orderBy('position','ASC')->get();
        return view('admin.mf_research.category-reorder',compact('datas'));
    }

    public function updateOrder(Request $request){
        $datas = DB::table("accord_sclass_mst")->get();

        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->classcode;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    DB::table("accord_sclass_mst")->where('classcode',$id)->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }

    public function mf_category_wise_performance(Request $request){
         if ($request->ajax()) {
            $data = DB::table("mf_category_wise_performance")
                        ->select(['mf_category_wise_performance.*','mf_scanner.s_name'])
                        ->LeftJoin('mf_scanner', 'mf_scanner.schemecode', '=', 'mf_category_wise_performance.schemecode')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('mf-research-calender-year', 'edit')){
                    $btn = '<a href="javascript:void(0);" class="btn btn-danger btn-sm mr-1" onclick="openEditModal('.$row->id.',\''.$row->nav.'\',\''.$row->aum.'\');">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['s_name','year','nav','aum','action'])
                ->make(true);
        }
        return view('admin.mf_research.mf_category_wise_performance');
    }

    public function mf_category_wise_performance_upload(Request $request){
        $input = $request->all();

        $data = DB::table("mf_category_wise_performance")->where('id',$input['category_classcode'])->first();
        if($data){
            $previus_date = $data->year - 1;
            $accord_navhist  = DB::table('accord_navhist')->whereYear('navdate', '=', $previus_date)->where('schemecode',$data->schemecode)->orderBy('navdate','DESC')->first();
            
            $return = "";
            if($accord_navhist && $data->nav){
                $navs1 = (float) $accord_navhist->navrs;
                $navs2 = (float) $data->nav;
                $return = ($navs2 - $navs1)/$navs1*100;
            }
            $insertData = [
                'aum' => $return
            ];
            DB::table("mf_category_wise_performance")->where("id",$input['category_classcode'])->update($insertData);

            toastr()->success('Successfully Updated.');
            return redirect()->route('webadmin.mf-category-wise-performance');
        }else{
            toastr()->danger('Something wrong.');
            return redirect()->route('webadmin.mf-category-wise-performance');
        }
        
    }

    public function disclaimer(){
        $data = [];
        $data['detail'] = DB::table("mf_research_disclaimers")->where('is_active',1)->first();
        return view('admin.mf_research.disclaimer',$data);
    }

    public function updateDisclaimer(Request $request){


        $saveData = [];
        $saveData['title'] = $request->title;

        $id = $request->title;
        $detail = DB::table("mf_research_disclaimers")->where('is_active',1)->first();
        $image = $request->file('pdf');
        // dd($image);
        if ($image = $request->file('pdf')){
            if (file_exists(public_path('uploads/mf_research_disclaimer/'.$detail->pdf))) {
                chmod(public_path('uploads/mf_research_disclaimer/'.$detail->pdf), 0777);
                unlink(public_path('uploads/mf_research_disclaimer/'.$detail->pdf));
            }

            $file = str_replace(' ', '-', $saveData['title']).time().'.'.$image->getClientOriginalExtension();
            $saveData['pdf'] = $file;

            $destinationPath = public_path('/uploads/mf_research_disclaimer');
            $image->move($destinationPath, $file);
            
        }
        DB::table("mf_research_disclaimers")->where('is_active',1)->update($saveData);
        toastr()->success('Successfully Updated.');
        return redirect()->route('webadmin.mf-disclaimer');
    }

}
