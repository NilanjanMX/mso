<?php

namespace App\Http\Controllers\Admin;

use App\Models\CalculatorHeading;
use App\Models\PackageCreationDropdown;
use App\Models\Calculator_category;
use App\Models\Calculator;
use App\Models\Calculator_category_value;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use DB;

class CalculatorCategoryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table("calculator_categories")->orderBy('name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-calculator-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.calculatorCategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-calculator-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.calculatorCategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    if(is_permitted('calculator-calculator-category', 'order')){
                    $btn .= '<a href="'.route('webadmin.calculator_reorder',['id'=> $row->id ]).'"  class="edit btn btn-secondary btn-sm ml-1">Order Calculators</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.calculator.category.index');
    }

    public function add(){
        $data = [];
        $data['calculator_list'] = DB::table("calculators")->where("status",1)->get();
        return view('admin.calculator.category.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'description' => isset($input['description'])?$input['description']:"",
            'status' => isset($input['status'])?1:0
        ];
        $insertData = [];
        $insertData['calculator_category_id'] = DB::table("calculator_categories")->insertGetId($saveData);

        $calculator_list = $request->calculator_list;
        if($calculator_list){
            foreach ($calculator_list as $key => $value) {
                $insertData['calculator_id'] = $value;
                DB::table("calculator_category_values")->insert($insertData);
            }
        }
            
        toastr()->success('Calculator category successfully saved.');
        return redirect()->route('webadmin.calculatorCategoryIndex');
    }

    public function edit($id){
        $data['detail'] = DB::table("calculator_categories")->where('id',$id)->first();
        $data['calculator_list'] = DB::table("calculators")->where("status",1)->get();

        foreach ($data['calculator_list'] as $key => $value) {
            $calculator_category_values = DB::table("calculator_category_values")->where('calculator_id',$value->id)->where('calculator_category_id',$id)->first();
            if($calculator_category_values){
                $data['calculator_list'][$key]->is_checked = true;
            }else{
                $data['calculator_list'][$key]->is_checked = false;
            }
        }
        // dd($data);
        return view('admin.calculator.category.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'description' => isset($input['description'])?$input['description']:"",
            'status' => isset($input['status'])?1:0
        ];

        DB::table("calculator_categories")->where('id',$id)->update($saveData);

        $calculator_list = $request->calculator_list;

        DB::table("calculator_category_values")->where('calculator_category_id',$id)->delete();

        $insertData = [];
        $insertData['calculator_category_id'] = $id;

        if($calculator_list){
            foreach ($calculator_list as $key => $value) {
                $insertData['calculator_id'] = $value;
                DB::table("calculator_category_values")->insert($insertData);
            }
        }

        toastr()->success('Calculator category successfully updated.');
        return redirect()->route('webadmin.calculatorCategoryIndex');
    }

    public function delete($id){
        if ($id){
            DB::table("calculator_categories")->where('id',$id)->delete();
            DB::table("calculator_category_values")->where('calculator_category_id',$id)->delete();
            toastr()->success('Calculator category successfully deleted.');
            return redirect()->route('webadmin.calculatorCategoryIndex');
        }
        return redirect()->back()->withInput();
    }

    public function calculatorCategoryReorder(){
        $datas = Calculator_category::orderBy('position','ASC')->get();
        return view('admin.calculator.category.reorder',compact('datas'));
    }

    public function calculatorCategoryReorderUpdate(Request $request){
        $datas = Calculator_category::all();
        // dd($datas);
        foreach ($request->order as $order) {
            Calculator_category::where('id',$order['id'])->update(['position' => $order['position']]);
        }
        
        return response('Update Successfully.', 200);
    }

    public function calculator(Request $request){
        if ($request->ajax()) {
            $data = DB::table("calculators")->orderBy('name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.calculatorEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.calculatorDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->addColumn('category_list', function($row){
                    $calculator_permissions = DB::table("calculator_category_values")->select(['calculator_categories.name'])->LeftJoin('calculator_categories', 'calculator_categories.id', '=', 'calculator_category_values.calculator_category_id')->where("calculator_id",$row->id)->get();
                    $btn = '';
                    foreach ($calculator_permissions as $key => $value) {
                        if($btn){
                            $btn .= ", ".$value->name;
                        }else{
                            $btn = $value->name;
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action','category_list'])
                ->make(true);
        }
        return view('admin.calculator.index');
    }

    public function calculatorAdd(){
        $data = [];
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();
        return view('admin.calculator.add',$data);
    }

    public function calculatorSave(Request $request){
        $request->validate([
            'name' => 'required',
            'url' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'url' => $input['url'],
            'description' => isset($input['description'])?$input['description']:"",
            'type' => isset($input['type'])?$input['type']:"",
            'youtube_video' => isset($input['youtube_video'])?$input['youtube_video']:"",
            'status' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('how_to_use')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['how_to_use'] = $file;

            $destinationPath = public_path('/uploads/how_to_use');
            $image->move($destinationPath, $file);
            if($previousCalculator->how_to_use){
                if (file_exists(public_path('uploads/how_to_use/'.$previousCalculator->how_to_use))) {
                    chmod(public_path('uploads/how_to_use/'.$previousCalculator->how_to_use), 0644);
                    unlink(public_path('uploads/how_to_use/'.$previousCalculator->how_to_use));
                }
            } 
        }

        if ($image = $request->file('case_study_pdf')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['case_study_pdf'] = $file;

            $destinationPath = public_path('/uploads/case_study_pdf');
            $image->move($destinationPath, $file);
            if($previousCalculator->case_study_pdf){
                if (file_exists(public_path('uploads/case_study_pdf/'.$previousCalculator->case_study_pdf))) {
                    chmod(public_path('uploads/case_study_pdf/'.$previousCalculator->case_study_pdf), 0644);
                    unlink(public_path('uploads/case_study_pdf/'.$previousCalculator->case_study_pdf));
                }
            } 
        }
        $calculator_id = DB::table("calculators")->insertGetId($saveData);

        $package_view = $request->package_view;
        $package_download = $request->package_download;
        $package_save = $request->package_save;
        $package_cover = $request->package_cover;
        $package_list = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();

        $insertData = [];
        $insertData['calculator_id'] = $calculator_id;

        foreach ($package_list as $key => $value) {
            $insertData['package_id'] = $value->id;
            $insertData['is_view'] = isset($package_view[$value->id])?1:0;
            $insertData['is_download'] = isset($package_download[$value->id])?1:0;
            $insertData['is_save'] = isset($package_save[$value->id])?1:0;
            $insertData['is_cover'] = isset($package_cover[$value->id])?1:0;

            DB::table("calculator_permissions")->insert($insertData);
        }
        toastr()->success('Calculator successfully saved.');
        return redirect()->route('webadmin.calculatorIndex');
    }

    public function calculatorEdit($id){
        $data['detail'] = DB::table("calculators")->where('id',$id)->first();
        $data['package_list'] = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();
        foreach ($data['package_list'] as $key => $value) {
            $calculator_permissions = DB::table("calculator_permissions")->where("package_id",$value->id)->where("calculator_id",$id)->first();
            if($calculator_permissions){
                $data['package_list'][$key]->is_view = ($calculator_permissions->is_view)?true:false;
                $data['package_list'][$key]->is_download = ($calculator_permissions->is_download)?true:false;
                $data['package_list'][$key]->is_save = ($calculator_permissions->is_save)?true:false;
                $data['package_list'][$key]->is_cover = ($calculator_permissions->is_cover)?true:false;
            }else{
                $data['package_list'][$key]->is_view = false;
                $data['package_list'][$key]->is_download = false;
                $data['package_list'][$key]->is_save = false;
                $data['package_list'][$key]->is_cover = false;
            }
        }
        return view('admin.calculator.edit',$data);
    }

    public function calculatorUpdate(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'url' => 'required',
        ]);

        $previousCalculator = DB::table("calculators")->where('id',$id)->first();

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'url' => $input['url'],
            'description' => isset($input['description'])?$input['description']:"",
            'type' => isset($input['type'])?$input['type']:"",
            'youtube_video' => isset($input['youtube_video'])?$input['youtube_video']:"",
            'status' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('how_to_use')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['how_to_use'] = $file;

            $destinationPath = public_path('/uploads/how_to_use');
            $image->move($destinationPath, $file);
            if($previousCalculator->how_to_use){
                if (file_exists(public_path('uploads/how_to_use/'.$previousCalculator->how_to_use))) {
                    chmod(public_path('uploads/how_to_use/'.$previousCalculator->how_to_use), 0644);
                    unlink(public_path('uploads/how_to_use/'.$previousCalculator->how_to_use));
                }
            } 
        }

        if ($image = $request->file('case_study_pdf')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['case_study_pdf'] = $file;

            $destinationPath = public_path('/uploads/case_study_pdf');
            $image->move($destinationPath, $file);
            if($previousCalculator->case_study_pdf){
                if (file_exists(public_path('uploads/case_study_pdf/'.$previousCalculator->case_study_pdf))) {
                    chmod(public_path('uploads/case_study_pdf/'.$previousCalculator->case_study_pdf), 0644);
                    unlink(public_path('uploads/case_study_pdf/'.$previousCalculator->case_study_pdf));
                }
            } 
        }

        DB::table("calculators")->where('id',$id)->update($saveData);

        DB::table("calculator_permissions")->where('calculator_id',$id)->delete();
        $package_view = $request->package_view;
        $package_download = $request->package_download;
        $package_save = $request->package_save;
        $package_cover = $request->package_cover;
        $package_list = PackageCreationDropdown::select(['id','name'])->where("is_active",1)->orderBy('order_by','DESC')->get();

        $insertData = [];
        $insertData['calculator_id'] = $id;

        foreach ($package_list as $key => $value) {
            $insertData['package_id'] = $value->id;
            $insertData['is_view'] = isset($package_view[$value->id])?1:0;
            $insertData['is_download'] = isset($package_download[$value->id])?1:0;
            $insertData['is_save'] = isset($package_save[$value->id])?1:0;
            $insertData['is_cover'] = isset($package_cover[$value->id])?1:0;

            DB::table("calculator_permissions")->insert($insertData);
        }

        toastr()->success('Calculator successfully updated.');
        return redirect()->route('webadmin.calculatorIndex');
    }

    public function calculatorDelete($id){
        if ($id){
            DB::table("calculators")->where('id',$id)->delete();
            toastr()->success('Calculator successfully deleted.');
            return redirect()->route('webadmin.calculatorIndex');
        }
        return redirect()->back()->withInput();
    }

    public function removeHowTo($id){
        if ($id){
            DB::table("calculators")->where('id',$id)->update(["how_to_use"=>""]);
            toastr()->success('Calculator how to user successfully removed.');
            return redirect()->route('webadmin.calculatorIndex');
        }
        return redirect()->back()->withInput();
    }

    public function removeCaseStudy($id){
        if ($id){
            DB::table("calculators")->where('id',$id)->update(["case_study_pdf"=>""]);
            toastr()->success('Calculator case study successfully removed.');
            return redirect()->route('webadmin.calculatorIndex');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable($id){
        $datas = Calculator_category_value::select(['calculators.name','calculator_category_values.id','calculator_category_values.calculator_id'])->LeftJoin('calculators', 'calculator_category_values.calculator_id', '=', 'calculators.id')->where("calculator_category_id",$id)->orderBy('calculator_category_values.position','ASC')->get();
        return view('admin.calculator.reorder',compact('datas'));
    }

    public function updateOrder(Request $request){
        // $datas = Calculator::all();
        // dd($datas);
        foreach ($request->order as $order) {
            DB::table("calculator_category_values")->where('id',$order['id'])->update(['position' => $order['position']]);
        }
        
        return response('Update Successfully.', 200);
    }

}
