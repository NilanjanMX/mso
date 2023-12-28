<?php

namespace App\Http\Controllers\Admin;

use App\Models\Thought;
use App\Models\ThoughtCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ThoughtController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Thought::orderBy('created_at','DESC')->get();
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
                    if(is_permitted('thought-thought', 'edit')){
                    $btn = '<a href="'.route('webadmin.thoughtEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('thought-thought', 'delete')){
                    $btn .= '<a href="'.route('webadmin.thoughtDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.thought.index');
    }

    public function add(){
        $data['categories'] = ThoughtCategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.thought.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'content' => 'required',
            'category_ids' => 'required'
        ]);

        $input = $request->all();

        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        $data = [
            'content' => $input['content'],
            'thought_category_id' => $input['category'],
            'thought_category_ids' => $category_ids,
            'created_at' => $publish_date,
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = Thought::create($data);
        if ($res){
            toastr()->success('Thought successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['thought'] = Thought::where('id',$id)->first();
        $data['categories'] = ThoughtCategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.thought.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'content' => 'required',
            'category_ids' => 'required'
        ]);

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }

        $previousThought = Thought::where('id',$id)->first();
        if (!$previousThought){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.thoughtIndex');
        }
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        $data = [
            'content' => $input['content'],
            'thought_category_id' => $input['category'],
            'thought_category_ids' => $category_ids,
            'created_at' => $publish_date,
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = $previousThought->update($data);
        if ($res){
            toastr()->success('Thought successfully updated.');
            return redirect()->route('webadmin.thoughtIndex');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousThought = Thought::where('id',$id)->first();
        if (!$previousThought){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.thoughtIndex');
        }
        $res = $previousThought->delete();
        if ($res){
            toastr()->success('Thought successfully deleted.');
            return redirect()->route('webadmin.thoughtIndex');
        }

        return redirect()->back()->withInput();
    }

    // Category

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = ThoughtCategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('thought-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.thoughtcategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('thought-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.thoughtDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.thought.category_index');
    }

    public function category_add(){
        return view('admin.thought.category_add');
    }

    public function category_save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = ThoughtCategory::create($saveData);
        if ($res){
            toastr()->success('Thought category successfully saved.');
            return redirect()->route('webadmin.thoughtcategoryIndex');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['thoughtcategory'] = ThoughtCategory::where('id',$id)->first();
        return view('admin.thought.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $previousThought = ThoughtCategory::where('id',$id)->first();
        if (!$previousThought){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.thoughtcategoryIndex');
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousThought->update($saveData);
        if ($res){
            toastr()->success('Thought category successfully updated.');
            return redirect()->route('webadmin.thoughtcategoryIndex');
        }

        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousThought = ThoughtCategory::where('id',$id)->first();
        if (!$previousThought){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.thoughtcategoryIndex');
        }

        $res = $previousThought->delete();
        if ($res){
            toastr()->success('Thought category successfully deleted.');
            return redirect()->route('webadmin.thoughtcategoryIndex');
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


}
