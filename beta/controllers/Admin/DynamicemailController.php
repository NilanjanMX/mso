<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use DB;


class DynamicemailController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table("dynamic_email")->orderBy('page_name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('dynamic-email-dynamic-email', 'edit')){
                    $btn = '<a href="'.route('webadmin.dynamic-email-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['page_name','subject','action'])
                ->make(true);
        }
        // ,'email_header','email_footer'
        return view('admin.dynamic_email.index');
    }

    public function edit($id){
        $data['blog'] = DB::table("dynamic_email")->where('id',$id)->first();
        // dd($data);
        return view('admin.dynamic_email.edit',$data);
    }

    public function update(Request $request,$id){

        $input = $request->all();

        $request->validate([
            'page_name' => 'required',
            'subject' => 'required',
            'email_header' => 'required'
        ]);

        $previousBlog = DB::table("dynamic_email")->where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.dynamic-email-edit');
        }

        $saveData = [
            'page_name' => $input['page_name'],
            'subject' => $input['subject'],
            'email_header' => $input['email_header'],
            'email_footer' => $input['email_footer']
        ];

        $res = DB::table("dynamic_email")->where('id',$id)->update($saveData);
        if ($res){
            toastr()->success('Email successfully saved.');
            return redirect()->route('webadmin.dynamic-email-index');
        }

        return redirect()->back()->withInput();
    }

}
