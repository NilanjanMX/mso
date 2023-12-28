<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Homepagebanner;

class HomepagebannerController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Homepagebanner::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if(empty($row->image)){
                        return '';
                    }else{
                        $url=asset("/uploads/homepagebanner/thumbnail/$row->image");
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
                    if(is_permitted('home-header-banner', 'edit')){
                    $btn = '<a href="'.route('webadmin.homepagebannerEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('home-header-banner', 'delete')){
                    $btn .= '<a href="'.route('webadmin.homepagebannerDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.homepagebanner.index');
    }

    public function add(){
        return view('admin.homepagebanner.add');
    }

    public function save(Request $request){
        
        $request->validate([
            'link_2' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $input = $request->all();
        $saveData = [
            // 'title' => $input['title'],
            // 'link_label_1' => $input['link_label_1'],
            // 'link_1' => $input['link_1'],
            // 'label_1_top' => $input['label_1_top'],
            // 'label_1_bottom' => $input['label_1_bottom'],
            // 'label_1_left' => $input['label_1_left'],
            // 'label_1_right' => $input['label_1_right'],
            // 'is_button_1' => isset($input['is_button_1'])?1:0,
            // 'link_label_2' => $input['link_label_2'],
            'link_2' => $input['link_2'],
            // 'label_2_top' => $input['label_2_top'],
            // 'label_2_bottom' => $input['label_2_bottom'],
            // 'label_2_left' => $input['label_2_left'],
            // 'label_2_right' => $input['label_2_right'],
            // 'is_button_2' => isset($input['is_button_2'])?1:0,
            // 'link_label_3' => $input['link_label_3'],
            // 'link_3' => $input['link_3'],
            // 'label_3_top' => $input['label_3_top'],
            // 'label_3_bottom' => $input['label_3_bottom'],
            // 'label_3_left' => $input['label_3_left'],
            // 'label_3_right' => $input['label_3_right'],
            // 'is_button_3' => isset($input['is_button_3'])?1:0,
            // 'link_label_4' => $input['link_label_4'],
            // 'link_4' => $input['link_4'],
            // 'label_4_top' => $input['label_4_top'],
            // 'label_4_bottom' => $input['label_4_bottom'],
            // 'label_4_left' => $input['label_4_left'],
            // 'label_4_right' => $input['label_4_right'],
            // 'is_button_4' => isset($input['is_button_4'])?1:0,
            // 'link_label_5' => $input['link_label_5'],
            // 'link_5' => $input['link_5'],
            // 'label_5_top' => $input['label_5_top'],
            // 'label_5_bottom' => $input['label_5_bottom'],
            // 'label_5_left' => $input['label_5_left'],
            // 'label_5_right' => $input['label_5_right'],
            // 'is_button_5' => isset($input['is_button_5'])?1:0,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $saveData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/homepagebanner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.'/'.$saveData['image']);
            // $img->resize(233, 182, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$saveData['image']);


            $destinationPath = public_path('/uploads/homepagebanner');
            $image->move($destinationPath, $saveData['image']);
        }

        $res = Homepagebanner::create($saveData);
        if ($res){
            toastr()->success('Banner successfully saved.');
            return redirect()->route('webadmin.homepagebanners');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['homepagebanner'] = Homepagebanner::where('id',$id)->first();
        return view('admin.homepagebanner.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'link_2' => 'required'
        ]);

        $previousStationary = Homepagebanner::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.homepagebanners');
        }

        $input = $request->all();
        $saveData = [
            // 'title' => $input['title'],
            // 'link_label_1' => $input['link_label_1'],
            // 'link_1' => $input['link_1'],
            // 'label_1_top' => $input['label_1_top'],
            // 'label_1_bottom' => $input['label_1_bottom'],
            // 'label_1_left' => $input['label_1_left'],
            // 'label_1_right' => $input['label_1_right'],
            // 'is_button_1' => isset($input['is_button_1'])?1:0,
            // 'link_label_2' => $input['link_label_2'],
            'link_2' => $input['link_2'],
            // 'label_2_top' => $input['label_2_top'],
            // 'label_2_bottom' => $input['label_2_bottom'],
            // 'label_2_left' => $input['label_2_left'],
            // 'label_2_right' => $input['label_2_right'],
            // 'is_button_2' => isset($input['is_button_2'])?1:0,
            // 'link_label_3' => $input['link_label_3'],
            // 'link_3' => $input['link_3'],
            // 'label_3_top' => $input['label_3_top'],
            // 'label_3_bottom' => $input['label_3_bottom'],
            // 'label_3_left' => $input['label_3_left'],
            // 'label_3_right' => $input['label_3_right'],
            // 'is_button_3' => isset($input['is_button_3'])?1:0,
            // 'link_label_4' => $input['link_label_4'],
            // 'link_4' => $input['link_4'],
            // 'label_4_top' => $input['label_4_top'],
            // 'label_4_bottom' => $input['label_4_bottom'],
            // 'label_4_left' => $input['label_4_left'],
            // 'label_4_right' => $input['label_4_right'],
            // 'is_button_4' => isset($input['is_button_4'])?1:0,
            // 'link_label_5' => $input['link_label_5'],
            // 'link_5' => $input['link_5'],
            // 'label_5_top' => $input['label_5_top'],
            // 'label_5_bottom' => $input['label_5_bottom'],
            // 'label_5_left' => $input['label_5_left'],
            // 'label_5_right' => $input['label_5_right'],
            // 'is_button_5' => isset($input['is_button_5'])?1:0,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $saveData['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/homepagebanner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.'/'.$saveData['image']);
            // $img->resize(233, 182, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$saveData['image']);
            $destinationPath = public_path('/uploads/homepagebanner');
            $image->move($destinationPath, $saveData['image']);
            if(!empty($previousStationary['image'])){

                if (file_exists(public_path('uploads/homepagebanner/thumbnail/'.$previousStationary['image']))) {
                    unlink(public_path('uploads/homepagebanner/thumbnail/'.$previousStationary['image']));
                }
                if (file_exists(public_path('uploads/homepagebanner/'.$previousStationary['image']))) {
                    unlink(public_path('uploads/homepagebanner/'.$previousStationary['image']));
                }

            }
                

        }

        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Banner successfully updated.');
            return redirect()->route('webadmin.homepagebanners');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousStationary = Homepagebanner::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.homepagebanners');
        }
        if(!empty($previousStationary['image'])){
            if (file_exists(public_path('uploads/homepagebanner/thumbnail/'.$previousStationary['image']))) {
                //chmod(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/homepagebanner/thumbnail/'.$previousStationary['image']));
            }
            if (file_exists(public_path('uploads/homepagebanner/'.$previousStationary['image']))) {
                //chmod(public_path('uploads/stationary/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/homepagebanner/'.$previousStationary['image']));
            }
        }
        
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Banner successfully deleted.');
            return redirect()->route('webadmin.homepagebanners');
        }

        return redirect()->back()->withInput();
    }
    
    public function showDatatable()
    {
        $datas = Homepagebanner::orderBy('position','ASC')->get();
        return view('admin.homepagebanner.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Homepagebanner::all();

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
