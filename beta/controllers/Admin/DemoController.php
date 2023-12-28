<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Demo;
use App\Models\WhatYouLearnDemo;
use Response;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        
        $input = $request->all();
        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';

        $res = Demo::orderBy('id', 'desc');
        // if($data['ustart_date'] && $data['uend_date']){
        //     $res->whereBetween('created_at', [$data['ustart_date'], $data['uend_date']]);
        // }
        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";
            $res->whereBetween('created_at', [$ustart_date , $uend_date]);
        }
        $data['demos'] = $res->get();
        // dd($data['uend_date']);
        $data['current_date'] = date('Y-m-d');

        // if($data['page_type'] == "DOWNLOAD"){
        //     $pdf = PDF::loadView('admin.analytics.inactive_demos', $data);
        //     return $pdf->download('inactive_demos.pdf');
        // }else 
        if($data['page_type'] == "CSV"){
            $filename = "demo_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('slno','name','phone','email','arn_code','city','language','date','time','created_at'));
           
            // $data = ModelsNationalConference::get();
            foreach($data['demos'] as $key=>$row) {
                
                fputcsv($handle, array(
                    $key+1, 
                    $row->name,
                    $row->phone,
                    $row->email,
                    $row->arn_code,
                    $row->city,
                    $row->language,
                    $row->date,
                    $row->time,
                    $row->created_at,
                ));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
            
        }
           
        return view('admin.demo.index', $data);
    }

    
    public function demo_session_details(Request $request)
    {
        $datas = WhatYouLearnDemo::first();
        if(isset($request->id) && !empty($request->id)){
            // $path = $datas->image;
            // if ($image = $request->file('image')){
            //     $path = time().'.'.$image->getClientOriginalExtension();
    
            //     $destinationPath = public_path('/uploads/demo/thumbnail');
            //     $img = Image::make($image->getRealPath());
            //     $img->save($destinationPath.'/'.$path);
            //     $destinationPath = public_path('/uploads/demo');
            //     $image->move($destinationPath, $path);
            // }
            $data =  [
                'text' => $request->text,
                // 'link' => $request->link,
                // 'image' => $path,
            ];
            WhatYouLearnDemo::where('id', $request->id)->update($data);

            return redirect()->back()->with('Update Successfully.');
        }elseif(isset($request->text)){
            // $path = '';
            // if ($image = $request->file('image')){
            //     $path = time().'.'.$image->getClientOriginalExtension();
    
            //     $destinationPath = public_path('/uploads/demo/thumbnail');
            //     $img = Image::make($image->getRealPath());
            //     $img->save($destinationPath.'/'.$path);
            //     $destinationPath = public_path('/uploads/demo');
            //     $image->move($destinationPath, $path);
            // }
            
            $data =  [
                'text' => $request->text,
                // 'link' => $request->link,
                // 'image' => $path,
            ];
            WhatYouLearnDemo::create($data);

            return redirect()->back()->with('Update Successfully.');
        }
        // dd($request);
        // dd('here');
        return view('admin.demo.demo_session_data',compact('datas'));
    }

}
