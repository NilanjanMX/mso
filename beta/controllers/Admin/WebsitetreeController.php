<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Websitetree;
use Yajra\DataTables\Facades\DataTables;

class WebsitetreeController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Websitetree::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
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
                    if(is_permitted('website-tree-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.websitetreeEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('website-tree-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.websitetreeDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('admin.websitetree.index');
    }

    public function add(){
        return view('admin.websitetree.add');
    }

    public function save(Request $request){
        $request->validate([
            'link_lable' => 'required',
            'link' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'link_lable' => $input['link_lable'],
            'link' => $input['link'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Websitetree::create($saveData);
        if ($res){
            toastr()->success('Website Tree successfully saved.');
            return redirect()->route('webadmin.websitetree');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['websitetree'] = Websitetree::where('id',$id)->first();
        return view('admin.websitetree.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'link_lable' => 'required',
            'link' => 'required',
        ]);

        $previousPage = Websitetree::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.websitetree');
        }

        $input = $request->all();
        $saveData = [
            'link_lable' => $input['link_lable'],
            'link' => $input['link'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Website Tree successfully updated.');
            return redirect()->route('webadmin.websitetree');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previous = Websitetree::where('id',$id)->first();
        if (!$previous){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.websitetree');
        }

        $res = $previous->delete();
        if ($res){
            toastr()->success('Website Tree successfully deleted.');
            return redirect()->route('webadmin.websitetree');
        }
        return redirect()->back()->withInput();
    }
    
    public function showDatatable()
    {
        $datas = Websitetree::orderBy('position','ASC')->get();
        return view('admin.websitetree.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Websitetree::all();

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
    
}
