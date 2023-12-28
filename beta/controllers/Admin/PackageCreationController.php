<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PackageCreationType;
use App\Models\PackageCreationDropdown;
use App\Models\PackageCreationSetting;
use App\Models\PackageCreationHint;
use App\Models\BecomeAMember;
use App\Models\PackageCreationBecomeAMember;
use DB;

class PackageCreationController extends Controller
{
    public function package_creation_type(Request $request){
        if ($request->ajax()) {
            $data = PackageCreationType::latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('package-creation-type', 'edit')){
                    $btn = '<a href="'.route('webadmin.package_creation_type_edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('package-creation-type', 'delete')){
                    $btn .= '<a href="'.route('webadmin.package_creation_type_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.package_creation.type');
    }

    public function package_creation_type_add(){
        return view('admin.package_creation.add_type');
    }

    public function package_creation_type_save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();

        $data = [
            'name' => $input['name'],
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = PackageCreationType::create($data);
        if ($res){
            toastr()->success('Type successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function package_creation_type_edit($id){
        $data['gallery'] = PackageCreationType::where('id',$id)->first();
        return view('admin.package_creation.edit_type',$data);
    }

    public function package_creation_type_update(Request $request,$id){
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();

        $previousGallery = PackageCreationType::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package_creation_type');
        }

        $data = [
            'name' => $input['name'],
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = $previousGallery->update($data);
        if ($res){
            toastr()->success('Type successfully updated.');
            return redirect()->route('webadmin.package_creation_type');
        }
        return redirect()->back()->withInput();
    }

    public function package_creation_type_delete(Request $request,$id){
        $previousGallery = PackageCreationType::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package_creation_type');
        }

        $res = $previousGallery->delete();
        if ($res){
            toastr()->success('Type successfully deleted.');
            return redirect()->route('webadmin.package_creation_type');
        }

        return redirect()->back()->withInput();
    }

    public function package_creation_dropdown(Request $request){
        if ($request->ajax()) {
            $data = PackageCreationDropdown::latest()->orderBy('order_by','DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('order_action', function($row){
                    $btn = '';
                    if(is_permitted('package-creation-package', 'down')){
                    $btn = '<a href="'.route('webadmin.package_creation_type_down',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-primary btn-sm mr-1">Down</a>';
                    }
                    if(is_permitted('package-creation-package', 'up')){
                    $btn .= '<a href="'.route('webadmin.package_creation_type_up',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Up</a>';
                    }
                    return $btn;
                })
                
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('package-creation-package', 'edit')){
                    $btn = '<a href="'.route('webadmin.package_creation_dropdown_edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('package-creation-package', 'delete')){
                    $btn .= '<a href="'.route('webadmin.package_creation_dropdown_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['order_action','action'])
                ->make(true);
        }
        return view('admin.package_creation.dropdown');
    }

    public function package_creation_dropdown_add(){
        $data = [];
        $data['type_list'] = PackageCreationType::where('is_active',1)->get();
        $data['become_a_member'] = BecomeAMember::where('is_active',1)->orderBy('position','ASC')->get();
        return view('admin.package_creation.add_dropdown',$data);
    }

    public function package_creation_dropdown_save(Request $request){
        $request->validate([
            'type_id' => 'required',
            'name' => 'required'
        ]);

        $input = $request->all();

        $order_detail = PackageCreationDropdown::orderBy('order_by','DESC')->first();

        $order_by = 1;

        if($order_detail){
            $order_by = $order_detail->order_by + 1;
        }
        
        $data = [
            'name' => $input['name'],
            'type_id' => $input['type_id'],
            'price' => $input['price'],
            'discount_price' => $input['discount_price'],
            'price_per_user' => $input['price_per_user'],
            'days' => $input['days'],
            'is_active' => (isset($input['status']))?1:0,
            'order_by' => $order_by
        ];
        // dd($data);
        $res = PackageCreationDropdown::create($data);

        $id = $res['id'];

        foreach ($request->text as $key => $value) {
            $insertData = [];
            $insertData['text'] = $value;
            $insertData['package_creation_dropdown_id'] = $id;
            $insertData['become_a_member_id'] = $key;
            $insertData['is_selected'] = isset($request->mso_item[$key])?$request->mso_item[$key]:0;

            PackageCreationBecomeAMember::create($insertData);

        }
        if ($res){
            toastr()->success('Package successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function package_creation_dropdown_edit($id){
        $data['gallery'] = PackageCreationDropdown::where('id',$id)->first();
        $data['type_list'] = PackageCreationType::where('is_active',1)->get();
        $data['become_a_member'] = BecomeAMember::where('is_active',1)->orderBy('position','ASC')->get();

        foreach ($data['become_a_member'] as $key => $value) {
            $becomeAMember = PackageCreationBecomeAMember::where('package_creation_dropdown_id',$id)->where('become_a_member_id',$value->id)->first();
            if($becomeAMember){
                $data['become_a_member'][$key]->text = $becomeAMember->text;
                $data['become_a_member'][$key]->is_selected = $becomeAMember->is_selected;
            }else{
                $data['become_a_member'][$key]->text = "";
                $data['become_a_member'][$key]->is_selected = "";
            }
        }
        // dd($data['gallery']);
        return view('admin.package_creation.edit_dropdown',$data);
    }

    public function package_creation_dropdown_update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'type_id' => 'required'
        ]);

        $input = $request->all();

        // dd($input);

        $previousGallery = PackageCreationDropdown::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package_creation_dropdown');
        }

        $data = [
            'name' => $input['name'],
            'type_id' => $input['type_id'],
            'price' => $input['price'],
            'discount_price' => $input['discount_price'],
            'price_per_user' => $input['price_per_user'],
            'days' => $input['days'],
            'is_active' => (isset($input['status']))?1:0
        ];
        // dd($data);
        $res = $previousGallery->update($data);

        PackageCreationBecomeAMember::where('package_creation_dropdown_id',$id)->delete();

        foreach ($request->text as $key => $value) {
            $insertData = [];
            $insertData['text'] = $value;
            $insertData['package_creation_dropdown_id'] = $id;
            $insertData['become_a_member_id'] = $key;
            $insertData['is_selected'] = isset($request->mso_item[$key])?$request->mso_item[$key]:0;

            PackageCreationBecomeAMember::create($insertData);

        }

        if ($res){
            toastr()->success('Package successfully updated.');
            return redirect()->route('webadmin.package_creation_dropdown');
        }
        return redirect()->back()->withInput();
    }

    public function package_creation_dropdown_delete(Request $request,$id){
        $previousGallery = PackageCreationDropdown::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package_creation_type');
        }

        $res = $previousGallery->delete();
        if ($res){
            PackageCreationBecomeAMember::where('package_creation_dropdown_id',$id)->delete();
            toastr()->success('Package successfully deleted.');
            return redirect()->route('webadmin.package_creation_dropdown');
        }

        return redirect()->back()->withInput();
    }

    public function package_creation_type_down(Request $request,$id){
        $previousGallery = PackageCreationDropdown::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package_creation_type');
        }

        $order_by = $previousGallery->order_by - 1;


        $previousGallery1 = PackageCreationDropdown::where('order_by',$order_by)->first();

        $previousGallery1->update(["order_by"=>$previousGallery1->order_by + 1]);

        $previousGallery->update(["order_by"=>$order_by]);
        toastr()->success('Package order change successfully.');
        return redirect()->route('webadmin.package_creation_dropdown');

        return redirect()->back()->withInput();
    }

    public function package_creation_type_up(Request $request,$id){
        $previousGallery = PackageCreationDropdown::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.package_creation_type');
        }

        $order_by = $previousGallery->order_by + 1;


        $previousGallery1 = PackageCreationDropdown::where('order_by',$order_by)->first();

        $previousGallery1->update(["order_by"=>$previousGallery1->order_by - 1]);

        $previousGallery->update(["order_by"=>$order_by]);
        toastr()->success('Package order change successfully.');
        return redirect()->route('webadmin.package_creation_dropdown');

        return redirect()->back()->withInput();
    }

    public function package_creation_setting(){
        $data = [];
        $data['setting'] = PackageCreationSetting::where('is_active',1)->first();
        return view('admin.package_creation.setting',$data);
    }

    public function package_creation_setting_update(Request $request){
        $request->validate([
            'is_reminder_email' => 'required'
        ]);

        $input = $request->all();

        $data = [
            'subcription_expiry_reminder_day' => $input['subcription_expiry_reminder_day'],
            'premium_membership_trial_day' => $input['premium_membership_trial_day'],
            'deleted_day' => $input['deleted_day'],
            'is_reminder_email' => $input['is_reminder_email'],
            'is_reminder_sms' => $input['is_reminder_sms']
        ];

        $setting = PackageCreationSetting::where('is_active',1)->first();

        $res = $setting->update($data);
        if ($res){
            toastr()->success('Setting successfully updated.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function package_creation_hint(){
        $data['gallery'] = PackageCreationHint::first();
        // dd($data);
        return view('admin.package_creation.hint',$data);
    }

    public function package_creation_dropdown_hint_update(Request $request){
        $input = $request->all();
        // dd($input);
        $packageCreationHint = PackageCreationHint::first();
        $insertData = [
            "add_on_price_hint"=>$input['add_on_price_hint'],
            "total_amount_hint"=>$input['total_amount_hint'],
            "number_of_user_hint"=>$input['number_of_user_hint'],
            "sales_presenters_hint"=>$input['sales_presenters_hint'],
            "client_proposals_hint"=>$input['client_proposals_hint'],
            "investment_suitability_profiler_hint"=>$input['investment_suitability_profiler_hint'],
            "marketing_banners_hint"=>$input['marketing_banners_hint'],
            "marketing_videos_hint"=>$input['marketing_videos_hint'],
            "premade_sales_presenter_hint"=>$input['premade_sales_presenter_hint'],
            "trail_calculator_hint"=>$input['trail_calculator_hint'],
            "famous_quotes_hint"=>$input['famous_quotes_hint'],
            "online_trainings_hint"=>$input['online_trainings_hint'],
            "premium_calculator_hint"=>$input['premium_calculator_hint'],
            "welcome_letter_hint"=>$input['welcome_letter_hint'],
            "ready_made_portfolio_hint"=>$input['ready_made_portfolio_hint'],
            "client_communication_hint"=>$input['client_communication_hint'],
            "mso_trigger_hint"=>$input['mso_trigger_hint'],
            "model_portfolio_hint"=>$input['model_portfolio_hint'],
            "scanner_hint"=>$input['scanner_hint']
        ];
        // dd($insertData);
        $packageCreationHint->update($insertData);
        toastr()->success('Hint updated successfully.');
        return redirect()->back();
    }

    public function renewal (Request $request){
        if ($request->ajax()) {
            $data = DB::table("renewal_discount_price")->orderBy('id','DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('package-creation-renewal', 'edit')){
                    $btn = '<a href="'.route('webadmin.PackageRenewalEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.package_creation.renewal');
    }

    public function renewalEdit($id){
        $data['gallery'] = DB::table("renewal_discount_price")->where('id',$id)->first();
        return view('admin.package_creation.edit_renewal',$data);
    }

    public function renewalUpdate(Request $request,$id){
        $request->validate([
            'percent' => 'required',
        ]);

        $input = $request->all();

        $previousGallery = DB::table("renewal_discount_price")->where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.PackageRenewalIndex');
        }

        $data = [
            'percent' => $input['percent']
        ];

        $res = DB::table("renewal_discount_price")->where('id',$id)->update($data);
        if ($res){
            toastr()->success('Renewal successfully updated.');
            return redirect()->route('webadmin.PackageRenewalIndex');
        }
        return redirect()->back()->withInput();
    }

}
