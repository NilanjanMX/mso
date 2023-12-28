<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Models\Exam\QuestionGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class QuestionGroupController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = QuestionGroup::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('exam-group', 'edit')){
                    $btn = '<a href="'.route('webadmin.questionGroupEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('exam-group', 'delete')){
                    $btn .= '<a href="'.route('webadmin.questionGroupDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.exam.group.index');
    }

    public function add(){
        return view('admin.exam.group.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title']
        ];

        $res = QuestionGroup::create($saveData);
        if ($res){
            toastr()->success('Question group successfully saved.');
            return redirect()->route('webadmin.questionGroupIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['questiongroup'] = QuestionGroup::where('id',$id)->first();
        return view('admin.exam.group.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required'
        ]);

        $questionGroup = QuestionGroup::where('id',$id)->first();
        if (!$questionGroup){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionGroupIndex');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title']
        ];

        $res = $questionGroup->update($saveData);
        if ($res){
            toastr()->success('Question group successfully updated.');
            return redirect()->route('webadmin.questionGroupIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $questionGroup = QuestionGroup::where('id',$id)->first();
        if (!$questionGroup){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionGroupIndex');
        }

        $res = $questionGroup->delete();
        if ($res){
            toastr()->success('Question group successfully deleted.');
            return redirect()->route('webadmin.questionGroupIndex');
        }

        return redirect()->back()->withInput();
    }

}
