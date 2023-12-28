<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Models\Exam\OnlineExam;
use App\Models\Exam\OnlineExamUserStatus;
use App\Models\Exam\QuestionGroup;
use App\Models\Exam\QuestionLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $data['online_exam_user_status'] = OnlineExamUserStatus::where([
                'online_exam_id'=> $input['exam'],
                'is_pass' => $input['status']
            ])->get();
            return view('admin.exam.report.list',$data);
        }
        $data['olineexamlist'] = OnlineExam::orderBy('name','asc')->get();
        return view('admin.exam.report.index',$data);
    }
}
