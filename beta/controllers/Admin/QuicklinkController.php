<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quicklink;
use App\Models\Quicklinkmenu;
use Yajra\DataTables\Facades\DataTables;


class QuicklinkController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Quicklink::latest()->get();
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
                ->addColumn('quicklink_category', function ($row) {
                    if($row->quicklinkmenus_id != 0){
                        $Quicklinkmenu = Quicklinkmenu::where('id',$row->quicklinkmenus_id)->first();
                        $quicklink_category = $Quicklinkmenu->title;
                    }else{
                        $quicklink_category = '';
                    }

                    return $quicklink_category;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('right-sidebar-settings-quick-link', 'edit')){
                    $btn = '<a href="'.route('webadmin.quicklinkEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('right-sidebar-settings-quick-link', 'delete')){
                    $btn .= '<a href="'.route('webadmin.quicklinkDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['quicklink_category','action','status'])
                ->make(true);
        }
        return view('admin.quicklink.index');
    }

    public function add(){
        $data['quicklinkmenus'] = Quicklinkmenu::get();
        //dd($data);
        return view('admin.quicklink.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'quicklinkmenu' => 'required',
            'title' => 'required',
            'redirect_url' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'quicklinkmenus_id' => $input['quicklinkmenu'],
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Quicklink::create($saveData);
        if ($res){
            toastr()->success('Quick Link successfully saved.');
            return redirect()->route('webadmin.quicklink');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['quicklinkmenus'] = Quicklinkmenu::get();
        $data['quicklink'] = Quicklink::where('id',$id)->first();
        return view('admin.quicklink.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'quicklinkmenu' => 'required',
            'title' => 'required',
            'redirect_url' => 'required',
        ]);

        $previousPage = Quicklink::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.quicklink');
        }

        $input = $request->all();
        //dd($input);
        $saveData = [
            'quicklinkmenus_id' => $input['quicklinkmenu'],
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Quick Link successfully updated.');
            return redirect()->route('webadmin.quicklink');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previous = Quicklink::where('id',$id)->first();
        if (!$previous){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.quicklink');
        }

        $res = $previous->delete();
        if ($res){
            toastr()->success('Quick Link successfully deleted.');
            return redirect()->route('webadmin.quicklink');
        }
        return redirect()->back()->withInput();
    }

}
