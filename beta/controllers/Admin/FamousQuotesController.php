<?php

namespace App\Http\Controllers\Admin;

use App\Models\FamousQuotes;
use App\Models\FamousQuoteImages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class FamousQuotesController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = FamousQuotes::orderBy('id','ASC')->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    $url=asset("uploads/famous_quotes/original/$row->logo");
                    return '<img src='.$url.' border="0" width="100" class="img-rounded" align="center" />';
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('famous-quotes-index', 'image')){
                    $btn = '<a href="'.route('webadmin.famousQuotesImage',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Image</a>';
                    }
                    if(is_permitted('famous-quotes-index', 'edit')){
                    $btn .= '<a href="'.route('webadmin.famousQuotesEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    $btn = '';
                    if(is_permitted('famous-quotes-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.famousQuotesDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['logo','action'])
                ->make(true);
        }
        return view('admin.famous_quotes.index');
    }

    public function add(){
        return view('admin.famous_quotes.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('logo')){
            $saveData['logo'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/famous_quotes');
            $img = Image::make($image->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['logo']);

            $destinationPath = public_path('/uploads/famous_quotes/original');
            $image->move($destinationPath, $saveData['logo']);
            // dd($saveData);
            /*$destinationPath = public_path('/uploads/logo');
            $image->move($destinationPath, $saveData['logo']);*/
            
        }
        
        $res = famousQuotes::create($saveData);
        if ($res){
            toastr()->success('Famous Quotes successfully saved.');
            return redirect()->route('webadmin.famousQuotes');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['adminlogo'] = famousQuotes::where('id',$id)->first();
        return view('admin.famous_quotes.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $previousArticle = famousQuotes::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.famousQuotes');
        }

        $input = $request->all();
        
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('logo')){
            $saveData['logo'] = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/famous_quotes');
            $img = Image::make($image->getRealPath());
            $img->resize(1500, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['logo']);
            
            $destinationPath = public_path('/uploads/famous_quotes/original');
            $image->move($destinationPath, $saveData['logo']);
            
            //$destinationPath = public_path('/uploads/logo');
            //$image->move($destinationPath, $saveData['logo']);
           //dd("ok");
            if (file_exists(public_path('uploads/famous_quotes/original/'.$previousArticle['logo']))) {
                unlink(public_path('uploads/famous_quotes/original/'.$previousArticle['logo']));
            }
            

        }

        $res = $previousArticle->update($saveData);
        if ($res){
            toastr()->success('Famous Quotes successfully updated.');
            return redirect()->route('webadmin.famousQuotes');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousArticle = famousQuotes::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.famousQuotes');
        }

        if (file_exists(public_path('uploads/famous_quotes/original/'.$previousArticle['logo']))) {
            unlink(public_path('uploads/famous_quotes/original/'.$previousArticle['logo']));
        }

        if (file_exists(public_path('uploads/famous_quotes/'.$previousArticle['logo']))) {
            unlink(public_path('uploads/famous_quotes/'.$previousArticle['logo']));
        }
        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Famous Quotes successfully deleted.');
            return redirect()->route('webadmin.famousQuotes');
        }

        return redirect()->back()->withInput();
    }
    public function image(Request $request,$famous_quote_id){
    	$data = [];
        $data['list'] = FamousQuoteImages::where('famous_quote_id',$famous_quote_id)->orderBy('id','ASC')->get();
        $data['famous_quote_id'] = $famous_quote_id;
        return view('admin.famous_quotes.image',$data);
    }

    public function imagesave(Request $request){
    	$input = $request->all();
    	// dd($input);
    	if ($image = $request->file('logo')){
            $logo = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/uploads/famous_quotes');
            $img = Image::make($image->getRealPath());
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$logo);

            $destinationPath = public_path('/uploads/famous_quotes/original');
            $image->move($destinationPath, $logo);

            $insertData = [
            	"logo"=>$logo
            ];
            if(!empty($input['id'])){
            	$famousQuoteImages = FamousQuoteImages::where('id',$input['id'])->first();
            	if($famousQuoteImages){
            		if (file_exists(public_path('uploads/famous_quotes/original/'.$famousQuoteImages['logo']))) {
			            unlink(public_path('uploads/famous_quotes/original/'.$famousQuoteImages['logo']));
			        }

			        if (file_exists(public_path('uploads/famous_quotes/'.$famousQuoteImages['logo']))) {
			            unlink(public_path('uploads/famous_quotes/'.$famousQuoteImages['logo']));
			        }
            		$famousQuoteImages->update($insertData);
            	}
	    	}else{
	    		$insertData['famous_quote_id'] = $input['famous_quote_id'];
	    		FamousQuoteImages::create($insertData);
	    	}
        }
        toastr()->success('Famous Quotes Image saved successfully.');
        return redirect()->route('webadmin.famousQuotesImage',$input['famous_quote_id']);
    	
    }

    public function deleteImage(Request $request,$id){
        $previousArticle = FamousQuoteImages::where('id',$id)->first();
        // dd($previousArticle);
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.famousQuotes');
        }

        if (file_exists(public_path('uploads/famous_quotes/original/'.$previousArticle['logo']))) {
            unlink(public_path('uploads/famous_quotes/original/'.$previousArticle['logo']));
        }

        if (file_exists(public_path('uploads/famous_quotes/'.$previousArticle['logo']))) {
            unlink(public_path('uploads/famous_quotes/'.$previousArticle['logo']));
        }
        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Famous Quote Images successfully deleted.');
            return redirect()->route('webadmin.famousQuotesImage',$previousArticle->famous_quote_id);
        }

        return redirect()->back()->withInput();
    }
    

}
