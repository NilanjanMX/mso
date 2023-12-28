<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Testimonial::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url=asset("uploads/testimonial/thumbnail/$row->image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function($row){
                    // customer-feedback
                    $btn = '';
                    if(is_permitted('customer-feedback', 'edit')){
                    $btn = '<a href="'.route('webadmin.testimonialEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('customer-feedback', 'delete')){
                    $btn .= '<a href="'.route('webadmin.testimonialDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.testimonial.index');
    }

    public function add(){
        return view('admin.testimonial.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'designation' => 'required',
            'comment' => 'required'
        ]);

        $input = $request->all();

        $data = [
            'name' => $input['name'],
            'designation' => $input['designation'],
            'content' => $input['comment'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('image')){
            $data['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/testimonial/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['image']);


            $destinationPath = public_path('/uploads/testimonial');
            $image->move($destinationPath, $data['image']);
        }

        $res = Testimonial::create($data);
        if ($res){
            toastr()->success('Testimonial successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['testimonial'] = Testimonial::where('id',$id)->first();
        return view('admin.testimonial.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'designation' => 'required',
            'comment' => 'required'
        ]);

        $input = $request->all();

        $previousTestimonial = Testimonial::where('id',$id)->first();
        if (!$previousTestimonial){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.testimonialIndex');
        }

        $data = [
            'name' => $input['name'],
            'designation' => $input['designation'],
            'content' => $input['comment'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('image')){
            $data['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/testimonial/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['image']);
            $destinationPath = public_path('/uploads/testimonial');
            $image->move($destinationPath, $data['image']);

            if (file_exists(public_path('uploads/testimonial/thumbnail/'.$previousTestimonial['image']))) {
                chmod(public_path('uploads/testimonial/thumbnail/'.$previousTestimonial['image']), 0644);
                unlink(public_path('uploads/testimonial/thumbnail/'.$previousTestimonial['image']));
            }
            if (file_exists(public_path('uploads/testimonial/'.$previousTestimonial['image']))) {
                chmod(public_path('uploads/testimonial/'.$previousTestimonial['image']), 0644);
                unlink(public_path('uploads/testimonial/'.$previousTestimonial['image']));
            }
        }

        $res = $previousTestimonial->update($data);
        if ($res){
            toastr()->success('Testimonial successfully updated.');
            return redirect()->route('webadmin.testimonialIndex');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousTestimonial = Testimonial::where('id',$id)->first();
        if (!$previousTestimonial){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.testimonialIndex');
        }

        if ($previousTestimonial){
            if (file_exists(public_path('uploads/testimonial/thumbnail/'.$previousTestimonial['image']))) {
                chmod(public_path('uploads/testimonial/thumbnail/'.$previousTestimonial['image']), 0644);
                unlink(public_path('uploads/testimonial/thumbnail/'.$previousTestimonial['image']));
            }
            if (file_exists(public_path('uploads/testimonial/'.$previousTestimonial['image']))) {
                chmod(public_path('uploads/testimonial/'.$previousTestimonial['image']), 0644);
                unlink(public_path('uploads/testimonial/'.$previousTestimonial['image']));
            }
        }

        $res = $previousTestimonial->delete();
        if ($res){
            toastr()->success('Testimonial successfully deleted.');
            return redirect()->route('webadmin.testimonialIndex');
        }

        return redirect()->back()->withInput();
    }
}
