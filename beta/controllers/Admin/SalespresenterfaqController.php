<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Salespresenterfaq;

class SalespresenterfaqController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Salespresenterfaq::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('updated_at', function ($row) {
                    
                    if(!empty($row->updated_at)){
                        $updated_at = date('d-m-Y',strtotime($row->updated_at));
                    }

                    return $updated_at;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('sales-presenters-faq', 'edit')){
                    $btn = '<a href="'.route('webadmin.salespresenterfaqEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('sales-presenters-faq', 'delete')){
                    $btn .= '<a href="'.route('webadmin.salespresenterfaqDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','updated_at','status','action'])
                ->make(true);
        }
        return view('admin.salespresenterfaq.index');
    }

    public function add(){
        return view('admin.salespresenterfaq.add');
    }

    public function save(Request $request){
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'answer' => $input['answer'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Salespresenterfaq::create($saveData);
        if ($res){
            toastr()->success('Sales Presenters faq successfully saved.');
            return redirect()->route('webadmin.salespresenterfaq');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['faq'] = Salespresenterfaq::where('id',$id)->first();
        return view('admin.salespresenterfaq.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);
        $previousBlog = Salespresenterfaq::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salespresenterfaq');
        }
        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'answer' => $input['answer'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Sales Presenters Faq successfully updated.');
            return redirect()->route('webadmin.salespresenterfaq');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = Salespresenterfaq::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salespresenterfaq');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Sales Presenters Faq successfully deleted.');
            return redirect()->route('webadmin.salespresenterfaq');
        }
        return redirect()->back()->withInput();
    }

}
