<?php

namespace App\Http\Controllers\Admin;

use App\Models\PageShare;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class PageshareimageController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = PageShare::latest()->orderBy('name','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $image = "";
                    if($row->image){
                        $url=asset("uploads/pageshare/$row->image");
                        $image = '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                    }
                    return $image;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('all-pages-pages-shares', 'edit')){
                    $btn = '<a href="'.route('webadmin.pageShareEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('all-pages-pages-shares', 'delete')){
                    $btn .= '<a href="'.route('webadmin.pageShareDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.pageshare.index');
    }

    public function add(){
        return view('admin.pageshare.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        if ($image = $request->file('image')){
            $saveData['image'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/pageshare');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['image']);

            $destinationPath = public_path('/uploads/pageshare/original');
            $image->move($destinationPath, $saveData['image']);
        }

        $res = PageShare::create($saveData);
        if ($res){
            toastr()->success('Page share successfully saved.');
            return redirect()->route('webadmin.pageShareIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['page'] = PageShare::where('id',$id)->first();
        return view('admin.pageshare.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
        ]);

        $previousPage = PageShare::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.pageShareIndex');
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $saveData['image'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/pageshare');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['image']);

            $destinationPath = public_path('/uploads/pageshare/original');
            $image->move($destinationPath, $saveData['image']);

            if (file_exists(public_path('uploads/pageshare/'.$previousPage['image']))) {
                unlink(public_path('uploads/pageshare/'.$previousPage['image']));
            }
            if (file_exists(public_path('uploads/pageshare/original/'.$previousPage['image']))) {
                unlink(public_path('uploads/pageshare/original/'.$previousPage['image']));
            }
        }

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Page share successfully updated.');
            return redirect()->route('webadmin.pageShareIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete($id){
        $previousPage = PageShare::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->route('webadmin.pageShareIndex');
        }
        if (file_exists(public_path('uploads/pageshare/'.$previousPage['image']))) {
            unlink(public_path('uploads/pageshare/'.$previousPage['image']));
        }
        if (file_exists(public_path('uploads/pageshare/original/'.$previousPage['image']))) {
            unlink(public_path('uploads/pageshare/original/'.$previousPage['image']));
        }
        $res = $previousPage->delete();
        if ($res){
            toastr()->success('Page share successfully deleted.');
            return redirect()->route('webadmin.pageShareIndex');
        }
        return redirect()->back()->withInput();
    }

}
