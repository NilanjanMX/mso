<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Premiumbanner;
use App\Models\Premiumbannercategory;
use App\Models\Premiumbannertag;
use App\Models\HistoryMarketingBanner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PremiumbannerController extends Controller
{
        
    public function index(Request $request){
        $tag = $request->tag;
        if($tag){
            $tag_ids = [];
            $tag_list = Premiumbannertag::where('name', 'LIKE', "%$tag%")->get();
            foreach ($tag_list as $key => $value) {
                array_push($tag_ids, $value->id);
            }
            $posts = Premiumbanner::where('is_active',1)->whereIn("premiumbannertag_ids",$tag_ids)->orderBy('position','ASC')->paginate(12);
            $totalPosts = Premiumbanner::where('is_active',1)->whereIn("premiumbannertag_ids",$tag_ids)->count();


            $categories = Premiumbannercategory::where('is_active',1)->get();

            foreach ($categories as $key => $value) {
                $categories[$key]->premiumbanners = Premiumbanner::where('is_active',1)->where('premiumbannercategory_ids',$value->id)->whereIn("premiumbannertag_ids",$tag_ids)->get();
            }

        }else{
            $posts = Premiumbanner::where('is_active',1)->orderBy('position','ASC')->paginate(12);
            $totalPosts = Premiumbanner::where('is_active',1)->count();
            $categories = Premiumbannercategory::where('is_active',1)->get();

            foreach ($categories as $key => $value) {
                $categories[$key]->premiumbanners = Premiumbanner::where('is_active',1)->where('premiumbannercategory_ids',$value->id)->get();
            }
        }
        
        $slug = '';
        // nila 
        $isSearchBarShow = true;
        return view('frontend.premiumbanner.index')->with(compact('posts','totalPosts','categories','slug','isSearchBarShow','tag'));
        // nila 
    }
    
    public function index_test(){
        
        $posts = Premiumbanner::where('is_active',1)->orderBy('position','ASC')->paginate(12);
        $totalPosts = Premiumbanner::where('is_active',1)->count();
        $categories = Premiumbannercategory::with('premiumbanners')->where('is_active',1)->get();
        $slug = '';
        return view('frontend.premiumbanner.test.index')->with(compact('posts','totalPosts','categories','slug'));
    }

    public function category($slug,Request $request){
        $category = Premiumbannercategory::where('slug',$slug)->where('is_active',1)->first();
        if(empty($category)){
            return redirect()->back();
        }
        $tag = $request->tag;
        if($tag){
            $tag_ids = [];
            $tag_list = Premiumbannertag::where('name', 'LIKE', "%$tag%")->get();
            foreach ($tag_list as $key => $value) {
                array_push($tag_ids, $value->id);
            }

            $posts = Premiumbanner::where('premiumbannercategory_ids',$category->id)->whereIn("premiumbannertag_ids",$tag_ids)->where('is_active',1)->orderBy('position','ASC')->paginate(12);
            $totalPosts = Premiumbanner::where('is_active',1)->whereIn("premiumbannertag_ids",$tag_ids)->count();
            
            $categories = Premiumbannercategory::where('is_active',1)->get();

            foreach ($categories as $key => $value) {
                $categories[$key]->premiumbanners = Premiumbanner::where('is_active',1)->where('premiumbannercategory_ids',$value->id)->whereIn("premiumbannertag_ids",$tag_ids)->get();
            }
            
        }else{
            $posts = Premiumbanner::where('premiumbannercategory_ids',$category->id)->where('is_active',1)->orderBy('position','ASC')->paginate(12);
            $totalPosts = Premiumbanner::where('is_active',1)->count();
            $categories = Premiumbannercategory::where('is_active',1)->get();
            foreach ($categories as $key => $value) {
                $categories[$key]->premiumbanners = Premiumbanner::where('is_active',1)->where('premiumbannercategory_ids',$value->id)->get();
            }
        }
        $isSearchBarShow = false;
        return view('frontend.premiumbanner.index')->with(compact('posts','totalPosts','categories','slug','isSearchBarShow','tag'));
    }
    public function details($slug){
        
        $post = Premiumbanner::where('is_active',1)->where('slug',$slug)->first();
        return view('frontend.premiumbanner.details')->with(compact('post'));
    }
}
