<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mfresearch_note;
use App\Models\PackageCreationDropdown;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Response;

use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table("ms_permissions")->groupBy("ms_id")->orderBy('ms_name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('site-permission', 'edit')){
                    $btn = '<a href="'.route('webadmin.site_settingEdit',['id'=> $row->ms_id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.site_setting.index');
    }

    public function edit($id){
        $data['detail'] = DB::table("ms_permissions")->where('ms_id',$id)->first();
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();
        foreach ($data['package_list'] as $key => $value) {
            $mf_research_permissions = DB::table("ms_permissions")->where("package_id",$value->id)->where("ms_id",$id)->first();
            if($mf_research_permissions){
                $data['package_list'][$key]->is_view = ($mf_research_permissions->is_view)?true:false;
                $data['package_list'][$key]->is_download = ($mf_research_permissions->is_download)?true:false;
                $data['package_list'][$key]->is_save = ($mf_research_permissions->is_save)?true:false;
                $data['package_list'][$key]->is_cover = ($mf_research_permissions->is_cover)?true:false;
                $data['package_list'][$key]->is_csv = ($mf_research_permissions->is_csv)?true:false;
            }else{
                $data['package_list'][$key]->is_view = false;
                $data['package_list'][$key]->is_download = false;
                $data['package_list'][$key]->is_save = false;
                $data['package_list'][$key]->is_cover = false;
                $data['package_list'][$key]->is_csv = false;
            }
        }
        return view('admin.site_setting.edit',$data);
    }

    public function update(Request $request,$id){

        $input = $request->all();
        // dd($id);
        $package_view = $request->package_view;
        $package_download = $request->package_download;
        $package_save = $request->package_save;
        $package_csv = $request->package_csv;
        $package_cover = $request->package_cover;
        $package_list = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();

        $insertData = [];
        $insertData['ms_id'] = $id;

        foreach ($package_list as $key => $value) {
            $insertData['package_id'] = $value->id;
            $insertData['is_view'] = isset($package_view[$value->id])?1:0;
            $insertData['is_download'] = isset($package_download[$value->id])?1:0;
            $insertData['is_save'] = isset($package_save[$value->id])?1:0;
            $insertData['is_csv'] = isset($package_csv[$value->id])?1:0;
            $insertData['is_cover'] = isset($package_cover[$value->id])?1:0;
            $mf_research_permissions = DB::table("ms_permissions")->where("package_id",$value->id)->where("ms_id",$id)->first();
            if($mf_research_permissions){
                DB::table("ms_permissions")->where("package_id",$value->id)->where("ms_id",$id)->update($insertData);
            }else{
                DB::table("ms_permissions")->insert($insertData);
            }
            
        }

        toastr()->success('Site permissions successfully updated.');
        return redirect()->route('webadmin.site_setting');
    }

}
