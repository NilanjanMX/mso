<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Coupon::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coupon_type', function ($row) {
                    if($row->coupon_type == 'fix_amount_all_product'){
                        $coupon_type = 'Fix amount of all products';
                    }elseif($row->coupon_type == 'percentage_amount_all_product'){
                        $coupon_type = 'Percentage amount of all products';
                    }elseif($row->coupon_type == 'fix_cart_discount'){
                        $coupon_type = 'Fix cart discount';
                    }elseif($row->coupon_type == 'percentage_cart_discount'){
                        $coupon_type = 'Percentage cart discount';
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
                    if(is_permitted('coupon-management-coupons', 'edit')){
                    $btn = '<a href="'.route('webadmin.couponsEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('coupon-management-coupons', 'delete')){
                    $btn .= '<a href="'.route('webadmin.couponsDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coupon_type','status','action'])
                ->make(true);
        }
        return view('admin.coupon.index');
    }

    public function add(){
        return view('admin.coupon.add');
    }

    public function save(Request $request){
        $request->validate([
            'coupon_code' => 'required|unique:coupons,coupon_code',
            'coupon_amount' => 'required|numeric',
            'coupon_type' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'coupon_code' => $input['coupon_code'],
            'coupon_amount' => $input['coupon_amount'],
            'coupon_type' => $input['coupon_type'],
            'expired_at' => $input['expired_at'],
            'is_onetime' => isset($input['is_onetime'])?1:0,
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Coupon::create($saveData);
        if ($res){
            toastr()->success('Coupon successfully saved.');
            return redirect()->route('webadmin.coupons');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['coupon'] = Coupon::where('id',$id)->first();
        return view('admin.coupon.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'coupon_code' => 'required|unique:coupons,coupon_code,'.$id,
            'coupon_amount' => 'required|numeric',
            'coupon_type' => 'required',
        ]);

        $previousCoupon = Coupon::where('id',$id)->first();
        if (!$previousCoupon){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.coupons');
        }

        $input = $request->all();
        $saveData = [
            'coupon_code' => $input['coupon_code'],
            'coupon_amount' => $input['coupon_amount'],
            'coupon_type' => $input['coupon_type'],
            'expired_at' => $input['expired_at'],
            'is_onetime' => isset($input['is_onetime'])?1:0,
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousCoupon->update($saveData);
        if ($res){
            toastr()->success('Coupon successfully saved.');
            return redirect()->route('webadmin.coupons');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousCoupon = Coupon::where('id',$id)->first();
        if (!$previousCoupon){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.coupons');
        }

        $res = $previousCoupon->delete();
        if ($res){
            toastr()->success('Coupon successfully deleted.');
            return redirect()->route('webadmin.coupons');
        }
        return redirect()->back()->withInput();
    }
}