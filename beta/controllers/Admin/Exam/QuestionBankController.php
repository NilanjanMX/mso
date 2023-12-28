<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Models\Exam\OnlineExamQuestion;
use App\Models\Exam\QuestionBank;
use App\Models\Exam\QuestionGroup;
use App\Models\Exam\QuestionLevel;
use App\Models\Exam\QuestionOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class QuestionBankController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = QuestionBank::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('group', function($row){
                    $group = QuestionGroup::where('id',$row->question_group_id)->first();
                    $group_info = $group['title'];
                    return $group_info;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('exam-bank', 'edit')){
                        $btn = '<a href="'.route('webadmin.questionBankEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    
                    if(is_permitted('exam-bank', 'delete')){
                        $btn .= '<a href="'.route('webadmin.questionBankDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['question','group','action'])
                ->make(true);
        }
        return view('admin.exam.bank.index');
    }

    public function add(){
        $data['questiongroups'] = QuestionGroup::orderBy('title','asc')->get();
        $data['questionlevels'] = QuestionLevel::orderBy('name','asc')->get();
        return view('admin.exam.bank.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'group' => 'required',
            'level' => 'required',
            'question' => 'required',
            'upload_file' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'mark' => 'required',
            'totalOption' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'explanation' => $input['explanation'],
            'question_level_id' => $input['level'],
            'question_group_id' => $input['group'],
            'total_option' => $input['totalOption'],
            'mark' => $input['mark'],
        ];

        if ($image = $request->file('upload_file')){
            $saveData['upload_file'] = time().'.'.$image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $destinationPath = public_path('/uploads/exam');
            $image->move($destinationPath, $saveData['upload_file']);
        }

        $res = QuestionBank::create($saveData);
        if ($res){

            if (isset($input['totalOption']) && $input['totalOption']>0){
                for ($i=0;$i<$input['totalOption'];$i++){
                    $ans_key = ($input['answer'][0] - 1);
                    $savOpt = [
                        'title' => $input['option'][$i],
                        'is_answer' => ($ans_key==$i)?1:0,
                        'question_bank_id' => $res['id']
                    ];
                    QuestionOption::create($savOpt);
                }
            }
            toastr()->success('Question bank successfully saved.');
            return redirect()->route('webadmin.questionBankIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['questiongroups'] = QuestionGroup::orderBy('title','asc')->get();
        $data['questionlevels'] = QuestionLevel::orderBy('name','asc')->get();
        $data['questiondetails'] = QuestionBank::where('id',$id)->first();
        return view('admin.exam.bank.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'group' => 'required',
            'level' => 'required',
            'question' => 'required',
            'upload_file' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'mark' => 'required',
            'totalOption' => 'required',
        ]);

        $questionBank = QuestionBank::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionBankIndex');
        }

        $input = $request->all();

        $saveData = [
            'question' => $input['question'],
            'explanation' => $input['explanation'],
            'question_level_id' => $input['level'],
            'question_group_id' => $input['group'],
            'total_option' => $input['totalOption'],
            'mark' => $input['mark']
        ];

        if ($image = $request->file('upload_file')){
            $saveData['upload_file'] = time().'.'.$image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $destinationPath = public_path('/uploads/exam');
            $image->move($destinationPath, $saveData['upload_file']);
            if(!empty($questionBank['upload_file'])){
                if (file_exists(public_path('uploads/exam/'.$questionBank['upload_file']))) {
                    chmod(public_path('uploads/exam/'.$questionBank['upload_file']), 0644);
                    unlink(public_path('uploads/exam/'.$questionBank['upload_file']));
                }
            }

        }

        $res = $questionBank->update($saveData);
        if ($res){
            $delExtOpt = QuestionOption::where('question_bank_id',$id);
            if ($delExtOpt){
                $delExtOpt->delete();
            }

            if (isset($input['totalOption']) && $input['totalOption']>0){
                $ans_key = ($input['answer'][0] - 1);
                for ($i=0;$i<$input['totalOption'];$i++){
                    $savOpt = [
                        'title' => $input['option'][$i],
                        'is_answer' => ($ans_key==$i)?1:0,
                        'question_bank_id' => $id
                    ];
                    QuestionOption::create($savOpt);
                }
            }
           // return $input;

            toastr()->success('Question bank successfully updated.');
            return redirect()->route('webadmin.questionBankIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $delExtOpt = QuestionOption::where('question_bank_id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }

        $questionBank = QuestionBank::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionBankIndex');
        }

        $res = $questionBank->delete();
        if ($res){
            $onlineExamQuestion = OnlineExamQuestion::where('question_bank_id',$id);
            if (isset($onlineExamQuestion)){
                $onlineExamQuestion->delete();
            }
            toastr()->success('Question bank successfully deleted.');
            return redirect()->route('webadmin.questionBankIndex');
        }

        return redirect()->back()->withInput();
    }

    public function removeExplanation($id){
        $questionBank = QuestionBank::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.questionBankIndex');
        }

        if(!empty($questionBank['upload_file'])){
            if (file_exists(public_path('uploads/exam/'.$questionBank['upload_file']))) {
                chmod(public_path('uploads/exam/'.$questionBank['upload_file']), 0644);
                unlink(public_path('uploads/exam/'.$questionBank['upload_file']));
            }
            $questionBank->update(['upload_file'=>'']);
        }
        toastr()->success('Image successfully removed.');
        return redirect()->back();
    }

}
