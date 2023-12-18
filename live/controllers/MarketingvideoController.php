<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Marketingvideo;
use App\Models\Marketingvideocategory;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Marketingvideotag;
use Illuminate\Support\Facades\Auth;

use App\Models\Quicklinkmenu;
use App\Models\QuicklinkBanner;
use App\Models\Quicklink;

use DB;

class MarketingvideoController extends Controller
{
    public function index(Request $request){
        $tag = $request->tag;
        if($tag){
            $tag_list = Marketingvideotag::select('id')->where('name', 'LIKE', '%'.$tag.'%')->where('is_active',1)->get();
            if(count($tag_list) != 0){
                // dd($tag_list);
                $tag_ids = [];
                foreach ($tag_list as $key => $value) {
                    array_push($tag_ids, $value->id);
                }
                
                $posts = Marketingvideo::where('is_active',1)
                ->where(function ($posts) use ($tag_ids) {
                    foreach($tag_ids as $tag_id){
                        $posts->orWhereRaw("find_in_set($tag_id, tag_ids)");
                    }
                });
                $totalPosts = $posts->count();
                $posts = $posts->orderBy('position','ASC')->paginate(12);
                
                $categories = Marketingvideocategory::where('is_active',1)->get();

                foreach ($categories as $key => $value) {
                    $categories[$key]->marketingvideos = Marketingvideo::where('is_active',1)->where('marketingvideocategory_id',$value->id)->whereIn("tag_ids",$tag_ids)->get();
                }
            }else{
                $totalPosts = 0;
                $posts = Marketingvideo::where('is_active',100)->paginate(12);
                $categories = Marketingvideocategory::where('is_active',1)->get();

                foreach ($categories as $key => $value) {
                    $categories[$key]->marketingvideos = array();
                }
            }
            // dd($posts);
            

        }else{
            $posts = Marketingvideo::where('is_active',1)->orderBy('position','ASC')->paginate(12);
            $totalPosts = Marketingvideo::where('is_active',1)->count();
            $categories = Marketingvideocategory::with('marketingvideos')->where('is_active',1)->get();
            // dd($posts);
        }

        
        
        $slug = '';

        if(Auth::user()){
             $package_id = Auth::user()->package_id;
             $ms_permissions = DB::table("ms_permissions")->where("ms_id","marketing-video")->where("package_id",$package_id)->first();
             if($ms_permissions){
                $permission = [
                   "is_view"=>$ms_permissions->is_view,
                   "is_download"=>$ms_permissions->is_download,
                   "is_save"=>$ms_permissions->is_save
                ];
             }else{
                 $permission = [
                     "is_view"=>1,
                     "is_download"=>1,
                     "is_save"=>1
                 ];
             }
        }else{
             $permission = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_save"=>0
             ];
        }
        
        $user_id = 0;
        if(Auth::user()){
            $user_id = Auth::user()->id;
        }

        UserHistory::create([
            'list_count' => 1,
            'user_id' => $user_id,
            'main_page' => "Marketing Video",
            'page_type' => "",
            'page_name' => "Marketing Video"
        ]);
        
        // dd($posts);
        $isSearchBarShow = true;
        return view('frontend.marketingvideo.index')->with(compact('posts','totalPosts','categories','slug','tag','isSearchBarShow','permission'));
    }

    public function details($slug){
        $post = Marketingvideo::where('slug',$slug)->first();
        $quicklinks = Quicklink::where('quicklinkmenus_id',2)->where('is_active',1)->orderBy('id','desc')->get();
        $quicklinkbanners = QuicklinkBanner::where('quicklinkmenus_id',2)->where('is_active',1)->orderBy('id','desc')->get();
            $user_id = 0;
            if(Auth::user()){
                $user_id = Auth::user()->id;
            }
            UserHistory::create([
                'view_count' => 1,
                'user_id' => $user_id,
                'main_page' => "Marketing Video",
                'page_type' => "",
                'page_name' => "Marketing Video"
            ]);

        if(Auth::user()){
             $package_id = Auth::user()->package_id;
             $ms_permissions = DB::table("ms_permissions")->where("ms_id","marketing-video")->where("package_id",$package_id)->first();
             if($ms_permissions){
                $permission = [
                   "is_view"=>$ms_permissions->is_view,
                   "is_download"=>$ms_permissions->is_download,
                   "is_save"=>$ms_permissions->is_save
                ];
             }else{
                 $permission = [
                     "is_view"=>1,
                     "is_download"=>1,
                     "is_save"=>1
                 ];
             }
        }else{
             $permission = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_save"=>0
             ];
        }
            
        return view('frontend.marketingvideo.details')->with(compact('post','quicklinks','quicklinkbanners','permission'));
    }

    public function category($slug,Request $request){
        $category = Marketingvideocategory::where('slug',$slug)->where('is_active',1)->first();
        if(empty($category)){
            return redirect()->back();
        }

        $tag = $request->tag;
        if($tag){
            $tag_list = Marketingvideotag::select('id')->where('name', 'LIKE', '%'.$tag.'%')->get();
            // dd($tag_list);
            $tag_ids = [];
            foreach ($tag_list as $key => $value) {
                array_push($tag_ids, $value->id);
            }
            $posts = Marketingvideo::where('marketingvideocategory_id',$category->id)->where('is_active',1)->whereIn('tag_ids', $tag_ids)->orderBy('position','ASC')->paginate(12);

            $totalPosts = Marketingvideo::where('is_active',1)->whereIn('tag_ids', $tag_ids)->count();
            $categories = Marketingvideocategory::where('is_active',1)->get();

            foreach ($categories as $key => $value) {
                $categories[$key]->marketingvideos = Marketingvideo::where('is_active',1)->where('marketingvideocategory_id',$value->id)->whereIn("tag_ids",$tag_ids)->get();
            }

        }else{
            $posts = Marketingvideo::where('marketingvideocategory_id',$category->id)->where('is_active',1)->orderBy('position','ASC')->paginate(12);
            $totalPosts = Marketingvideo::where('is_active',1)->count();
            $categories = Marketingvideocategory::with('marketingvideos')->where('is_active',1)->get();
        }
        
        if(Auth::user()){
             $package_id = Auth::user()->package_id;
             $ms_permissions = DB::table("ms_permissions")->where("ms_id","marketing-video")->where("package_id",$package_id)->first();
             if($ms_permissions){
                $permission = [
                   "is_view"=>$ms_permissions->is_view,
                   "is_download"=>$ms_permissions->is_download,
                   "is_save"=>$ms_permissions->is_save
                ];
             }else{
                 $permission = [
                     "is_view"=>1,
                     "is_download"=>1,
                     "is_save"=>1
                 ];
             }
        }else{
             $permission = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_save"=>0
             ];
        }
        $isSearchBarShow = false;
        return view('frontend.marketingvideo.index')->with(compact('posts','totalPosts','categories','slug','tag','permission','isSearchBarShow'));
    }
}
