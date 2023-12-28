<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FooterSlide;

class FooterSlidesController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = FooterSlide::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if(empty($row->image)){
                        return '';
                    }else{
                        $url=asset("/uploads/footerslide/thumbnail/$row->image");
                        return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                    }
                    
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('footer-slides', 'edit')){
                    $btn = '<a href="'.route('webadmin.footerslideEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('footer-slides', 'delete')){
                    $btn .= '<a href="'.route('webadmin.footerslideDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['title','image','status','action'])
                ->make(true);
        }
        return view('admin.footerslides.index');
    }

    public function add(){
        return view('admin.footerslides.add');
    }

    public function save(Request $request){
        
        $request->validate([
            // 'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $input = $request->all();
        $saveData = [
            // 'title' => $input['title'],
            // 'description' => $input['description'],
            // 'button_label' => $input['button_label'],
            // 'button_link' => $input['button_link'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $saveData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/footerslide/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.'/'.$saveData['image']);
            // $img->resize(233, 182, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$saveData['image']);


            $destinationPath = public_path('/uploads/footerslide');
            $image->move($destinationPath, $saveData['image']);
        }

        $res = FooterSlide::create($saveData);
        if ($res){
            toastr()->success('Banner successfully saved.');
            return redirect()->route('webadmin.footerslides');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['footerslide'] = FooterSlide::where('id',$id)->first();
        return view('admin.footerslides.edit',$data);
    }

    public function update(Request $request,$id){
        // $request->validate([
        //     'title' => 'required'
        // ]);

        $previousStationary = FooterSlide::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.footerslides');
        }

        $input = $request->all();
        $saveData = [
            // 'title' => $input['title'],
            // 'description' => $input['description'],
            // 'button_label' => $input['button_label'],
            // 'button_link' => $input['button_link'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $saveData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/footerslide/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.'/'.$saveData['image']);
            // $img->resize(233, 182, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$saveData['image']);
            $destinationPath = public_path('/uploads/footerslide');
            $image->move($destinationPath, $saveData['image']);
            if(!empty($previousStationary['image'])){

                if (file_exists(public_path('uploads/footerslide/thumbnail/'.$previousStationary['image']))) {
                    unlink(public_path('uploads/footerslide/thumbnail/'.$previousStationary['image']));
                }
                if (file_exists(public_path('uploads/footerslide/'.$previousStationary['image']))) {
                    unlink(public_path('uploads/footerslide/'.$previousStationary['image']));
                }

            }
                

        }

        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Banner successfully updated.');
            return redirect()->route('webadmin.footerslides');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousStationary = FooterSlide::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.footerslides');
        }
        if(!empty($previousStationary['image'])){
            if (file_exists(public_path('uploads/footerslide/thumbnail/'.$previousStationary['image']))) {
                //chmod(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/footerslide/thumbnail/'.$previousStationary['image']));
            }
            if (file_exists(public_path('uploads/footerslide/'.$previousStationary['image']))) {
                //chmod(public_path('uploads/stationary/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/footerslide/'.$previousStationary['image']));
            }
        }
        
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Banner successfully deleted.');
            return redirect()->route('webadmin.footerslides');
        }

        return redirect()->back()->withInput();
    }
    
    public function showDatatable()
    {
        $datas = FooterSlide::orderBy('position','ASC')->get();
        return view('admin.footerslides.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = FooterSlide::all();

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
