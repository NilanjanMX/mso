<?php

namespace App\Http\Controllers\Admin;

use App\Models\Thought;
use App\Models\ReadymadeData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReadymadeDataController extends Controller
{
    public function index(Request $request, $task){
        // dd($task);
        $data['task'] = $task;
        $data['title'] = $this->taskTitle($task);
        if ($request->ajax()) {
            $data = ReadymadeData::latest()->where('task', $request->task)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('readymade-portfolio-investment-mode', 'edit')){
                    $btn = '<a href="'.route('webadmin.readymadeportfolioEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('readymade-portfolio-investment-mode', 'delete')){
                    $btn .= '<a href="'.route('webadmin.readymadeportfolioDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.readymadePortfolio.category_index', $data);
    }

    public function add($task){
        $data['task'] = $task;
        $data['title'] = $this->taskTitle($data['task']);
        return view('admin.readymadePortfolio.category_add', $data);
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);
        // dd($request);
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'task' => $input['task'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = ReadymadeData::create($saveData);
        if ($res){
            toastr()->success('successfully saved.');
            return redirect()->route('webadmin.readymadeportfolioIndex',['task'=> $input['task']]);
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['thoughtcategory'] = ReadymadeData::where('id',$id)->first();
        $data['task'] = $data['thoughtcategory']->task;
        $data['title'] = $this->taskTitle($data['task']);
        return view('admin.readymadePortfolio.category_edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $previousThought = ReadymadeData::where('id',$id)->first();
        if (!$previousThought){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.readymadeportfolioIndex',['task'=> 'mode']);
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'task' => $input['task'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousThought->update($saveData);
        if ($res){
            toastr()->success('successfully updated.');
            return redirect()->route('webadmin.readymadeportfolioIndex',['task'=> $previousThought->task]);
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousThought = ReadymadeData::where('id',$id)->first();
        if (!$previousThought){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.readymadeportfolioIndex',['task'=> 'mode']);
        }

        $res = $previousThought->delete();
        if ($res){
            toastr()->success('successfully deleted.');
            return redirect()->route('webadmin.readymadeportfolioIndex',['task'=> $previousThought->task]);
        }

        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Thought::orderBy('position','ASC')->get();
        return view('admin.thought.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Thought::all();

        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $data->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }

    public function taskTitle($task){
        if($task == 'mode'){
            return 'Investment Mode';
        }elseif($task == 'profile'){
            return 'Risk Profile';
        }elseif($task == 'portfolio'){
            return 'Portfolio';
        }elseif($task == 'period'){
            return 'Period';
        }elseif($task == 'amount'){
            return 'Amount';
        }
    }


}
