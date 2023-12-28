<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Downloadtool;
use App\Models\Downloadtoolsimage;

class DownloadtoolController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Downloadtool::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/downloadtool/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('downloadfile', function ($row) {
                    if(!empty($row->downloadable_file)){
                        $downloadfile_url=asset("uploads/downloadtool/downloadfile/$row->downloadable_file");
                        return '<a href="'.$downloadfile_url.'" target="_blank" download><p>Download</p></a>';
                    }else{
                        return '';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
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
                    if(is_permitted('ifa-tools-download-tool', 'edit')){
                    $btn = '<a href="'.route('webadmin.downloadtoolEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('ifa-tools-download-tool', 'delete')){
                    $btn .= '<a href="'.route('webadmin.downloadtoolDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','downloadfile','status','action'])
                ->make(true);
        }
        return view('admin.downloadtools.index');
    }

    public function add(){
        return view('admin.downloadtools.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            'downloadable_file' => 'required|max:4000',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/downloadtool/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/downloadtool');
            $image->move($destinationPath, $saveData['cover_image']);
        }

        if ($image = $request->file('downloadable_file')){
            $file = $input['title'].'.'.$image->getClientOriginalExtension();
            $saveData['downloadable_file'] = $file;

            $destinationPath = public_path('/uploads/downloadtool/downloadfile');
            $image->move($destinationPath, $file);
            
        }

        $res = Downloadtool::create($saveData);
        
        $insert_id = $res->id;
        
        if($files=$request->file('upload_images')){
            foreach($files as $file){
                $name=time().'-'.$file->getClientOriginalName();
                $destinationPath = public_path('/uploads/downloadtool/images');
                $file->move($destinationPath, $name);
                $imageData = [
                    'downloadtool_id' => $insert_id,
                    'image' => $name,
                ];
                $res = Downloadtoolsimage::create($imageData);
            }
        }
        
        
        if ($res){
            toastr()->success('Download Tool successfully saved.');
            return redirect()->route('webadmin.downloadtools');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['downloadtool'] = Downloadtool::where('id',$id)->first();
        $data['downloadtoolsimages'] = Downloadtoolsimage::where('downloadtool_id',$id)->get();
        return view('admin.downloadtools.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $previousDownloadtool = Downloadtool::where('id',$id)->first();
        if (!$previousDownloadtool){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.downloadtools');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/downloadtool/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/downloadtool');
            $image->move($destinationPath, $saveData['cover_image']);

            if (file_exists(public_path('uploads/downloadtool/thumbnail/'.$previousDownloadtool['cover_image']))) {
                //chmod(public_path('uploads/downloadtool/thumbnail/'.$previousDownloadtool['cover_image']), 0644);
                unlink(public_path('uploads/downloadtool/thumbnail/'.$previousDownloadtool['cover_image']));
            }
            if (file_exists(public_path('uploads/downloadtool/'.$previousDownloadtool['cover_image']))) {
                //chmod(public_path('uploads/downloadtool/'.$previousDownloadtool['cover_image']), 0644);
                unlink(public_path('uploads/downloadtool/'.$previousDownloadtool['cover_image']));
            }

        }

        if ($image = $request->file('downloadable_file')){
            $file = $input['title'].'.'.$image->getClientOriginalExtension();
            //dd($file);
            $saveData['downloadable_file'] = $file;

            $destinationPath = public_path('/uploads/downloadtool/downloadfile');
            $image->move($destinationPath, $file);

            /*if (file_exists(public_path('uploads/downloadtool/downloadfile/'.$previousDownloadtool['downloadable_file']))) {
                //chmod(public_path('uploads/downloadtool/downloadfile/'.$previousDownloadtool['downloadable_file']), 0644);
                unlink(public_path('uploads/downloadtool/downloadfile/'.$previousDownloadtool['downloadable_file']));
            }*/
        }
        
        if($files=$request->file('upload_images')){
            foreach($files as $file){
                $name=time().'-'.$file->getClientOriginalName();
                $destinationPath = public_path('/uploads/downloadtool/images');
                $file->move($destinationPath, $name);
                $imageData = [
                    'downloadtool_id' => $id,
                    'image' => $name,
                ];
                $res = Downloadtoolsimage::create($imageData);
            }
        }
        
        // dd($saveData);
        $res = $previousDownloadtool->update($saveData);
        
        if ($res){
            toastr()->success('Download Tool successfully saved.');
            return redirect()->route('webadmin.downloadtools');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousDownloadtool = Downloadtool::where('id',$id)->first();
        if (!$previousDownloadtool){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.downloadtools');
        }

        if (file_exists(public_path('uploads/downloadtool/thumbnail/'.$previousDownloadtool['cover_image']))) {
            //chmod(public_path('uploads/downloadtool/thumbnail/'.$previousDownloadtool['cover_image']), 0644);
            unlink(public_path('uploads/downloadtool/thumbnail/'.$previousDownloadtool['cover_image']));
        }
        if (file_exists(public_path('uploads/downloadtool/'.$previousDownloadtool['cover_image']))) {
            //chmod(public_path('uploads/downloadtool/'.$previousDownloadtool['cover_image']), 0644);
            unlink(public_path('uploads/downloadtool/'.$previousDownloadtool['cover_image']));
        }
        if (file_exists(public_path('uploads/downloadtool/downloadfile/'.$previousDownloadtool['downloadable_file']))) {
            //chmod(public_path('uploads/downloadtool/downloadfile/'.$previousDownloadtool['downloadable_file']), 0644);
            unlink(public_path('uploads/downloadtool/downloadfile/'.$previousDownloadtool['downloadable_file']));
        }
        $res = $previousDownloadtool->delete();
        if ($res){
            toastr()->success('Download Tool successfully deleted.');
            return redirect()->route('webadmin.downloadtools');
        }
        return redirect()->back()->withInput();
    }
    
    
    
    public function delete_image(Request $request,$id){
        $previousDownloadtool = Downloadtoolsimage::where('id',$id)->first();
        if (!$previousDownloadtool){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->back();
        }

        if (file_exists(public_path('uploads/downloadtool/images/'.$previousDownloadtool['image']))) {
            unlink(public_path('uploads/downloadtool/images/'.$previousDownloadtool['image']));
        }
        
        $res = $previousDownloadtool->delete();
        if ($res){
            toastr()->success('Download Tool Image successfully deleted.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Downloadtool::orderBy('position','ASC')->get();
        return view('admin.downloadtools.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Downloadtool::all();

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
