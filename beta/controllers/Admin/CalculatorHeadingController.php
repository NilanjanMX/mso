<?php

namespace App\Http\Controllers\Admin;

use App\Models\CalculatorHeading;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CalculatorHeadingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = CalculatorHeading::latest()->orderBy('name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-heading', 'edit')){
                    $btn = '<a href="'.route('webadmin.calculatorHeadingEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-heading', 'delete')){
                    $btn .= '<a href="'.route('webadmin.calculatorHeadingDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.calculator_heading.index');
    }

    public function add(){
        return view('admin.calculator_heading.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'boi' => isset($input['boi'])?$input['boi']:"",
            'key_name' => isset($input['key_name'])?$input['key_name']:"",
            'is_active' => isset($input['status'])?1:0
        ];

        $res = CalculatorHeading::create($saveData);
        if ($res){
            toastr()->success('Calculator heading successfully saved.');
            return redirect()->route('webadmin.calculatorHeadingIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['page'] = CalculatorHeading::where('id',$id)->first();
        return view('admin.calculator_heading.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
        ]);

        $previousPage = CalculatorHeading::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.calculatorHeadingIndex');
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'boi' => isset($input['boi'])?$input['boi']:"",
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Calculator heading successfully updated.');
            return redirect()->route('webadmin.calculatorHeadingIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete($id){
        $previousPage = CalculatorHeading::where('id',$id)->first();
        $res = $previousPage->delete();
        if ($res){
            toastr()->success('Calculator heading successfully deleted.');
            return redirect()->route('webadmin.calculatorHeadingIndex');
        }
        return redirect()->back()->withInput();
    }

}
