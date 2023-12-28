<?php

namespace App\Http\Controllers\Admin;

use App\Models\Adminlogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class AdminlogoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Adminlogo::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    $url=asset("uploads/logo/original/$row->logo");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('admin-logos-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.adminlogoEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('admin-logos-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.adminlogoDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['logo','action'])
                ->make(true);
        }
        return view('admin.adminlogo.index');
    }

    public function add(){
        return view('admin.adminlogo.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        
        $saveData = [
            'title' => $input['title'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('logo')){
            $saveData['logo'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/logo');
            $img = Image::make($image->getRealPath());
            $img->resize(null, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['logo']);

            $destinationPath = public_path('/uploads/logo/original');
            $image->move($destinationPath, $saveData['logo']);
            
            /*$destinationPath = public_path('/uploads/logo');
            $image->move($destinationPath, $saveData['logo']);*/
            
        }
        
        $res = Adminlogo::create($saveData);
        if ($res){
            toastr()->success('Admin Logo successfully saved.');
            return redirect()->route('webadmin.adminlogoIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['adminlogo'] = Adminlogo::where('id',$id)->first();
        return view('admin.adminlogo.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required'
        ]);

        $previousArticle = Adminlogo::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.adminlogoIndex');
        }

        $input = $request->all();
        
        $saveData = [
            'title' => $input['title'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('logo')){
            $saveData['logo'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/logo');
            $img = Image::make($image->getRealPath());
            $img->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['logo']);
            
            $destinationPath = public_path('/uploads/logo/original');
            $image->move($destinationPath, $saveData['logo']);
            
            //$destinationPath = public_path('/uploads/logo');
            //$image->move($destinationPath, $saveData['logo']);
           //dd("ok");
            if (file_exists(public_path('uploads/logo/original/'.$previousArticle['logo']))) {
                unlink(public_path('uploads/logo/original/'.$previousArticle['logo']));
            }
            

        }

        $res = $previousArticle->update($saveData);
        if ($res){
            toastr()->success('Admin Logo successfully updated.');
            return redirect()->route('webadmin.adminlogoIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousArticle = Adminlogo::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.adminlogoIndex');
        }

        if (file_exists(public_path('uploads/logo/original/'.$previousArticle['logo']))) {
            unlink(public_path('uploads/logo/original/'.$previousArticle['logo']));
        }
        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Admin Logo successfully deleted.');
            return redirect()->route('webadmin.adminlogoIndex');
        }

        return redirect()->back()->withInput();
    }
    

}
