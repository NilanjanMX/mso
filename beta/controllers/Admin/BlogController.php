<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Blogcomment;


class BlogController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Blog::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/blog/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('category', function ($row) {
                    $category = BlogCategory::where('id',$row->blog_category_id)->first();
                    if(isset($category->name) && !empty($category->name)){
                        $category = $category->name;
                    }else{
                        $category = '';
                    }
                    return $category;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('blogs-blogs', 'edit')){
                    $btn = '<a href="'.route('webadmin.blogEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('blogs-blogs', 'delete')){
                    $btn .= '<a href="'.route('webadmin.blogDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','category','action'])
                ->make(true);
        }
        return view('admin.blog.index');
    }

    public function add(){
        $data['categories'] = BlogCategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.blog.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_ids' => 'required',
            'author' => 'required',
        ]);

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        //dd($publish_date);
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'author' => $input['author'],
            'blog_category_id' => $input['category'],
            'blog_category_ids' => $category_ids,
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/blog/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/blog');
            $image->move($destinationPath, $saveData['cover_image']);
        }

        $res = Blog::create($saveData);
        if ($res){
            toastr()->success('Blog successfully saved.');
            return redirect()->route('webadmin.blogIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['blog'] = Blog::where('id',$id)->first();
        $data['categories'] = BlogCategory::where('is_active',1)->orderBy('name','asc')->get();
        //dd($data['blog']->blog_category_ids);
        return view('admin.blog.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_ids' => 'required',
            'author' => 'required',
        ]);

        $previousBlog = Blog::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.blogIndex');
        }

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        //dd($category_ids);
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'author' => $input['author'],
            'blog_category_id' => $input['category'],
            'blog_category_ids' => $category_ids,
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/blog/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/blog');
            $image->move($destinationPath, $saveData['cover_image']);

            if (file_exists(public_path('uploads/blog/thumbnail/'.$previousBlog['cover_image']))) {
                //chmod(public_path('uploads/blog/thumbnail/'.$previousBlog['cover_image']), 0644);
                unlink(public_path('uploads/blog/thumbnail/'.$previousBlog['cover_image']));
            }
            if (file_exists(public_path('uploads/blog/'.$previousBlog['cover_image']))) {
                //chmod(public_path('uploads/blog/'.$previousBlog['cover_image']), 0644);
                unlink(public_path('uploads/blog/'.$previousBlog['cover_image']));
            }

        }

        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Blog successfully saved.');
            return redirect()->route('webadmin.blogIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = Blog::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.blogIndex');
        }

        if (file_exists(public_path('uploads/blog/thumbnail/'.$previousBlog['cover_image']))) {
            chmod(public_path('uploads/blog/thumbnail/'.$previousBlog['cover_image']), 0644);
            unlink(public_path('uploads/blog/thumbnail/'.$previousBlog['cover_image']));
        }
        if (file_exists(public_path('uploads/blog/'.$previousBlog['cover_image']))) {
            chmod(public_path('uploads/blog/'.$previousBlog['cover_image']), 0644);
            unlink(public_path('uploads/blog/'.$previousBlog['cover_image']));
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Blog successfully deleted.');
            return redirect()->route('webadmin.blogIndex');
        }

        return redirect()->back()->withInput();
    }




    // Category

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = BlogCategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('blogs-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.blogcategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('blogs-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.blogcategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.blog.category_index');
    }

    public function category_add(){
        return view('admin.blog.category_add');
    }

    public function category_save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = BlogCategory::create($saveData);
        if ($res){
            toastr()->success('Blog category successfully saved.');
            return redirect()->route('webadmin.blogcategoryIndex');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['blogcategory'] = BlogCategory::where('id',$id)->first();
        return view('admin.blog.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = BlogCategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.blogcategoryIndex');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Blog category successfully updated.');
            return redirect()->route('webadmin.blogcategoryIndex');
        }
        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousBlog = BlogCategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.blogcategoryIndex');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Blog category successfully deleted.');
            return redirect()->route('webadmin.blogcategoryIndex');
        }
        return redirect()->back()->withInput();
    }
    
    /*public function blog_comments(){
        //dd("ok");
        $data = Blogcomment::with('blog')->latest()->get();
        dd($data);
    }*/

    public function blog_comments(Request $request){
        /*$data = Blogcomment::latest()->get();
         echo "<pre>"; print_r($data); echo "</pre>"; die;*/
        if ($request->ajax()) {
            //$data = Blogcomment::with('blog')->latest()->get();
            $data = Blogcomment::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $blog = Blog::where('id',$row->blog_id)->first();
                    //$title = $row->blog->title;
                    $title = $blog->title;
                    return $title;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('blogs-comments', 'delete')){
                    $btn = '<a href="'.route('webadmin.commentDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['title','action'])
                ->make(true);
        }
        return view('admin.blog.comments');
    }
    
    public function commentDelete($id){
        $comment = BlogComment::where('id',$id)->first();
        if (!$comment){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.blogComments');
        }
        $res = $comment->delete();
        if ($res){
            toastr()->success('Blog comment successfully deleted.');
            return redirect()->route('webadmin.blogComments');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Blog::orderBy('position','ASC')->get();
        return view('admin.blog.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Blog::all();

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
