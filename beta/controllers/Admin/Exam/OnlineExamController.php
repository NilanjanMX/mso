<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Models\Exam\OnlineExam;
use App\Models\Exam\OnlineExamQuestion;
use App\Models\Exam\QuestionBank;
use App\Models\Exam\QuestionGroup;
use App\Models\Exam\QuestionLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OnlineExamController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = OnlineExam::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('view_details', function ($row) {
                    if($row->id !=''){
                        $view_details = '<a href="'.route('frontend.exam.shareable',['id'=> $row->id ]).'" target="_blank">View Details</a>';
                    }else{
                        $view_details = '';
                    }
                    return $view_details;
                })
                ->addColumn('status', function($row){
                    $btn = '';
                    if(is_permitted('exam-online-exam', 'changestatus')){
                        if ($row->is_active==0){
                            $btn = '<a href="'.route('webadmin.onlineExamStatus',['id'=> $row->id,'flg'=>'1' ]).'" class="edit btn btn-danger btn-sm mr-1" >No</a>';
                        }else{
                            $btn = '<a href="'.route('webadmin.onlineExamStatus',['id'=> $row->id,'flg'=>'0' ]).'" class="edit btn btn-success btn-sm mr-1" >Yes</a>';
                        }
                    }else{
                        if ($row->is_active==0){
                            $btn = '<span class="edit btn btn-danger btn-sm mr-1" >No</span>';
                        }else{
                            $btn = '<span class="edit btn btn-success btn-sm mr-1" >Yes</span>';
                        }
                    }
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $btn = ''; 
                    if(is_permitted('exam-online-exam', 'addquestion')){
                        $btn = '<a href="'.route('webadmin.onlineExamAddQuestion',['id'=> $row->id ]).'" class="edit btn btn-info btn-sm mr-1" title="Add Question">Add</a>';
                    }if(is_permitted('exam-online-exam', 'edit')){
                        $btn .= '<a href="'.route('webadmin.onlineExamEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }if(is_permitted('exam-online-exam', 'delete')){
                        $btn .= '<a href="'.route('webadmin.onlineExamDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['view_details','action','status'])
                ->make(true);
        }
        return view('admin.exam.onlineexam.index');
    }

    public function add(){
        $data['questionlevels'] = QuestionLevel::orderBy('name','asc')->get();
        return view('admin.exam.onlineexam.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'exam_level' => 'required',
            'exam_status' => 'required',
            'duration' => 'required',
            'mark_type' => 'required',
            'pass_value' => 'required',
            'negative_mark' => 'required',
            'enable_random' => 'required',
            'published' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'exam_level' => $input['exam_level'],
            'exam_status' => $input['exam_status'],
            'duration' => $input['duration'],
            'mark_type' => $input['mark_type'],
            'percentage' => $input['pass_value'],
            'negative_mark' => $input['negative_mark'],
            'random_status' => $input['enable_random'],
            'is_active' => $input['published'],
            'description' => isset($input['description'])?$input['description']:null
        ];

        $res = OnlineExam::create($saveData);
        if ($res){
            toastr()->success('Online exam successfully saved.');
            return redirect()->route('webadmin.onlineExamIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['questionlevels'] = QuestionLevel::orderBy('name','asc')->get();
        $data['onlineexam'] = OnlineExam::where('id',$id)->first();
        return view('admin.exam.onlineexam.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'exam_level' => 'required',
            'exam_status' => 'required',
            'duration' => 'required',
            'mark_type' => 'required',
            'pass_value' => 'required',
            'negative_mark' => 'required',
            'enable_random' => 'required',
            'published' => 'required',
        ]);

        $OnlineExam = OnlineExam::where('id',$id)->first();
        if (!$OnlineExam){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.onlineExamIndex');
        }

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'exam_level' => $input['exam_level'],
            'exam_status' => $input['exam_status'],
            'duration' => $input['duration'],
            'mark_type' => $input['mark_type'],
            'percentage' => $input['pass_value'],
            'negative_mark' => $input['negative_mark'],
            'random_status' => $input['enable_random'],
            'is_active' => $input['published'],
            'description' => isset($input['description'])?$input['description']:null
        ];

        $res = $OnlineExam->update($saveData);
        if ($res){
            toastr()->success('Online exam successfully updated.');
            return redirect()->route('webadmin.onlineExamIndex');
        }

        return redirect()->back()->withInput();
    }

    public function status(Request $request,$id,$flg)
    {

        $OnlineExam = OnlineExam::where('id', $id)->first();
        if (!$OnlineExam) {
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.onlineExamIndex');
        }

        $saveData = [
            'is_active' => $flg
        ];

        $res = $OnlineExam->update($saveData);
        if ($res){
            toastr()->success('Online exam publishe status successfully updated.');
            return redirect()->route('webadmin.onlineExamIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $onlineExam = OnlineExam::where('id',$id)->first();
        if (!$onlineExam){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.onlineExamIndex');
        }

        $res = $onlineExam->delete();
        if ($res){
            toastr()->success('Question bank successfully deleted.');
            return redirect()->route('webadmin.onlineExamIndex');
        }

        return redirect()->back()->withInput();
    }

    public function addQuestion($id){
        $data['questiongroups'] = QuestionGroup::orderBy('title','asc')->get();
        $data['questionlevels'] = QuestionLevel::orderBy('name','asc')->get();
        $data['onlineexam'] = OnlineExam::where('id', $id)->first();
        return view('admin.exam.onlineexam.add_question',$data);
    }

    public function getQuestionList(Request $request){
        $input = $request->all();
        $questionQuery = QuestionBank::query();
        if ($input['level'] && $input['level']!=''){
            $questionQuery = $questionQuery->where([
                ['question_level_id','=',$input['level']]
            ]);
        }

        if ($input['group'] && $input['group']!=''){
            $questionQuery = $questionQuery->where([
                ['question_group_id','=',$input['group']]
            ]);
        }

        $data['question_list'] = $questionQuery->get();

        return view('admin.exam.onlineexam.add_questions_list',$data);;
    }

    public function saveExamQuestion(Request $request){
        $input = $request->all();
        $existCheck = OnlineExamQuestion::where([
            'online_exam_id' => $input['exam'],
            'question_bank_id' => $input['question']
        ])->first();
        if (!$existCheck){
            OnlineExamQuestion::create([
                'online_exam_id' => $input['exam'],
                'question_bank_id' => $input['question']
            ]);
        }
    }

    public function getExamQuestionList(Request $request){
        $input = $request->all();
        $data['exam_question_list'] = OnlineExamQuestion::where('online_exam_id',$input['exam'])->get();
        return view('admin.exam.onlineexam.exam_question_list',$data);;
    }

    public function getExamQuestionSummaryList(Request $request){
        $input = $request->all();
        $data['exam_question_list'] = OnlineExamQuestion::where('online_exam_id',$input['exam'])->get();
        return view('admin.exam.onlineexam.exam_question_summary_list',$data);;
    }

    public function removeExamQuestion(Request $request){
        $input = $request->all();
        $existCheck = OnlineExamQuestion::where([
            'id' => $input['exam_q_id']
        ])->first();
        if ($existCheck){
            $existCheck->delete();
        }
    }


}
