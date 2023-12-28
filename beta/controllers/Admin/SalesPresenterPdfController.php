<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Models\sales_presenter_pdf;
use App\Models\SalespresenterPdfcategory;

class SalesPresenterPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        //$data = sales_presenter_pdf::latest()->get();
        //dd($data);
        if ($request->ajax()) {
            $data = sales_presenter_pdf::with('category')->orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pdf', function ($row) {
                    $url=asset("uploads/sales_presenter_pdf/$row->pdf_file");
                    return '<a href='.$url.' border="0" width="40" class="img-rounded" align="center" target="_blank" />PDF</a>';
                })
                ->addColumn('category', function ($row) {
                    $category = '';
                    if(!empty($row->category->name)){
                        $category = $row->category->name;
                    }

                    return $category;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('pre-made-sales-presenter-sales-presenter-pdf', 'copy')){
                    $btn = '<div id="copy_url_'.$row->id.'" style="font-size:0px;">'.url('sales-presenters/premade-sales-presenter-detail',['id'=> $row->id ]).'</div>';
                    }
                    if(is_permitted('pre-made-sales-presenter-sales-presenter-pdf', 'edit')){
                    $btn .= '<a href="'.route('webadmin.salesPresenterPdfEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('pre-made-sales-presenter-sales-presenter-pdf', 'delete')){
                    $btn .= '<a href="'.route('webadmin.salesPresenterPdfDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    if(is_permitted('pre-made-sales-presenter-sales-presenter-pdf', 'copy')){
                    $btn .= '<a href="javascript:void(0);" onclick="copyFuntion(\'copy_url_'.$row->id.'\')"  class="edit btn btn-success btn-sm ml-1">Copy</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['pdf','category','status','action'])
                ->make(true);
        }
        return view('admin.sales_presenter_pdf.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(){
        //dd("ok");
        $data['categories'] = SalespresenterPdfcategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.sales_presenter_pdf.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'cover_image' => 'required|mimes:jpeg,png,jpg',
            'pdf_file_free' => 'required|mimes:pdf',
            'pdf_file_free_landscape' => 'required|mimes:pdf',
            'pdf_file' => 'required|mimes:pdf',
            'landscape_pdf' => 'required|mimes:pdf',
            'category' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'salespresenterpdfcategories_id' => $input['category'],
            'description' => $input['description'],
            'cover_image' => $input['cover_image'],
            'pdf_file_free' => $input['pdf_file_free'],
            'pdf_file_free_landscape' => $input['pdf_file_free_landscape'],
            'pdf_file' => $input['pdf_file'],
            'landscape_pdf' => $input['landscape_pdf'],
            'is_active' => isset($input['status'])?1:0
        ];

        
        if ($image = $request->file('pdf_file')){
            $file = str_replace(' ', '-', $input['title']).time().'.'.$image->getClientOriginalExtension();
            $saveData['pdf_file'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        if ($image = $request->file('landscape_pdf')){
            $file = str_replace(' ', '-', $input['title']).time().'-land.'.$image->getClientOriginalExtension();
            $saveData['landscape_pdf'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/sales_presenter_pdf/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $saveData['cover_image']);
        }

        if ($image = $request->file('pdf_file_free')){
            $file = str_replace(' ', '-', $input['title']).time().'-free.'.$image->getClientOriginalExtension();
            $saveData['pdf_file_free'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        
        if ($image = $request->file('pdf_file_free_landscape')){
            $file = str_replace(' ', '-', $input['title']).time().'-free-land.'.$image->getClientOriginalExtension();
            $saveData['pdf_file_free_landscape'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        $res = sales_presenter_pdf::create($saveData);
        if ($res){
            toastr()->success('PDF successfully saved.');
            return redirect()->route('webadmin.salesPresenterPdf');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\sales_presenter_pdf  $sales_presenter_pdf
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data['sales_presenter_pdf'] = sales_presenter_pdf::where('id',$id)->first();
        $data['categories'] = SalespresenterPdfcategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.sales_presenter_pdf.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
        ]);

        $previousSamplereport = sales_presenter_pdf::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salesPresenterPdf');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'salespresenterpdfcategories_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){

            if (file_exists(public_path('uploads/sales_presenter_pdf/thumbnail/'.$previousSamplereport['cover_image']))) {
                chmod(public_path('uploads/sales_presenter_pdf/thumbnail/'.$previousSamplereport['cover_image']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/thumbnail/'.$previousSamplereport['cover_image']));
            }
            if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['cover_image']))) {
                chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['cover_image']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['cover_image']));
            }
            
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/sales_presenter_pdf/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $saveData['cover_image']);

            
            
        }

        if ($image = $request->file('pdf_file_free')){

            
            if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free']))) {
                chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free']));
            }

            $file = str_replace(' ', '-', $input['title']).time().'-free.'.$image->getClientOriginalExtension();
            $saveData['pdf_file_free'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        if ($image = $request->file('pdf_file_free_landscape')){

            
            if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free_landscape']))) {
                chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free_landscape']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free_landscape']));
            }

            $file = str_replace(' ', '-', $input['title']).time().'-free-land.'.$image->getClientOriginalExtension();
            $saveData['pdf_file_free_landscape'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        

        if ($image = $request->file('pdf_file')){

            if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file']))) {
                chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file']));
            }

            $file = str_replace(' ', '-', $input['title']).time().'.'.$image->getClientOriginalExtension();
            $saveData['pdf_file'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        if ($image = $request->file('landscape_pdf')){

            if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['landscape_pdf']))) {
                chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['landscape_pdf']), 0777);
                unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['landscape_pdf']));
            }

            $file = str_replace(' ', '-', $input['title']).time().'-land.'.$image->getClientOriginalExtension();
            $saveData['landscape_pdf'] = $file;

            $destinationPath = public_path('/uploads/sales_presenter_pdf');
            $image->move($destinationPath, $file);
            
        }

        $res = $previousSamplereport->update($saveData);
        if ($res){
            toastr()->success('PDF successfully saved.');
            return redirect()->route('webadmin.salesPresenterPdf');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousSamplereport = sales_presenter_pdf::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salesPresenterPdf');
        }

        
        if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file']))) {
            chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file']), 0777);
            unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file']));
        }

        if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free']))) {
            chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free']), 0777);
            unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free']));
        }

        if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free_landscape']))) {
            chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free_landscape']), 0777);
            unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['pdf_file_free_landscape']));
        }

        if (file_exists(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['landscape_pdf']))) {
            chmod(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['landscape_pdf']), 0777);
            unlink(public_path('uploads/sales_presenter_pdf/'.$previousSamplereport['landscape_pdf']));
        }

        if (file_exists(public_path('uploads/sales_presenter_pdf/thumbnail/'.$previousSamplereport['cover_image']))) {
            chmod(public_path('uploads/sales_presenter_pdf/thumbnail/'.$previousSamplereport['cover_image']), 0777);
            unlink(public_path('uploads/sales_presenter_pdf/thumbnail/'.$previousSamplereport['cover_image']));
        }

        $res = $previousSamplereport->delete();
        if ($res){
            toastr()->success('Sample PDF successfully deleted.');
            return redirect()->route('webadmin.salesPresenterPdf');
        }

        return redirect()->back()->withInput();
    }
    
    // Reorder
    
    public function showDatatable()
    {
        $datas = sales_presenter_pdf::orderBy('position','ASC')->get();
        return view('admin.sales_presenter_pdf.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = sales_presenter_pdf::all();
        
        $orders = [];
        foreach ($request->order as $order) {
            $orders[$order['id']] = $order['position'];
        }
        
        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->id;
            // dd($orders[$id]);
            $data->update(['position' => $orders[$id]]);
            
        }
        
        return response('Update Successfully.', 200);
    }
}
