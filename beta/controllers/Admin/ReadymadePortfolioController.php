<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReadymadePortfolio;
use App\Models\ReadymadeData;
use App\Models\ReadymadePortfolioCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ReadymadePortfolioController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = ReadymadePortfolio::with('modes','profiles','portfolios','periods','amounts')->orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('mode', function ($row) {
                    if(!empty($row->mode)){
                        $mode = $row->modes->name;
                    }
                    return $mode;
                })
                ->addColumn('profile', function ($row) {
                    if(!empty($row->profile)){
                        $profile = $row->profiles->name;
                    }
                    return $profile;
                })
                ->addColumn('portfolio', function ($row) {
                    if(!empty($row->portfolio)){
                        $portfolio = $row->portfolios->name;
                    }
                    return $portfolio;
                })
                ->addColumn('period', function ($row) {
                    if(!empty($row->period)){
                        $period = $row->periods->name;
                    }
                    return $period;
                })
                ->addColumn('amount', function ($row) {
                    if(!empty($row->amount)){
                        $amount = $row->amounts->name;
                    }
                    return $amount;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->editColumn('pdf', function ($row) {
                    $pdfs = explode(',', $row->pdf);
                    $img = '';
                    foreach($pdfs as $pdf){
                        if(!empty($pdf)){
                            $img .= '<img src="'.asset('/uploads/readymadeportfolio').'/'.$pdf.'" style="width: 50px;">';
                        }
                    }
                    return $img;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('readymade-portfolio-readymade-portfolio', 'edit')){
                    $btn = '<a href="'.route('webadmin.readymadeEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('readymade-portfolio-readymade-portfolio', 'delete')){
                    $btn .= '<a href="'.route('webadmin.readymadeDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action','pdf','status'])
                ->make(true);
        }
        return view('admin.readymadePortfolio.index');
    }

    public function add(){
        $data['data'] = ReadymadeData::where('is_active',1)->orderBy('name','asc')->get();
        $data['category_list'] = ReadymadePortfolioCategory::where('is_active',1)->orderBy('name','asc')->get();
        $data['schemes'] = DB::table('mf_scanner')->get();
        return view('admin.readymadePortfolio.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'portfolio_no' => 'required',
            'mode' => 'required',
            'profile' => 'required',
            'portfolio' => 'required',
            'period' => 'required',
            'amount' => 'required',
            'sample_pdf1' => 'required',
        ]);
        
        $schemes = '';
        $input = $request->all();
        $category_id = "";
        $category_ids = $request->category_ids;

        foreach ($category_ids as $key => $value) {
            if($category_id){
                $category_id = $category_id.",".$value;
            }else{
                $category_id = $value;
            }
        }
        // if(isset($input['scheme']) && !empty($input['scheme'][0])){
        //     foreach($input['scheme'] as $scheme){
        //         if(empty($schemes)){
        //             $schemes = $scheme;
        //         }else{
        //             $schemes = $schemes.' ,'.$scheme;
        //         }
                
        //     }
        // }
        
        $data = [
            'portfolio_no' => $input['portfolio_no'],
            'mode' => $input['mode'],
            'profile' => $input['profile'],
            'portfolio' => $input['portfolio'],
            'period' => $input['period'],
            'amount' => $input['amount'],
            'category_id' => $category_id,
            'schemes' => $schemes,
            'is_active' => (isset($input['status']))?1:0
        ];
        
        $pdfs = '';

        if ($image = $request->file('sample_pdf1')){
            $file = time().'_1.'.$image->getClientOriginalExtension();
            $pdfs = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf2')){
            $file = time().'_2.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf3')){
            $file = time().'_3.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf4')){
            $file = time().'_4.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf5')){
            $file = time().'_5.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf6')){
            $file = time().'_6.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf7')){
            $file = time().'_7.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf8')){
            $file = time().'_8.'.$image->getClientOriginalExtension();
            $pdfs = $pdfs.','.$file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        $data['pdf'] = $pdfs;
        // dd($data);
        $res = ReadymadePortfolio::create($data);
        if ($res){
            toastr()->success('Readymade Portfolio successfully created.');
            return redirect()->route('webadmin.readymadeIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['readymade'] = ReadymadePortfolio::where('id',$id)->first();
        $data['category_list'] = ReadymadePortfolioCategory::where('is_active',1)->orderBy('name','asc')->get();
        $data['data'] = ReadymadeData::where('is_active',1)->orderBy('name','asc')->get();
        $data['schemes'] = DB::table('mf_scanner')->get();
        // dd($data['readymade']);
        return view('admin.readymadePortfolio.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'portfolio_no' => 'required',
            'mode' => 'required',
            'profile' => 'required',
            'portfolio' => 'required',
            'period' => 'required',
            'amount' => 'required',
            // 'sample_pdf' => 'required',
        ]);

        $input = $request->all();
        
        $previousReadymadePortfolio = ReadymadePortfolio::where('id',$id)->first();
        if (!$previousReadymadePortfolio){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.readymadeIndex');
        }

        $schemes = '';
        $category_id = "";
        $category_ids = $request->category_ids;

        foreach ($category_ids as $key => $value) {
            if($category_id){
                $category_id .= ",".$value;
            }else{
                $category_id = $value;
            }
        }
        
        $data = [
            'portfolio_no' => $input['portfolio_no'],
            'mode' => $input['mode'],
            'profile' => $input['profile'],
            'portfolio' => $input['portfolio'],
            'period' => $input['period'],
            'amount' => $input['amount'],
            'category_id' => $category_id,
            'schemes' => $schemes,
            'is_active' => (isset($input['status']))?1:0
        ];
        // dd($data);
        if(!empty($previousReadymadePortfolio->pdf)){
            $pdfs = explode(',', $previousReadymadePortfolio->pdf);
        }else{
            $pdfs = array();
        }

        if ($image = $request->file('sample_pdf1')){
            $file = time().'1.'.$image->getClientOriginalExtension();
            $pdfs[0] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
            
        }
        if ($image = $request->file('sample_pdf2')){
            $file = time().'2.'.$image->getClientOriginalExtension();
            $pdfs[1] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
            
        }
        if ($image = $request->file('sample_pdf3')){
            $file = time().'3.'.$image->getClientOriginalExtension();
            $pdfs[2] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf4')){
            $file = time().'4.'.$image->getClientOriginalExtension();
            $pdfs[3] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf5')){
            $file = time().'5.'.$image->getClientOriginalExtension();
            $pdfs[4] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf6')){
            $file = time().'6.'.$image->getClientOriginalExtension();
            $pdfs[5] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf7')){
            $file = time().'7.'.$image->getClientOriginalExtension();
            $pdfs[6] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }
        if ($image = $request->file('sample_pdf8')){
            $file = time().'8.'.$image->getClientOriginalExtension();
            $pdfs[7] = $file;
            $destinationPath = public_path('/uploads/readymadeportfolio');
            $image->move($destinationPath, $file);
        }

        $data['pdf'] = implode(',', $pdfs);
        // dd($data);
        $res = $previousReadymadePortfolio->update($data);
        if ($res){
            toastr()->success('Readymade Portfolio successfully updated.');
            return redirect()->route('webadmin.readymadeIndex');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousReadymadePortfolio = ReadymadePortfolio::where('id',$id)->first();
        if (!$previousReadymadePortfolio){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.readymadeIndex');
        }
        $res = $previousReadymadePortfolio->delete();
        if ($res){
            toastr()->success('Readymade Portfolio successfully deleted.');
            return redirect()->route('webadmin.readymadeIndex');
        }

        return redirect()->back()->withInput();
    }

    public function showDatatable(){
        $datas = ReadymadePortfolio::with('portfolios')->orderBy('position','ASC')->get();
        return view('admin.readymadePortfolio.reorder',compact('datas'));
    }

    public function updateOrder(Request $request){
        $datas = ReadymadePortfolio::all();
        // dd($request->order);
        $orders = [];
        foreach ($request->order as $order) {
            $orders[$order['id']]=$order['position'];
        }
        
        // dd($orders);
        foreach ($datas as $data) {
            $data->timestamps = false;
            $id = $data->id;
            if(isset($orders[$id])){
                ReadymadePortfolio::where('id',$id)->update(['position' => $orders[$id]]);
            }
            
        }
        return response('Update Successfully1.', 200);
    }



}
