<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Articlecomment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    
    public function article_comments(Request $request){
        
        if ($request->ajax()) {
            $data = Articlecomment::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $blog = Article::where('id',$row->article_id)->first();
                    //$title = $row->blog->title;
                    $title = $blog->title;
                    return $title;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('articles-comments', 'delete')){
                    $btn = '<a href="'.route('webadmin.articlecommentDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['title','action'])
                ->make(true);
        }
        return view('admin.article.comments');
    }
    
    public function commentDelete($id){
        $comment = Articlecomment::where('id',$id)->first();
        if (!$comment){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.articleComments');
        }
        $res = $comment->delete();
        if ($res){
            toastr()->success('Article comment successfully deleted.');
            return redirect()->route('webadmin.articleComments');
        }
        return redirect()->back()->withInput();
    }
    
    
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Article::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/article/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('articles-articles', 'edit')){
                    $btn = '<a href="'.route('webadmin.articleEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('articles-articles', 'delete')){
                    $btn .= '<a href="'.route('webadmin.articleDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','action'])
                ->make(true);
        }
        return view('admin.article.index');
    }

    public function add(){
        return view('admin.article.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'required',
        ]);

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'author' => $input['author'],
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/article/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/article');
            $image->move($destinationPath, $saveData['cover_image']);
        }

        $res = Article::create($saveData);
        if ($res){
            toastr()->success('Article successfully saved.');
            return redirect()->route('webadmin.articleIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['article'] = Article::where('id',$id)->first();
        return view('admin.article.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'required',
        ]);

        $previousArticle = Article::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.articleIndex');
        }

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'author' => $input['author'],
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/article/thumbnail');
            
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/article');
            //exit;
            $image->move($destinationPath, $saveData['cover_image']);

            if (file_exists(public_path('uploads/article/thumbnail/'.$previousArticle['cover_image']))) {
                //chmod(public_path('uploads/article/thumbnail/'.$previousArticle['cover_image']), 0644);
                unlink(public_path('uploads/article/thumbnail/'.$previousArticle['cover_image']));
            }
            if (file_exists(public_path('uploads/article/'.$previousArticle['cover_image']))) {
                //chmod(public_path('uploads/article/'.$previousArticle['cover_image']), 0644);
                unlink(public_path('uploads/article/'.$previousArticle['cover_image']));
            }

        }
        // echo "<pre>"; print_r($saveData); exit;
        $res = $previousArticle->update($saveData);
        if ($res){
            toastr()->success('Article successfully saved.');
            return redirect()->route('webadmin.articleIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousArticle = Article::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.articleIndex');
        }

        if (file_exists(public_path('uploads/article/thumbnail/'.$previousArticle['cover_image']))) {
            chmod(public_path('uploads/article/thumbnail/'.$previousArticle['cover_image']), 0644);
            unlink(public_path('uploads/article/thumbnail/'.$previousArticle['cover_image']));
        }
        if (file_exists(public_path('uploads/article/'.$previousArticle['cover_image']))) {
            chmod(public_path('uploads/article/'.$previousArticle['cover_image']), 0644);
            unlink(public_path('uploads/article/'.$previousArticle['cover_image']));
        }
        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Article successfully deleted.');
            return redirect()->route('webadmin.articleIndex');
        }

        return redirect()->back()->withInput();
    }
    public function showDatatable()
    {
        $datas = Article::orderBy('position','ASC')->get();
        return view('admin.article.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Article::all();

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
