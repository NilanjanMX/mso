<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\HomepageMembership;
use App\Models\HomepageMembershipCards;

class HomepageMembershipController extends Controller
{

    public function homepagemembershipsData(Request $request)
    {
        $datas = HomepageMembership::first();
        if(isset($request->id) && !empty($request->id)){
            // dd($request);
            $data =  [
                'header' => $request->header,
                'description' => $request->description,
                'bottom_text' => $request->bottom_text,
                'button1_label' => $request->button1_label,
                'button1_link' => $request->button1_link,
                'button2_label' => $request->button2_label,
                'button2_link' => $request->button2_link,
            ];
            HomepageMembership::where('id', $request->id)->update($data);

            return redirect()->route('webadmin.homepagemembershipsData')->with('Update Successfully.');
        }elseif(isset($request->header)){
            
            $data =  [
                'header' => $request->header,
                'description' => $request->description,
                'bottom_text' => $request->bottom_text,
                'button1_label' => $request->button1_label,
                'button1_link' => $request->button1_link,
                'button2_label' => $request->button2_label,
                'button2_link' => $request->button2_link,
            ];
            HomepageMembership::create($data);

            return redirect()->route('webadmin.homepagemembershipsData')->with('Update Successfully.');
        }
        // dd($request);
        // dd('here');
        return view('admin.homepagemembership.homepagemembershipsData',compact('datas'));
    }


    public function index(Request $request){
        if ($request->ajax()) {
            $data = HomepageMembershipCards::get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('image', function ($row) {
                //     if(empty($row->image)){
                //         return '';
                //     }else{
                //         $url=asset("/uploads/homepagemembership/thumbnail/$row->image");
                //         return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                //     }
                    
                // })
                ->addColumn('icon', function ($row) {
                    if(empty($row->icon)){
                        return '';
                    }else{
                        $url=asset("/uploads/homepagemembership/$row->icon");
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
                    // home-membership-section
                    $btn = '';
                    if(is_permitted('home-membership-section-cards', 'edit')){
                        $btn = '<a href="'.route('webadmin.homepagemembershipEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('home-membership-section-cards', 'delete')){
                        $btn .= '<a href="'.route('webadmin.homepagemembershipDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    
                    
                    return $btn;
                })
                ->rawColumns(['title','description','icon','status','action'])
                ->make(true);
        }
        // dd(2);
        return view('admin.homepagemembership.index');
    }

    public function add(){
        return view('admin.homepagemembership.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'button_link' => 'required',
        ]);

        
        $input = $request->all();
        // dd($input);
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'button_link' => $input['button_link'],
            'is_active' => isset($input['status'])?1:0
        ];
        if ($image = $request->file('image')){
            $saveData['icon'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/homepagemembership');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.'/'.$saveData['icon']);
            // $img->resize(233, 182, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$saveData['image']);


            // $destinationPath = public_path('/uploads/homepagebanner');
            // $image->move($destinationPath, $saveData['icon']);
        }
        
        $res = HomepageMembershipCards::create($saveData);
        if ($res){
            toastr()->success('successfully saved.');
            return redirect()->route('webadmin.homepagememberships');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['homepagemembership'] = HomepageMembershipCards::where('id',$id)->first();
        return view('admin.homepagemembership.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'button_link' => 'required',
        ]);
        
        $previousStationary = HomepageMembershipCards::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.homepagememberships');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'button_link' => $input['button_link'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $saveData['icon'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/homepagemembership');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.'/'.$saveData['icon']);
            // $img->resize(233, 182, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$saveData['image']);


            // $destinationPath = public_path('/uploads/homepagebanner');
            // $image->move($destinationPath, $saveData['icon']);
        }

        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('successfully updated.');
            return redirect()->route('webadmin.homepagememberships');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousStationary = HomepageMembershipCards::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.homepagememberships');
        }
       
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('successfully deleted.');
            return redirect()->route('webadmin.homepagememberships');
        }

        return redirect()->back()->withInput();
    }
    
    public function showDatatable()
    {
        $datas = HomepageMembershipCards::orderBy('position','ASC')->get();
        return view('admin.homepagemembership.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = HomepageMembershipCards::all();

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
