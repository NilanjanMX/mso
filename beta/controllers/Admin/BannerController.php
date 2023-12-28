<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function banner(){
        $data['banner'] = Banner::findOrFail(1);
        return view('admin.home.banners',$data);
    }
    public function bannerUpdate(Request $request,$id){

        $request->validate([
            'title1' => 'required',
            'subtitle1' => 'required',
            'icon1' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url1' => 'required',
            'title2' => 'required',
            'subtitle2' => 'required',
            'icon2' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url2' => 'required',
            'title3' => 'required',
            'subtitle3' => 'required',
            'icon3' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url3' => 'required',
            'title4' => 'required',
            'subtitle4' => 'required',
            'icon4' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url4' => 'required',
            'title5' => 'required',
            'subtitle5' => 'required',
            'icon5' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'redirect_url5' => 'required'
        ]);

        $input = $request->all();

        $previousBanner = Banner::where('id',$id)->first();
        if (!$previousBanner){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.bannerIndex');
        }

        $data = [
            'title1' => $input['title1'],
            'subtitle1' => $input['subtitle1'],
            'redirect_url1' => $input['redirect_url1'],
            'title2' => $input['title2'],
            'subtitle2' => $input['subtitle2'],
            'redirect_url2' => $input['redirect_url2'],
            'title3' => $input['title3'],
            'subtitle3' => $input['subtitle3'],
            'redirect_url3' => $input['redirect_url3'],
            'title4' => $input['title4'],
            'subtitle4' => $input['subtitle4'],
            'redirect_url4' => $input['redirect_url4'],
            'title5' => $input['title5'],
            'subtitle5' => $input['subtitle5'],
            'redirect_url5' => $input['redirect_url5']
        ];

        for($i = 0; $i<= 5; $i++){
            if ($image = $request->file('icon'.$i)){
                $icon = time().'-'.$i.'.'.$image->getClientOriginalExtension();
                $data['icon'.$i] = $icon;

                $destinationPath = public_path('/uploads/banner');
                $image->move($destinationPath, $icon);
                
                if (file_exists(public_path('uploads/banner/'.$previousBanner['icon'.$i]))) {
                    unlink(public_path('uploads/banner/'.$previousBanner['icon'.$i]));
                }
            }
        }
        //dd($data);
        $res = $previousBanner->update($data);
        if ($res){
            toastr()->success('Banner successfully updated.');
            return redirect()->back();
        }
        return redirect()->back()->withInput();
        
    }
}
