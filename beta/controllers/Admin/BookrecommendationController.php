<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Bookrecommendation;

class BookrecommendationController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Bookrecommendation::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/bookrecommendation/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
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
                    if(is_permitted('ifa-tools-book-recommendation-for-ifas', 'edit')){
                    $btn = '<a href="'.route('webadmin.bookrecommendationEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('ifa-tools-book-recommendation-for-ifas', 'delete')){
                    $btn .= '<a href="'.route('webadmin.bookrecommendationDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','status','action'])
                ->make(true);
        }
        return view('admin.bookrecommendations.index');
    }

    public function add(){
        return view('admin.bookrecommendations.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();
        $saveData = [
            'category' => $input['category'],
            'title' => $input['title'],
            'content' => $input['content'],
            'amazon_link' => $input['amazon_link'],
            'flipkart_link' => $input['flipkart_link'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/bookrecommendation/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/bookrecommendation');
            $image->move($destinationPath, $saveData['cover_image']);
        }

        if ($image = $request->file('download_book_english')){
            $file = $input['title'].'-english.'.$image->getClientOriginalExtension();
            $saveData['download_book_english'] = $file;

            $destinationPath = public_path('/uploads/bookrecommendation/books/english');
            $image->move($destinationPath, $file);
            
        }

        if ($image = $request->file('download_book_hindi')){
            $file = $input['title'].'-hindi.'.$image->getClientOriginalExtension();
            $saveData['download_book_hindi'] = $file;

            $destinationPath = public_path('/uploads/bookrecommendation/books/hindi');
            $image->move($destinationPath, $file);
            
        }

        $res = Bookrecommendation::create($saveData);
        if ($res){
            toastr()->success('Book Recommendation successfully saved.');
            return redirect()->route('webadmin.bookrecommendations');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['bookrecommendation'] = Bookrecommendation::where('id',$id)->first();
        return view('admin.bookrecommendations.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $previousBookrecommendation = Bookrecommendation::where('id',$id)->first();
        if (!$previousBookrecommendation){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.bookrecommendations');
        }

        $input = $request->all();
        $saveData = [
            'category' => $input['category'],
            'title' => $input['title'],
            'content' => $input['content'],
            'amazon_link' => $input['amazon_link'],
            'flipkart_link' => $input['flipkart_link'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/bookrecommendation/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/bookrecommendation');
            $image->move($destinationPath, $saveData['cover_image']);

            /*if (file_exists(public_path('uploads/bookrecommendation/thumbnail/'.$previousBookrecommendation['cover_image']))) {
                chmod(public_path('uploads/bookrecommendation/thumbnail/'.$previousBookrecommendation['cover_image']), 0644);
                unlink(public_path('uploads/bookrecommendation/thumbnail/'.$previousBookrecommendation['cover_image']));
            }
            if (file_exists(public_path('uploads/bookrecommendation/'.$previousBookrecommendation['cover_image']))) {
                chmod(public_path('uploads/bookrecommendation/'.$previousBookrecommendation['cover_image']), 0644);
                unlink(public_path('uploads/bookrecommendation/'.$previousBookrecommendation['cover_image']));
            }*/

        }

        if ($image = $request->file('download_book_english')){
            $file = $input['title'].'-english.'.$image->getClientOriginalExtension();
            $saveData['download_book_english'] = $file;

            $destinationPath = public_path('/uploads/bookrecommendation/books/english');
            $image->move($destinationPath, $file);

            /*if (file_exists(public_path('uploads/bookrecommendation/books/english/'.$previousBookrecommendation['download_book_english']))) {
                chmod(public_path('uploads/bookrecommendation/books/english/'.$previousBookrecommendation['download_book_english']), 0644);
                unlink(public_path('uploads/bookrecommendation/books/english/'.$previousBookrecommendation['download_book_english']));
            }*/
            
        }

        if ($image = $request->file('download_book_hindi')){
            $file = $input['title'].'-hindi.'.$image->getClientOriginalExtension();
            $saveData['download_book_hindi'] = $file;

            $destinationPath = public_path('/uploads/bookrecommendation/books/hindi');
            $image->move($destinationPath, $file);

            /*if (file_exists(public_path('uploads/bookrecommendation/books/hindi/'.$previousBookrecommendation['download_book_hindi']))) {
                chmod(public_path('uploads/bookrecommendation/books/hindi/'.$previousBookrecommendation['download_book_hindi']), 0644);
                unlink(public_path('uploads/bookrecommendation/books/hindi/'.$previousBookrecommendation['download_book_hindi']));
            }*/
            
        }

        $res = $previousBookrecommendation->update($saveData);
        if ($res){
            toastr()->success('Book Recommendation successfully saved.');
            return redirect()->route('webadmin.bookrecommendations');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBookrecommendation = Bookrecommendation::where('id',$id)->first();
        if (!$previousBookrecommendation){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.bookrecommendations');
        }

        /*if (file_exists(public_path('uploads/bookrecommendation/thumbnail/'.$previousBookrecommendation['cover_image']))) {
            chmod(public_path('uploads/bookrecommendation/thumbnail/'.$previousBookrecommendation['cover_image']), 0644);
            unlink(public_path('uploads/bookrecommendation/thumbnail/'.$previousBookrecommendation['cover_image']));
        }
        if (file_exists(public_path('uploads/bookrecommendation/'.$previousBookrecommendation['cover_image']))) {
            chmod(public_path('uploads/bookrecommendation/'.$previousBookrecommendation['cover_image']), 0644);
            unlink(public_path('uploads/bookrecommendation/'.$previousBookrecommendation['cover_image']));
        }
        if (file_exists(public_path('uploads/bookrecommendation/books/english/'.$previousBookrecommendation['download_book_english']))) {
            chmod(public_path('uploads/bookrecommendation/books/english/'.$previousBookrecommendation['download_book_english']), 0644);
            unlink(public_path('uploads/bookrecommendation/books/english/'.$previousBookrecommendation['download_book_english']));
        }
        if (file_exists(public_path('uploads/bookrecommendation/books/hindi/'.$previousBookrecommendation['download_book_hindi']))) {
            chmod(public_path('uploads/bookrecommendation/books/hindi/'.$previousBookrecommendation['download_book_hindi']), 0644);
            unlink(public_path('uploads/bookrecommendation/books/hindi/'.$previousBookrecommendation['download_book_hindi']));
        }*/
        $res = $previousBookrecommendation->delete();
        if ($res){
            toastr()->success('Book Recommendation successfully deleted.');
            return redirect()->route('webadmin.bookrecommendations');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Bookrecommendation::orderBy('position','ASC')->get();
        return view('admin.bookrecommendations.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Bookrecommendation::all();

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
