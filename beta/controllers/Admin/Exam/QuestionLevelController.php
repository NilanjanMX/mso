<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Models\Exam\QuestionLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class QuestionLevelController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = QuestionLevel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('exam-level', 'edit')){
                    $btn = '<a href="'.route('webadmin.questionLevelEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('exam-level', 'delete')){
                    $btn .= '<a href="'.route('webadmin.questionLevelDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.exam.level.index');
    }

    public function add(){
        return view('admin.exam.level.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name']
        ];

        $res = QuestionLevel::create($saveData);
        if ($res){
            toastr()->success('Question level successfully saved.');
            return redirect()->route('webadmin.questionLevelIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['questionlevel'] = QuestionLevel::where('id',$id)->first();
        return view('admin.exam.level.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $questionLevel = QuestionLevel::where('id',$id)->first();
        if (!$questionLevel){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionLevelIndex');
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name']
        ];

        $res = $questionLevel->update($saveData);
        if ($res){
            toastr()->success('Question level successfully updated.');
            return redirect()->route('webadmin.questionLevelIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $questionLevel = QuestionLevel::where('id',$id)->first();
        if (!$questionLevel){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionLevelIndex');
        }

        $res = $questionLevel->delete();
        if ($res){
            toastr()->success('Question level successfully deleted.');
            return redirect()->route('webadmin.questionLevelIndex');
        }

        return redirect()->back()->withInput();
    }


}