<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Askbrijesh;
use Yajra\DataTables\Facades\DataTables;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AskbrijeshExport;

class AskbrijeshController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Askbrijesh::orderBy('id','desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('complain-feedback-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.askbrijeshEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('complain-feedback-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.askbrijeshDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','action'])
                ->make(true);
        }
        return view('admin.askbrijesh.index');
    }

   

    public function edit($id){
        $data['askbrijesh'] = Askbrijesh::where('id',$id)->first();
        
        return view('admin.askbrijesh.edit',$data);
    }

    public function update(Request $request,$id){
        

        $askbrijesh = Askbrijesh::where('id',$id)->first();
        if (!$askbrijesh){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.askbrijesh');
        }

        $input = $request->all();
        
        $saveData = [
            'responded_by' => $input['responded_by'],
            'responded_through' => $input['responded_through'],
            'our_comment' => $input['our_comment'],
            'responded_date' => $input['responded_date'],
        ];

        $res = $askbrijesh->update($saveData);
        if ($res){
            toastr()->success('Ask Brijesh successfully updated.');
            return redirect()->route('webadmin.askbrijesh');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $Askbrijesh = Askbrijesh::where('id',$id)->first();
        if (!$Askbrijesh){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.askbrijesh');
        }

        $res = $Askbrijesh->delete();
        if ($res){
            toastr()->success('Ask Brijesh successfully deleted.');
            return redirect()->route('webadmin.askbrijesh');
        }
        return redirect()->back()->withInput();
    }
    
    public function exportaskbrijesh()
    {
        return Excel::download(new AskbrijeshExport, 'ask-brijesh-list-'.date('d-m-Y').'.xlsx');
    }

    

}
