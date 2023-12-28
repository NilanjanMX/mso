<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\BecomeAMember;
use DB;

class BecomeAMemberController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = BecomeAMember::orderBy('position','ASC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('package-creation-become-a-member', 'edit')){
                    $btn = '<a href="'.route('webadmin.become_a_member_edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('package-creation-become-a-member', 'delete')){
                    $btn .= '<a href="'.route('webadmin.become_a_member_delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.become_a_member.index');
    }

    public function add(){
        return view('admin.become_a_member.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();



        $data = [
            'name' => $input['name'],
            'hint' => $input['hint'],
            'description' => $input['description'],
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = BecomeAMember::create($data);
        if ($res){
            toastr()->success('Type successfully created.');
            return redirect()->route('webadmin.become_a_member');
        }
        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['detail'] = BecomeAMember::where('id',$id)->first();
        return view('admin.become_a_member.edit',$data);
    }

    public function update(Request $request){

        
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();

        $id = $request->id;

        // dd($input);

        $previousGallery = BecomeAMember::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.become_a_member');
        }

        $data = [
            'name' => $input['name'],
            'hint' => $input['hint'],
            'description' => $input['description'],
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = BecomeAMember::where('id',$id)->update($data);
        if ($res){
            toastr()->success('Type successfully updated.');
            return redirect()->route('webadmin.become_a_member');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousGallery = BecomeAMember::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.become_a_member');
        }

        $res = $previousGallery->delete();
        if ($res){
            toastr()->success('Type successfully deleted.');
            return redirect()->route('webadmin.become_a_member');
        }

        return redirect()->back()->withInput();
    }

    public function reorder()
    {
        $datas = BecomeAMember::orderBy('position','ASC')->get();
        return view('admin.become_a_member.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = BecomeAMember::all();



        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    BecomeAMember::where('id',$id)->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }

}
