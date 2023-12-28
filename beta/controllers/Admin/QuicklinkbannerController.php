<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QuicklinkBanner;
use App\Models\Quicklinkmenu;
use Yajra\DataTables\Facades\DataTables;
use Intervention\Image\Facades\Image;

class QuicklinkbannerController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = QuicklinkBanner::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('banner', function ($row) {
                    if(empty($row->banner)){
                        return '';
                    }else{
                        $url=asset("uploads/sidebanner/$row->banner");
                        return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                    }
                    
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('quicklink_category', function ($row) {
                    if($row->quicklinkmenus_id != 0){
                        $Quicklinkmenu = Quicklinkmenu::where('id',$row->quicklinkmenus_id)->first();
                        $quicklink_category = $Quicklinkmenu->title;
                    }else{
                        $quicklink_category = '';
                    }

                    return $quicklink_category;
                })
                ->addColumn('action', function($row){
                    // right-sidebar-settings-quick-link-banner
                    $btn = '';
                    if(is_permitted('right-sidebar-settings-quick-link-banner', 'edit')){
                    $btn = '<a href="'.route('webadmin.quicklinkbannerEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('right-sidebar-settings-quick-link-banner', 'delete')){
                    $btn .= '<a href="'.route('webadmin.quicklinkbannerDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['banner','quicklink_category','action','status'])
                ->make(true);
        }
        return view('admin.quicklinkbanner.index');
    }

    public function add(){
        $data['quicklinkmenus'] = Quicklinkmenu::get();
        //dd($data);
        return view('admin.quicklinkbanner.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'quicklinkmenu' => 'required',
            'title' => 'required',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'redirect_url' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'quicklinkmenus_id' => $input['quicklinkmenu'],
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => isset($input['status'])?1:0
        ];
        
        if ($image = $request->file('banner')){
            $saveData['banner'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/sidebanner/');
            $image->move($destinationPath, $saveData['banner']);
        }
        $res = QuicklinkBanner::create($saveData);
        if ($res){
            toastr()->success('Quick Link Banner successfully saved.');
            return redirect()->route('webadmin.quicklinkbanner');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['quicklinkmenus'] = Quicklinkmenu::get();
        $data['quicklink'] = QuicklinkBanner::where('id',$id)->first();
        return view('admin.quicklinkbanner.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'quicklinkmenu' => 'required',
            'title' => 'required',
            'redirect_url' => 'required',
        ]);

        $previousPage = QuicklinkBanner::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.quicklinkbanner');
        }

        $input = $request->all();
        //dd($input);
        $saveData = [
            'quicklinkmenus_id' => $input['quicklinkmenu'],
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => isset($input['status'])?1:0
        ];
        if(isset($input['banner'])){
            if ($image = $request->file('banner')){
                
                $saveData['banner'] = time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/sidebanner/');
                $image->move($destinationPath, $saveData['banner']);
            }
        }

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Quick Link Banner successfully updated.');
            return redirect()->route('webadmin.quicklinkbanner');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previous = QuicklinkBanner::where('id',$id)->first();
        if (!$previous){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.quicklinkbanner');
        }

        $res = $previous->delete();
        if ($res){
            toastr()->success('Quick Link Banner successfully deleted.');
            return redirect()->route('webadmin.quicklinkbanner');
        }
        return redirect()->back()->withInput();
    }

}
