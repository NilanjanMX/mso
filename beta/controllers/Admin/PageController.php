<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Page::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('all-pages-pages', 'edit')){
                    $btn = '<a href="'.route('webadmin.pageEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    //$btn .= '<a href="'.route('webadmin.blogDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.page.index');
    }

    public function add(){
        return view('admin.page.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Page::create($saveData);
        if ($res){
            toastr()->success('Page successfully saved.');
            return redirect()->route('webadmin.pageIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['page'] = Page::where('id',$id)->first();
        return view('admin.page.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $previousPage = Page::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.pageIndex');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Page successfully updated.');
            return redirect()->route('webadmin.pageIndex');
        }

        return redirect()->back()->withInput();
    }

}
