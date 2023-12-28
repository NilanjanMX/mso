<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calculatorfooter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Input;

use App\Models\PackageCreationDropdown;
use App\Models\TriggerUser;
use App\Models\Trigger;
use App\Models\TriggerSetting;
use DB;
class TriggerController extends Controller
{
    
    public function index(Request $request){
        if ($request->ajax()) {
            $data = TriggerUser::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('webadmin.msomodelportfolio_lumpsum_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.trigger.index');
    }
    
    public function list(Request $request){
        if ($request->ajax()) {
            $data = Trigger::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('trigger-list', 'edit')){
                    $btn = '<a href="'.route('webadmin.trigger_edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    if($row->status == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->rawColumns(['action','status','created_at'])
                ->make(true);
        }
        return view('admin.trigger.list');
    }

    public function edit($id,Request $request){
        $data = [];
        $data['detail'] = Trigger::where("id",$id)->first();

        return view('admin.trigger.edit',$data);

    }
    
    public function update($id,Request $request){
        
        $input = $request->all();

        $insertData = [];
        $insertData['name'] = $request->name;
        $insertData['status'] = ($request->status)?1:0;

        Trigger::where('id',$id)->update($insertData);

        toastr()->success('Updated successfully.');
        return redirect()->route('webadmin.trigger_list');
    }

    public function setup(Request $request){
        $data = [];
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();

        foreach ($data['package_list'] as $key => $value) {
            $trigger_settings = TriggerSetting::where("package_id",$value->id)->first();
            if($trigger_settings){
                $data['package_list'][$key]->number_of_trigger = $trigger_settings->number_of_trigger;
                $data['package_list'][$key]->number_of_active_trigger = $trigger_settings->number_of_active_trigger;
            }else{
                $data['package_list'][$key]->number_of_trigger = "";
                $data['package_list'][$key]->number_of_active_trigger = "";
            }
        }
        return view('admin.trigger.setup',$data);
    }
    
    public function setup_update(Request $request){
        
        $input = $request->all();

        $number_of_trigger = $request->number_of_trigger;
        $number_of_active_trigger = $request->number_of_active_trigger;

        foreach ($number_of_trigger as $key => $value) {
            $insertData = [];
            $insertData['package_id'] = $key;
            $insertData['number_of_trigger'] = $value;
            $insertData['number_of_active_trigger'] = $number_of_active_trigger[$key];

            $trigger_settings = TriggerSetting::where("package_id",$key)->first();
            if($trigger_settings){
                TriggerSetting::where('package_id',$key)->update($insertData);
            }else{
                TriggerSetting::insert($insertData);
            }
        }

        toastr()->success('Updated successfully.');
        return redirect()->route('webadmin.trigger_setup');
    }

    public function default(Request $request){
        // dd(1);
        if ($request->ajax()) {
            //$data = SalespresenterCover::with('category')->latest()->get();
            $data = TriggerUser::where("user_id",0)->orderBy('id','DESC')->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('status', function ($row) {
                    $status='';
                    if($row->status == 1){
                        $status .= '<span style="font-weight: 700;color: #61d41a;">Active</span>';
                    }else{
                        $status .= '<span style="font-weight: 700;color: #ef5350;">Inactive</span>';
                    }
                    
                    if($row->is_email_hit){
                        $status .= '<span style="font-weight: 700;color: #ff0000;margin-left: 12px;">Hit</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('trigger-default', 'edit')){
                    $btn = '<a href="'.route('webadmin.trigger_default_edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('trigger-default', 'delete')){
                    $btn .= '<a href="'.route('webadmin.trigger_default_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','action','status'])
                ->make(true);
        }
        return view('admin.trigger.default_index');
    }

    public function default_add(){
        $data['scheme_list'] = DB::table("accord_scheme_details")
                                ->select(["accord_scheme_details.schemecode","accord_scheme_details.s_name","accord_currentnav.navrs","accord_currentnav.navdate"])
                                ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                                ->where('accord_scheme_details.status','=','Active')->orderBy("accord_scheme_details.s_name","ASC")->get();

        $data['index_list'] = DB::table("accord_indicesmaster")
                                ->select(["accord_indicesmaster.INDEX_CODE as index_code","accord_indicesmaster.INDEX_NAME as index_n","accord_indicesmaster.EXCHANGE as EXCHANGE","accord_indicesmaster.INDEX_LNAME as index_name"])
                                ->where('accord_indicesmaster.flag','=','A')->orderBy("accord_indicesmaster.INDEX_NAME","ASC")->get();

        foreach ($data['index_list'] as $key => $value) {
            $data['index_list'][$key]->VALUE = "";
            if($value->EXCHANGE == "BSE"){
                $indices_hst = DB::table("accord_indices_hst_bsc")->where("SCRIPCODE",$value->index_code)->first();
                if($indices_hst){
                    $data['index_list'][$key]->VALUE = $indices_hst->CLOSE;
                }
                
            }else if($value->EXCHANGE == "NSE"){
                $indices_hst = DB::table("accord_indices_hst_nsc")->where("SYMBOL",$value->index_n)->first();
                if($indices_hst){
                    $data['index_list'][$key]->VALUE = $indices_hst->CLOSE;
                }
            }
        }
        
        $data['trigger_list'] = Trigger::where("status",1)->get();
        return view('admin.trigger.default_add',$data);
    }

    public function default_save(Request $request){
        $request->validate([
            'trigger_type' => 'required',
            'trigger_name' => 'required',
        ]);

        $input = $request->all();
        // dd($input);
        $insertData['trigger_name'] = $request->trigger_name;
        $insertData['trigger_type'] = $request->trigger_type;
        
        $insertData['trigger_value'] = $request->trigger_value;
        $insertData['remarks'] = ($request->remarks)?$request->remarks:"";

        $insertData['user_id'] = 0;

        if($insertData['trigger_type'] == "NAV Trigger"){
            $insertData['scheme'] = $request->scheme;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "Index"){
            $insertData['scheme'] = $request->select_index;
            $insertData['current_nav'] = $request->current_index;
        }else {

        }
        // dd($insertData);
        if($insertData['trigger_value'] == 2){
            $insertData['amount'] = $request->amount;
            $insertData['navrs'] = $request->amount;
            $insertData['trigger_condition'] = $request->trigger_condition;
            // dd($insertData);
            DB::table("trigger_users")->insert($insertData);

        }else if($insertData['trigger_value'] == 1){
            $insertData['base_nav'] = (float) ($request->base_nav);
            $insertData['increase_decrease'] = $request->increase_decrease;
            $insertData['trigger_condition'] = $request->increase_decrease;
            $insertData['appreciation'] = (float) ($request->appreciation);
            if($insertData['increase_decrease'] == 1){
                $insertData['navrs'] = $insertData['base_nav'] + ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }else{
                $insertData['navrs'] = $insertData['base_nav'] - ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }

            // dd($insertData);
            DB::table("trigger_users")->insert($insertData);
        }
        toastr()->success('Trigger successfully saved.');
        return redirect()->route('webadmin.trigger_default');
    }

    public function default_edit($id){
        $data['scheme_list'] = DB::table("accord_scheme_details")
                                ->select(["accord_scheme_details.schemecode","accord_scheme_details.s_name","accord_currentnav.navrs","accord_currentnav.navdate"])
                                ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                                ->where('accord_scheme_details.status','=','Active')->orderBy("accord_scheme_details.s_name","ASC")->get();

        $data['index_list'] = DB::table("accord_indicesmaster")
                                ->select(["accord_indicesmaster.INDEX_CODE as index_code","accord_indicesmaster.INDEX_NAME as index_n","accord_indicesmaster.EXCHANGE as EXCHANGE","accord_indicesmaster.INDEX_LNAME as index_name"])
                                ->where('accord_indicesmaster.flag','=','A')->orderBy("accord_indicesmaster.INDEX_NAME","ASC")->get();

        foreach ($data['index_list'] as $key => $value) {
            $data['index_list'][$key]->VALUE = "";
            if($value->EXCHANGE == "BSE"){
                $indices_hst = DB::table("accord_indices_hst_bsc")->where("SCRIPCODE",$value->index_code)->first();
                if($indices_hst){
                    $data['index_list'][$key]->VALUE = $indices_hst->CLOSE;
                }
                
            }else if($value->EXCHANGE == "NSE"){
                $indices_hst = DB::table("accord_indices_hst_nsc")->where("SYMBOL",$value->index_n)->first();
                if($indices_hst){
                    $data['index_list'][$key]->VALUE = $indices_hst->CLOSE;
                }
            }
        }

        $data['trigger_list'] = Trigger::where("status",1)->get();
        $data['detail'] = DB::table("trigger_users")->where('id',$id)->first();

        return view('admin.trigger.default_edit',$data);
    }

    public function default_update(Request $request){
        $request->validate([
            'trigger_type' => 'required',
            'trigger_name' => 'required',
        ]);

        $input = $request->all();
        $id = $request->id;

        $insertData['trigger_name'] = $request->trigger_name;
        $insertData['trigger_type'] = $request->trigger_type;
        $insertData['trigger_value'] = $request->trigger_value;
        $insertData['remarks'] = ($request->remarks)?$request->remarks:"";

        if($insertData['trigger_type'] == "NAV Trigger"){
            $insertData['scheme'] = $request->scheme;
            $insertData['current_nav'] = $request->current_nav;
        }else if($insertData['trigger_type'] == "Index"){
            $insertData['scheme'] = $request->select_index;
            $insertData['current_nav'] = $request->current_index;
        }else {

        }
        if($insertData['trigger_value'] == 2){
            $insertData['amount'] = $request->amount;
            $insertData['navrs'] = $request->amount;
            $insertData['trigger_condition'] = $request->trigger_condition;
            
            DB::table("trigger_users")->where("id",$id)->update($insertData);

        }else if($insertData['trigger_value'] == 1){
            $insertData['base_nav'] = (float) ($request->base_nav);
            $insertData['increase_decrease'] = $request->increase_decrease;
            $insertData['trigger_condition'] = $request->increase_decrease;
            $insertData['appreciation'] = (float) ($request->appreciation);
            if($insertData['increase_decrease'] == 1){
                $insertData['navrs'] = $insertData['base_nav'] + ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }else{
                $insertData['navrs'] = $insertData['base_nav'] - ($insertData['base_nav'] * $insertData['appreciation'] / 100);
            }

            DB::table("trigger_users")->where("id",$id)->update($insertData);
        }

        toastr()->success('Trigger successfully updated.');
        return redirect()->route('webadmin.trigger_default');
    }

    public function default_delete(Request $request,$id){
        // dd($id);
        DB::table("trigger_users")->where('id',$id)->delete();

        toastr()->success('Trigger successfully deleted.');
        return redirect()->route('webadmin.trigger_default');
        
    }

    public function default_showDatatable()
    {
        $salespresentersoftcopies = SalespresenterCover::orderBy('position','ASC')->get();
        return view('admin.salespresenter_cover.reorder',compact('salespresentersoftcopies'));
    }

    public function default_updateOrder(Request $request)
    {
        $salespresentersoftcopies = SalespresenterCover::all();

        foreach ($salespresentersoftcopies as $salespresentersoftcopy) {
            $salespresentersoftcopy->timestamps = false; // To disable update_at field updation
            $id = $salespresentersoftcopy->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $salespresentersoftcopy->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }
}
    