<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Gallery::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url=asset("uploads/gallery/thumbnail/$row->gallery_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('gallery-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.galleryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('gallery-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.galleryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.gallery.index');
    }

    public function add(){
        return view('admin.gallery.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'gallery_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $input = $request->all();

        $data = [
            'title' => $input['title'],
            'gallery_image' => $input['gallery_image'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('gallery_image')){
            $data['gallery_image'] = time().'.'.$image->getClientOriginalExtension();
            //dd($data['gallery_image']);

            $destinationPath = public_path('/uploads/gallery/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['gallery_image']);


            $destinationPath = public_path('/uploads/gallery');
            $image->move($destinationPath, $data['gallery_image']);
        }

        $res = Gallery::create($data);
        if ($res){
            toastr()->success('Gallery successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['gallery'] = Gallery::where('id',$id)->first();
        return view('admin.gallery.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
        ]);

        $input = $request->all();

        $previousGallery = Gallery::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.gallery');
        }

        $data = [
            'title' => $input['title'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('gallery_image')){
            $data['gallery_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/gallery/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['gallery_image']);


            $destinationPath = public_path('/uploads/gallery');
            $image->move($destinationPath, $data['gallery_image']);
            if (file_exists(public_path('uploads/gallery/thumbnail/'.$previousGallery['gallery_image']))) {
                chmod(public_path('uploads/gallery/thumbnail/'.$previousGallery['gallery_image']), 0644);
                unlink(public_path('uploads/gallery/thumbnail/'.$previousGallery['gallery_image']));
            }
            if (file_exists(public_path('uploads/gallery/'.$previousGallery['gallery_image']))) {
                chmod(public_path('uploads/gallery/'.$previousGallery['gallery_image']), 0644);
                unlink(public_path('uploads/gallery/'.$previousGallery['gallery_image']));
            }
        }

        $res = $previousGallery->update($data);
        if ($res){
            toastr()->success('Gallery successfully updated.');
            return redirect()->route('webadmin.gallery');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousGallery = Gallery::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.gallery');
        }

        if ($previousGallery){
            if (file_exists(public_path('uploads/gallery/thumbnail/'.$previousGallery['gallery_image']))) {
                chmod(public_path('uploads/gallery/thumbnail/'.$previousGallery['gallery_image']), 0644);
                unlink(public_path('uploads/gallery/thumbnail/'.$previousGallery['gallery_image']));
            }
            if (file_exists(public_path('uploads/gallery/'.$previousGallery['gallery_image']))) {
                chmod(public_path('uploads/gallery/'.$previousGallery['gallery_image']), 0644);
                unlink(public_path('uploads/gallery/'.$previousGallery['gallery_image']));
            }
        }

        $res = $previousGallery->delete();
        if ($res){
            toastr()->success('Gallery successfully deleted.');
            return redirect()->route('webadmin.gallery');
        }

        return redirect()->back()->withInput();
    }

}
