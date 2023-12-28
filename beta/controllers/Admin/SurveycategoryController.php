<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Surveycategory;
use App\Models\Survey;
use Yajra\DataTables\Facades\DataTables;


class SurveycategoryController extends Controller
{

    public function index(Request $request){
        if ($request->ajax()) {
            $data = Survey::with('category')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    $category = '';
                    if(!empty($row->category->name)){
                        $category = $row->category->name;
                    }

                    return $category;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('ifa-tools-survey-survey', 'edit')){
                    $btn = '<a href="'.route('webadmin.surveyEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('ifa-tools-survey-survey', 'delete')){
                    $btn .= '<a href="'.route('webadmin.surveyDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['category','action'])
                ->make(true);
        }
        return view('admin.survey.index');
    }

    public function add(){
        $categories = Surveycategory::orderBy('id','desc')->get();

        return view('admin.survey.add')->with(compact('categories'));
    }

    public function save(Request $request){
        $request->validate([
            'surveycategory_id' => 'required',
            'options' => 'required',
            'respondents' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'surveycategory_id' => $input['surveycategory_id'],
            'options' => $input['options'],
            'respondents' => $input['respondents'],
        ];

        $res = Survey::create($saveData);
        if ($res){
            toastr()->success('Survey successfully saved.');
            return redirect()->route('webadmin.survey');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['survey'] = Survey::where('id',$id)->first();
        $data['categories'] = Surveycategory::orderBy('id','asc')->get();
        return view('admin.survey.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'surveycategory_id' => 'required',
            'options' => 'required',
            'respondents' => 'required',
        ]);

        $previousSurvey = Survey::where('id',$id)->first();
        if (!$previousSurvey){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.survey');
        }

        $input = $request->all();
        $saveData = [
            'surveycategory_id' => $input['surveycategory_id'],
            'options' => $input['options'],
            'respondents' => $input['respondents'],
        ];

        $res = $previousSurvey->update($saveData);
        if ($res){
            toastr()->success('Survey successfully Updated.');
            return redirect()->route('webadmin.survey');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousSurvey = Survey::where('id',$id)->first();
        if (!$previousSurvey){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.survey');
        }

        $res = $previousSurvey->delete();
        if ($res){
            toastr()->success('Survey successfully deleted.');
            return redirect()->route('webadmin.survey');
        }
        return redirect()->back()->withInput();
    }

// Survey Category

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = Surveycategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
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
                    if(is_permitted('ifa-tools-survey-survey-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.surveycategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('ifa-tools-survey-survey-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.surveycategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.survey.category_index');
    }

    public function category_add(){
        return view('admin.survey.category_add');
    }

    public function category_save(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Surveycategory::create($saveData);
        if ($res){
            toastr()->success('Survey Category successfully saved.');
            return redirect()->route('webadmin.surveycategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['category'] = Surveycategory::where('id',$id)->first();
        return view('admin.survey.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required',
        ]);

        $previousCategory = Surveycategory::where('id',$id)->first();
        if (!$previousCategory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.surveycategory');
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousCategory->update($saveData);
        if ($res){
            toastr()->success('Survey Category successfully saved.');
            return redirect()->route('webadmin.surveycategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousCategory = Surveycategory::where('id',$id)->first();
        if (!$previousCategory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.surveycategory');
        }

        $res = $previousCategory->delete();
        if ($res){
            toastr()->success('Survey Category successfully deleted.');
            return redirect()->route('webadmin.surveycategory');
        }
        return redirect()->back()->withInput();
    }

}
