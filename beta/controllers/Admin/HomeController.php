<?php

namespace App\Http\Controllers\Admin;

use App\Models\HomeBanner;
use App\Models\HomeUsefullink;
use App\Models\HomeWhatsnew;
use App\Models\BusinessAssociate;
use App\Models\MfAdvisorData;
use App\Models\FreeWebinarData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function bannerIndex(Request $request){
        if ($request->ajax()) {
            $data = HomeBanner::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url=asset("uploads/banner/thumbnail/$row->image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('home-header-banner', 'edit')){
                        $btn = '<a href="'.route('webadmin.bannerEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('home-header-banner', 'delete')){
                        $btn .= '<a href="'.route('webadmin.bannerDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.home.banner_index');
    }

    

    public function bannerAdd(){
        return view('admin.home.banner_add');
    }

    public function bannerSave(Request $request){
        $request->validate([
            'title' => 'required',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url' => 'required'
        ]);

        $input = $request->all();

        $data = [
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('banner_image')){
            $data['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/banner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['image']);


            $destinationPath = public_path('/uploads/banner');
            $image->move($destinationPath, $data['image']);
        }

        $res = HomeBanner::create($data);
        if ($res){
            toastr()->success('Banner successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function bannerEdit($id){
        $data['banner'] = HomeBanner::where('id',$id)->first();
        return view('admin.home.banner_edit',$data);
    }

    public function bannerUpdate(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'banner_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url' => 'required'
        ]);

        $input = $request->all();

        $previousBanner = HomeBanner::where('id',$id)->first();
        if (!$previousBanner){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.bannerIndex');
        }

        $data = [
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('banner_image')){
            $data['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/banner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['image']);


            $destinationPath = public_path('/uploads/banner');
            $image->move($destinationPath, $data['image']);
            if (file_exists(public_path('uploads/banner/thumbnail/'.$previousBanner['image']))) {
                //chmod(public_path('uploads/banner/thumbnail/'.$previousBanner['image']), 0644);
                unlink(public_path('uploads/banner/thumbnail/'.$previousBanner['image']));
            }
            if (file_exists(public_path('uploads/banner/'.$previousBanner['image']))) {
                //chmod(public_path('uploads/banner/'.$previousBanner['image']), 0644);
                unlink(public_path('uploads/banner/'.$previousBanner['image']));
            }
        }

        $res = $previousBanner->update($data);
        if ($res){
            toastr()->success('Banner successfully updated.');
            return redirect()->route('webadmin.bannerIndex');
        }
        return redirect()->back()->withInput();
    }

    public function bannerDelete(Request $request,$id){
        $previousBanner = HomeBanner::where('id',$id)->first();
        if (!$previousBanner){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.bannerIndex');
        }

        if ($previousBanner){
            if (file_exists(public_path('uploads/banner/thumbnail/'.$previousBanner['image']))) {
                //chmod(public_path('uploads/banner/thumbnail/'.$previousBanner['image']), 0644);
                unlink(public_path('uploads/banner/thumbnail/'.$previousBanner['image']));
            }
            if (file_exists(public_path('uploads/banner/'.$previousBanner['image']))) {
                //chmod(public_path('uploads/banner/'.$previousBanner['image']), 0644);
                unlink(public_path('uploads/banner/'.$previousBanner['image']));
            }
        }

        $res = $previousBanner->delete();
        if ($res){
            toastr()->success('Banner successfully deleted.');
            return redirect()->route('webadmin.bannerIndex');
        }

        return redirect()->back()->withInput();
    }



    // Useful Links
    public function usefullinkIndex(Request $request){
        if ($request->ajax()) {
            $data = HomeUsefullink::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    $url=asset("uploads/usefullink/thumbnail/$row->icon");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function($row){
                    // home-useful-links
                    $btn = '';
                    if(is_permitted('home-useful-links', 'edit')){
                    $btn = '<a href="'.route('webadmin.usefullinkEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('home-useful-links', 'delete')){
                    $btn .= '<a href="'.route('webadmin.usefullinkDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['icon','action'])
                ->make(true);
        }
        return view('admin.home.usefullink_index');
    }

    public function usefullinkAdd(){
        return view('admin.home.usefullink_add');
    }

    public function usefullinkSave(Request $request){
        $request->validate([
            'title' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'redirect_url' => 'required'
        ]);

        $input = $request->all();

        $data = [
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'description' => $input['description'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('icon')){
            $data['icon'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/usefullink/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['icon']);


            $destinationPath = public_path('/uploads/usefullink');
            $image->move($destinationPath, $data['icon']);
        }

        $res = HomeUsefullink::create($data);
        if ($res){
            toastr()->success('Usefullink successfully created.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function usefullinkEdit($id){
        $data['usefullink'] = HomeUsefullink::where('id',$id)->first();
        return view('admin.home.usefullink_edit',$data);
    }

    public function usefullinkUpdate(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'icon' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'redirect_url' => 'required'
        ]);

        $input = $request->all();

        $previousUsefullink = HomeUsefullink::where('id',$id)->first();
        if (!$previousUsefullink){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.usefullinkIndex');
        }

        $data = [
            'title' => $input['title'],
            'redirect_url' => $input['redirect_url'],
            'description' => $input['description'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('icon')){
            $data['icon'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/usefullink/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['icon']);


            $destinationPath = public_path('/uploads/usefullink');
            $image->move($destinationPath, $data['icon']);

            if (file_exists(public_path('uploads/usefullink/thumbnail/'.$previousUsefullink['icon']))) {
                //chmod(public_path('uploads/usefullink/thumbnail/'.$previousUsefullink['icon']), 0644);
                unlink(public_path('uploads/usefullink/thumbnail/'.$previousUsefullink['icon']));
            }
            if (file_exists(public_path('uploads/usefullink/'.$previousUsefullink['icon']))) {
                //chmod(public_path('uploads/usefullink/'.$previousUsefullink['icon']), 0644);
                unlink(public_path('uploads/usefullink/'.$previousUsefullink['icon']));
            }
        }

        $res = $previousUsefullink->update($data);
        if ($res){
            toastr()->success('Usefullink successfully updated.');
            return redirect()->route('webadmin.usefullinkIndex');
        }
        return redirect()->back()->withInput();
    }

    public function usefullinkDelete(Request $request,$id){
        $previousUsefullink = HomeUsefullink::where('id',$id)->first();
        if (!$previousUsefullink){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.usefullinkIndex');
        }

        if ($previousUsefullink){
            if (file_exists(public_path('uploads/usefullink/thumbnail/'.$previousUsefullink['icon']))) {
                //chmod(public_path('uploads/usefullink/thumbnail/'.$previousUsefullink['icon']), 0644);
                unlink(public_path('uploads/usefullink/thumbnail/'.$previousUsefullink['icon']));
            }
            if (file_exists(public_path('uploads/usefullink/'.$previousUsefullink['icon']))) {
                //chmod(public_path('uploads/usefullink/'.$previousUsefullink['icon']), 0644);
                unlink(public_path('uploads/usefullink/'.$previousUsefullink['icon']));
            }
        }

        $res = $previousUsefullink->delete();
        if ($res){
            toastr()->success('Usefullink successfully deleted.');
            return redirect()->route('webadmin.usefullinkIndex');
        }

        return redirect()->back()->withInput();
    }




    //Whats New

    public function whatsnewIndex(Request $request){
        if ($request->ajax()) {
            $data = HomeWhatsnew::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url=asset("uploads/whatsnew/$row->image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function($row){
                    // home-whats-new
                    $btn = '';
                    if(is_permitted('home-whats-new', 'edit')){
                    $btn = '<a href="'.route('webadmin.whatsnewEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('home-whats-new', 'delete')){
                    $btn .= '<a href="'.route('webadmin.whatsnewDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.home.whatsnew_index');
    }

    public function whatsnewAdd(){
        return view('admin.home.whatsnew_add');
    }

    public function whatsnewSave(Request $request){
        $request->validate([
            'title' => 'required',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'redirect_url' => 'required'
        ]);

        $input = $request->all();

        $data = [
            'title' => $input['title'],
            'description' => $input['description'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('banner_image')){
            $data['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/whatsnew/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['image']);


            $destinationPath = public_path('/uploads/whatsnew');
            $image->move($destinationPath, $data['image']);
        }

        $res = HomeWhatsnew::create($data);
        if ($res){
            toastr()->success("What's new successfully created.");
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function whatsnewEdit($id){
        $data['whatsnew'] = HomeWhatsnew::where('id',$id)->first();
        return view('admin.home.whatsnew_edit',$data);
    }

    public function whatsnewUpdate(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'banner_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'redirect_url' => 'required'
        ]);

        $input = $request->all();

        $previousWhatsnew = HomeWhatsnew::where('id',$id)->first();
        if (!$previousWhatsnew){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.whatsnewIndex');
        }

        $data = [
            'title' => $input['title'],
            'description' => $input['description'],
            'redirect_url' => $input['redirect_url'],
            'is_active' => (isset($input['status']))?1:0
        ];

        if ($image = $request->file('banner_image')){
            $data['image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/whatsnew/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$data['image']);


            $destinationPath = public_path('/uploads/whatsnew');
            $image->move($destinationPath, $data['image']);
            if (file_exists(public_path('uploads/whatsnew/thumbnail/'.$previousWhatsnew['image']))) {
                //chmod(public_path('uploads/whatsnew/thumbnail/'.$previousWhatsnew['image']), 0644);
                unlink(public_path('uploads/whatsnew/thumbnail/'.$previousWhatsnew['image']));
            }
            if (file_exists(public_path('uploads/whatsnew/'.$previousWhatsnew['image']))) {
                //chmod(public_path('uploads/whatsnew/'.$previousWhatsnew['image']), 0644);
                unlink(public_path('uploads/whatsnew/'.$previousWhatsnew['image']));
            }
        }

        $res = $previousWhatsnew->update($data);
        if ($res){
            toastr()->success("What's new successfully updated.");
            return redirect()->route('webadmin.whatsnewIndex');
        }
        return redirect()->back()->withInput();
    }

    public function whatsnewDelete(Request $request,$id){
        $previousWhatsnew = HomeWhatsnew::where('id',$id)->first();
        if (!$previousWhatsnew){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.bannerIndex');
        }

        if ($previousWhatsnew){
            if (file_exists(public_path('uploads/whatsnew/thumbnail/'.$previousWhatsnew['image']))) {
                //chmod(public_path('uploads/whatsnew/thumbnail/'.$previousWhatsnew['image']), 0644);
                unlink(public_path('uploads/whatsnew/thumbnail/'.$previousWhatsnew['image']));
            }
            if (file_exists(public_path('uploads/whatsnew/'.$previousWhatsnew['image']))) {
                //chmod(public_path('uploads/whatsnew/'.$previousWhatsnew['image']), 0644);
                unlink(public_path('uploads/whatsnew/'.$previousWhatsnew['image']));
            }
        }

        $res = $previousWhatsnew->delete();
        if ($res){
            toastr()->success('Whatsnew successfully deleted.');
            return redirect()->route('webadmin.whatsnewIndex');
        }

        return redirect()->back()->withInput();
    }


    public function showDatatable()
    {
        $datas = HomeWhatsnew::orderBy('position','ASC')->get();
        return view('admin.home.whatsnew_reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = HomeWhatsnew::all();

        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->id;
            // return $id;
            foreach ($request->order as $order) {
                // if ($order['id'] == $id) {
                    $st = HomeWhatsnew::where('id', $order['id'])->update(['position' => $order['position']]);
                // }
            }
        }
        
        return response('Update Successfully.', 200);
    }






    //Business Associate

    public function businessAssociate(Request $request){
        if ($request->ajax()) {
            $data = BusinessAssociate::latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('image', function ($row) {
                //     $url=asset("uploads/whatsnew/thumbnail/$row->image");
                //     return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                // })
                ->addColumn('action', function($row){
                    $btn = '';
                    // $btn = '<a href="'.route('webadmin.whatsnewEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    if(is_permitted('mso-associates', 'delete')){
                    $btn = '<a href="'.route('webadmin.businessAssociateDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.home.businesss_associates');
    }

    public function businessAssociateDelete(Request $request,$id){
        $previousUsefullink = BusinessAssociate::where('id',$id)->first();
        if (!$previousUsefullink){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.businessAssociateList');
        }

        $res = $previousUsefullink->delete();
        if ($res){
            toastr()->success('Business Associate successfully deleted.');
            return redirect()->route('webadmin.businessAssociateList');
        }

        return redirect()->back()->withInput();
    }

    
    public function mfadvisorData(Request $request)
    {
        $datas = MfAdvisorData::first();

        if(isset($request->header)){
            // dd($request);
            $data =  [
                'header' => $request->header,
                'agents_label' => $request->agents_label,
                'agents' => $request->agents,
                'years_label' => $request->years_label,
                'years' => $request->years,
                'members_label' => $request->members_label,
                'members' => $request->members,
            ];
            MfAdvisorData::where('id', $request->id)->update($data);

            return redirect()->back()->with('Update Successfully.');
        }
        // dd('here');
        return view('admin.home.mfadvisordata',compact('datas'));
    }

    
    public function freewebinarData(Request $request)
    {
        $datas = FreeWebinarData::first();
        if(isset($request->id) && !empty($request->id)){
            // dd($request);
            $data =  [
                'text' => $request->text,
                'youtube' => $request->youtube,
                'meeting' => $request->meeting,
            ];
            FreeWebinarData::where('id', $request->id)->update($data);

            return redirect()->back()->with('Update Successfully.');
        }elseif(isset($request->text)){
            
            $data =  [
                'text' => $request->text,
                'youtube' => $request->youtube,
                'meeting' => $request->meeting,
            ];
            FreeWebinarData::create($data);

            return redirect()->back()->with('Update Successfully.');
        }
        // dd($request);
        // dd('here');
        return view('admin.home.freewebinardata',compact('datas'));
    }


}
