<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PackageCoupon;
use App\Models\PackageCreationDropdown;
use Yajra\DataTables\Facades\DataTables;

class PackageCouponController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = PackageCoupon::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('package_name', function ($row) {
                    
                    $package_detail = PackageCreationDropdown::where('id',$row->package_id)->first();
                    //$title = $row->blog->title;
                    $package_name = "";
                    if($package_detail){
                        $package_name = $package_detail->name;
                    }
                    return $package_name;
                })
                ->addColumn('coupon_type', function ($row) {
                    if($row->coupon_type == 'first_time'){
                        $coupon_type = 'First Time';
                    }elseif($row->coupon_type == 'all_time'){
                        $coupon_type = 'All Time';
                    }else{
                        $coupon_type = '';
                    }
                    return $coupon_type;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('package-creation-Coupon', 'edit')){
                    $btn = '<a href="'.route('webadmin.packageEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('package-creation-Coupon', 'delete')){
                    $btn .= '<a href="'.route('webadmin.packageDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['package_name','coupon_type','status','action'])
                ->make(true);
        }
        return view('admin.package_coupon.index');
    }

    public function add(){
        $data = [];
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where('is_active',1)->get();
        return view('admin.package_coupon.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'coupon_code' => 'required|unique:package_coupons,coupon_code',
            'coupon_amount' => 'required|numeric',
            'coupon_type' => 'required',
            'package_id' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'coupon_code' => $input['coupon_code'],
            'coupon_amount' => $input['coupon_amount'],
            'package_id' => $input['package_id'],
            'coupon_type' => $input['coupon_type'],
            'expired_at' => $input['expired_at'],
            'is_onetime' => isset($input['is_onetime'])?1:0,
            'is_active' => isset($input['status'])?1:0
        ];

        $res = PackageCoupon::create($saveData);
        if ($res){
            toastr()->success('Coupon successfully saved.');
            return redirect()->route('webadmin.package');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data = [];
        $data['coupon'] = PackageCoupon::where('id',$id)->first();
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where('is_active',1)->get();
        return view('admin.package_coupon.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'coupon_code' => 'required|unique:package_coupons,coupon_code,'.$id,
            'coupon_amount' => 'required|numeric',
            'coupon_type' => 'required',
            'package_id' => 'required',
        ]);

        $previousCoupon = PackageCoupon::where('id',$id)->first();
        if (!$previousCoupon){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package');
        }

        $input = $request->all();
        $saveData = [
            'coupon_code' => $input['coupon_code'],
            'coupon_amount' => $input['coupon_amount'],
            'package_id' => $input['package_id'],
            'coupon_type' => $input['coupon_type'],
            'expired_at' => $input['expired_at'],
            'is_onetime' => isset($input['is_onetime'])?1:0,
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousCoupon->update($saveData);
        if ($res){
            toastr()->success('Coupon successfully saved.');
            return redirect()->route('webadmin.package');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousCoupon = PackageCoupon::where('id',$id)->first();
        if (!$previousCoupon){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package');
        }

        $res = $previousCoupon->delete();
        if ($res){
            toastr()->success('Package Coupon successfully deleted.');
            return redirect()->route('webadmin.package');
        }
        return redirect()->back()->withInput();
    }
}