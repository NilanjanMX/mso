<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require './vendor/autoload.php';
use FFMpeg;
use Image;
use Response;
use Auth;
use App\User;
use Session;
use App\Models\Membership;
use App\Models\UserHistory;
use App\Models\Displayinfo;
use App\Models\Marketingvideo;
use App\Models\HistorySite;

class VideomakingController extends Controller
{
    public function video_making($slug, $video){
        ini_set('memory_limit', -1);
        //dd($video);
        $marketing_video = Marketingvideo::where('slug',$slug)->first();
        //$increase_download_count = Marketingvideo::where('id',$premium_banner->id)->first();
        $download_count = $marketing_video->downloads;
        
        $download_count++;
        //dd($download_count);
        $saveData = [
            'downloads' => $download_count
        ];
        $res = $marketing_video->update($saveData);
        
        $user = Auth::user();
        $displayInfo = Displayinfo::where('user_id',$user->id)->first();
        $username = $displayInfo->name;
        $company_name = $displayInfo->company_name;
        $phone_no = $displayInfo->phone_no;
        $company_logo = $user->company_logo;
        /*if(!empty($company_logo)){
            $logo = public_path('uploads/logo/original/'.$company_logo);
        }else{
            $logo = public_path('f/images/no-image-logo.jpg');
        }*/
        $time = time();
        $logo = '';
        if(!empty($company_logo)){
            $logo = Image::make(public_path('uploads/logo/original/'.$company_logo));
            //$logo->resize(600, 300);
            $logo_width = $logo->getSize()->width;
            $logo_height = $logo->getSize()->height;
            
            if($logo_width == $logo_height){
                $logo->resize(300, 300);
            }else{
                $logo->resize(300, 150);
            }
            
            
            $logo->save(public_path('images/download/'.$time.'.png'));
            $logo = public_path('images/download/'.$time.'.png');
            
        }else{
            //$logo = public_path('f/images/no-image-logo.jpg');
            $logo = '';
        }

        $video_file_path = public_path('uploads/marketingvideo/video/'.$video);

   
        //$video_file_path = public_path('uploads/videos/'.$video_file);
        //$watermarkPath = public_path('uploads/logo/1580902504.png');
        $watermarkPath2 = $logo;



        $branding = Image::make(public_path('f/images/video-bg.png'))->fit(2490, 1);
        $branding->fill("#FFFFFF")->opacity(40)->fit(2490, 70);
        // $branding = Image::make(public_path('f/images/video-bg.jpg'));
        $branding_info = '';
        if($displayInfo->name_check){
            $branding_info = $username;
        }
        if($displayInfo->company_name_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$company_name;
            }else{
                $branding_info = $company_name;
            }

        }
        if($displayInfo->phone_no_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$phone_no;
            }else{
                $branding_info = $phone_no;
            }

        }
            


        if($branding_info){
            $stringlength = strlen($branding_info);
            //dd($stringlength);
            if($stringlength > 100){
                $first_pos = 600;
                $last_pos = 25;
            }
            elseif($stringlength > 80){
                $first_pos = 700;
                $last_pos = 25;
            }
            elseif($stringlength > 70){
                $first_pos = 875;
                $last_pos = 25;
            }
            elseif($stringlength > 60){
                $first_pos = 900;
                $last_pos = 25;
            }elseif($stringlength > 55){
                $first_pos = 950;
                $last_pos = 25;
            }elseif($stringlength > 50){
                $first_pos = 950;
                $last_pos = 25;
            }elseif($stringlength > 45){
                $first_pos = 1000;
                $last_pos = 25;
            }elseif($stringlength > 40){
                $first_pos = 1050;
                $last_pos = 25;
            }elseif($stringlength > 35){
                $first_pos = 1100;
                $last_pos = 25;
            }elseif($stringlength > 30){
                $first_pos = 1150;
                $last_pos = 25;
            }elseif($stringlength > 25){
                $first_pos = 1200;
                $last_pos = 25;
            }elseif($stringlength > 20){
                $first_pos = 1250;
                $last_pos = 25;
            }elseif($stringlength > 15){
                $first_pos = 1300;
                $last_pos = 25;
            }elseif($stringlength > 9){
                $first_pos = 1400;
                $last_pos = 25;
            }elseif($stringlength > 5){
                $first_pos = 1450;
                $last_pos = 25;
            }else{
                $first_pos = 600;
                $last_pos = 25;
            }
            
            if($displayInfo->amfi_registered == 1){
                $last_pos = 30;
            }else{
                $last_pos = 40;
            }
            //dd($last_pos);
            //$address_color = $displayInfo->address_color;
            $branding->text($branding_info, 1525, $last_pos, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(30);
                $font->align('center');
                //$font->color($address_color);
            });
            if($displayInfo->amfi_registered == 1){
                $branding->text('AMFI-Registered Mutual Fund Distributor', 1525, 62, function ($font) {
                    //$branding->text('AMFI-registered Mutual Fund Distributor', 1300, 55, function ($font) {
                    $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                    $font->size(28);
                    $font->align('center');
                    //$font->color($address_color);
                });
            }

        }
    
        $time = time();
        $branding_filename = 'branding'.$time.'.jpg';
        $branding->save(public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename));
        $filepath = public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename);

        //$watermarkPath = public_path('f/images/video-bg.jpg');
        $watermarkPath = $filepath;
        //dd($watermarkPath);

        try{
            
            $ffmpeg = $ffmpeg = FFMpeg\FFMpeg::create(['timeout'=>3600, 'ffmpeg.thread'=>12]);

            $ffprobe_prep = FFMpeg\FFProbe::create();
            $ffprobe = $ffprobe_prep->format($video_file_path);
            //dd($ffprobe);
            $video = $ffmpeg->open($video_file_path);

            // Get video duration to ensure our videos are never longer than our video limit.
            $duration = $ffprobe->get('duration');

            // Use mp4 format and set the audio bitrate to 56Kbit and Mono channel.
            // TODO: Try stereo later...
            $format = new FFMpeg\Format\Video\X264('libmp3lame','libx264');
            $format
                //-> setKiloBitrate(256)
                -> setKiloBitrate(1000)
                -> setAudioChannels(1)
                //-> setAudioKiloBitrate(8);
                -> setAudioKiloBitrate(256);

            $first = $ffprobe_prep
                        ->streams($video_file_path)
                        ->videos()
                        ->first();

            $width = $first->get('width');

            

            if($width){
                // Resize to 558 x 314 and resize to fit width.
                $video
                    ->filters()
                    ->resize(new FFMpeg\Coordinate\Dimension($width, ceil($width / 16 * 9)));
            }
            // Trim to videos longer than three minutes to 3 minutes.
            if($duration){

                $video
                    ->filters()
                    ->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds($duration));
            }

            // Change the framerate to 16fps and GOP as 9.
            $video
                ->filters()
                ->framerate(new FFMpeg\Coordinate\FrameRate(30), 9);
            $export_file = $slug.'-'.$time.'.mp4';
            //$video_file_new_2 = public_path('uploads/marketingvideo/video/download/export-x264.mp4');
            $video_file_new_2 = public_path('uploads/marketingvideo/video/download/'.$export_file);
            
            // Synchronize audio and video
            if(!empty($watermarkPath2)){
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'top' => 30,
                        'right' => 40,
                    ))
                    ->watermark($watermarkPath2, array(
                        'position' => 'relative',
                        'bottom' => 0,
                        'right' => 0,
                    ))
                    ->synchronize();
                    
                   
                
            }else{
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'bottom' => 30,
                        'right' => 40,
                    ))
                    ->synchronize();
            }
            
             //echo $video_file_new_2; die;
            $video->save($format, $video_file_new_2);
            UserHistory::create([
                'download_count' => 1,
                'user_id' => Auth::user()->id,
                'main_page' => "Marketing Video",
                'page_type' => "",
                'page_name' => "Marketing Video"
            ]);
            
            } catch(Exception $e){}
    
            $export_video = public_path('uploads/marketingvideo/video/download/'.$export_file);
            return response()->download($export_video);
            //dd("ok");


    }
    
    public function getFontWidth($count){
        $return_value = 512;
        
        if($count <= 2){
            $return_value = 500;
        }else if($count <= 4){
            $return_value = 490;
        }else if($count <= 6){
            $return_value = 480;
        }else if($count <= 8){
            $return_value = 470;
        }else if($count <= 10){
            $return_value = 460;
        }else if($count <= 12){
            $return_value = 450;
        }else if($count <= 14){
            $return_value = 440;
        }else if($count <= 16){
            $return_value = 620;
        }else if($count <= 18){
            $return_value = 420;
        }else if($count <= 20){
            $return_value = 410;
        }else if($count <= 22){
            $return_value = 400;
        }else if($count <= 24){
            $return_value = 390;
        }else if($count <= 26){
            $return_value = 600;
        }else if($count <= 28){
            $return_value = 620;
        }else if($count <= 30){
            $return_value = 620;
        }else if($count <= 32){
            $return_value = 620;
        }else if($count <= 34){
            $return_value = 620;
        }else if($count <= 36){
            $return_value = 620;
        }else if($count <= 38){
            $return_value = 620;
        }else if($count <= 40){
            $return_value = 620;
        }else if($count <= 42){
            $return_value = 620;
        }else if($count <= 44){
            $return_value = 290;
        }else if($count <= 46){
            $return_value = 280;
        }else if($count <= 48){
            $return_value = 270;
        }else if($count <= 50){
            $return_value = 260;
        }else if($count <= 52){
            $return_value = 250;
        }else if($count <= 54){
            $return_value = 240;
        }else if($count <= 56){
            $return_value = 230;
        }else if($count <= 58){
            $return_value = 210;
        }else if($count <= 60){
            $return_value = 200;
        }else if($count <= 62){
            $return_value = 190;
        }else if($count <= 64){
            $return_value = 180;
        }else if($count <= 66){
            $return_value = 170;
        }else if($count <= 68){
            $return_value = 160;
        }else if($count <= 70){
            $return_value = 150;
        }else if($count <= 72){
            $return_value = 140;
        }else if($count <= 74){
            $return_value = 130;
        }else if($count <= 76){
            $return_value = 120;
        }else if($count <= 78){
            $return_value = 110;
        }else if($count <= 80){
            $return_value = 100;
        }else if($count <= 82){
            $return_value = 90;
        }else if($count <= 84){
            $return_value = 80;
        }else if($count <= 86){
            $return_value = 70;
        }else if($count <= 88){
            $return_value = 60;
        }else{
            $return_value = 0;
        }

        return $return_value;
    }
    
    public function video_making_new($slug, $video){
        ini_set('memory_limit', -1);
        // dd($video);
        $marketing_video = Marketingvideo::where('slug',$slug)->first();

        $ip_address = getIp();
        HistorySite::create([
            'page_id' => $marketing_video->id,
            'user_id' => Auth::user()->id,
            'page_type' => "Marketing Videos",
            'ip' => $ip_address
        ]);

        //$increase_download_count = Marketingvideo::where('id',$premium_banner->id)->first();
        $download_count = $marketing_video->downloads;
        
        $download_count++;
        $saveData = [
            'downloads' => $download_count
        ];
        $res = $marketing_video->update($saveData);
        
        $user = Auth::user();
        $displayInfo = Displayinfo::where('user_id',$user->id)->first();
        $username = $displayInfo->name;
        $company_name = $displayInfo->company_name;
        $phone_no = $displayInfo->phone_no;
        $company_logo = $user->company_logo;
        // dd($company_logo);
        $time = time();
        $logo = '';
        if(!empty($company_logo)){
            $logo = Image::make(public_path('uploads/logo/original/'.$company_logo));
            //$logo->resize(600, 300);
            $logo_width = $logo->getSize()->width;
            $logo_height = $logo->getSize()->height;
            
            if($logo_width == $logo_height){
                $logo->resize(100, 100);
            }else{
                $logo_diff = $logo_width/$logo_height;
                
                if($logo_diff > 1.75){
                    $rate_width = 20000/$logo_width;
                    $rate_height = (int) ($logo_height*$rate_width/100);
                    $logo->resize(200, $rate_height);
                }else{
                    $rate_width = 14500/$logo_width;
                    $rate_height = (int) ($logo_height*$rate_width/100);
                    // echo $rate_height; exit;
                    $logo->resize(145, $rate_height);
                }
                // $logo->resize(200, 100);
            }
            
            
            $logo->save(public_path('images/download/'.$time.'.png'));
            $logo = public_path('images/download/'.$time.'.png');
            
        }else{
            //$logo = public_path('f/images/no-image-logo.jpg');
            $logo = '';
        }
        
        $video_file_path = public_path('uploads/marketingvideo/video/'.$video);

        //$video_file_path = public_path('uploads/videos/'.$video_file);
        //$watermarkPath = public_path('uploads/logo/1580902504.png');
        $watermarkPath2 = $logo;



        $branding = Image::make(public_path('f/images/video-bg.png'))->fit(1245, 62);
        $branding->fill("#FFFFFF");
        // $branding = Image::make(public_path('f/images/video-bg.jpg'));
        $branding_info = '';
        if($displayInfo->name_check){
            $branding_info = $username;
        }
        if($displayInfo->company_name_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$company_name;
            }else{
                $branding_info = $company_name;
            }

        }
        if($displayInfo->phone_no_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$phone_no;
            }else{
                $branding_info = $phone_no;
            }
        }
        if($branding_info){
            // $branding_info = $branding_info;
            $stringlength = strlen($branding_info);
            // dd($stringlength);
            
            $first_pos = 730;//$this->getFontWidth($stringlength);
            
            
            if($displayInfo->amfi_registered == 1){
                $last_pos = 33;
            }else{
                $last_pos = 43;
            }
            
            $branding->text($branding_info, $first_pos, $last_pos, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(22);
                $font->align('center');
                //$font->color($address_color);
            });
            if($displayInfo->amfi_registered == 1){
                $branding->text('AMFI-Registered Mutual Fund Distributor', 740, 62, function ($font) {
                //$branding->text('AMFI-registered Mutual Fund Distributor1', 1300, 55, function ($font) {
                    $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                    $font->size(20);
                    $font->align('center');
                    //$font->color($address_color);
                });
            }
        }
    
        $time = time();
        $branding_filename = 'branding'.$time.'.jpg';

        $branding->save(public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename));
        $filepath = public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename);

        $watermarkPath = $filepath;
        //  dd($watermarkPath);

        try{
            
            $ffmpeg = FFMpeg\FFMpeg::create(['timeout'=>3600, 'ffmpeg.thread'=>12]);

            $ffprobe_prep = FFMpeg\FFProbe::create();
            $ffprobe = $ffprobe_prep->format($video_file_path);
            // dd($ffprobe);
            $video = $ffmpeg->open($video_file_path);

            // Get video duration to ensure our videos are never longer than our video limit.
            $duration = $ffprobe->get('duration');
            
            // Use mp4 format and set the audio bitrate to 56Kbit and Mono channel.
            // TODO: Try stereo later...
            $format = new FFMpeg\Format\Video\X264('aac','libx264');
            $format
                //-> setKiloBitrate(256)
                -> setKiloBitrate(1000)
                -> setAudioChannels(1)
                //-> setAudioKiloBitrate(8);
                -> setAudioKiloBitrate(256);

            $first = $ffprobe_prep
                        ->streams($video_file_path)
                        ->videos()
                        ->first();

            $width = $first->get('width');

            
            
            if($width){
                // Resize to 558 x 314 and resize to fit width.
                $video
                    ->filters()
                    ->resize(new FFMpeg\Coordinate\Dimension(1024, 1024));
            }
            // Trim to videos longer than three minutes to 3 minutes.
            if($duration){

                $video
                    ->filters()
                    ->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds($duration));
            }

            // Change the framerate to 16fps and GOP as 9.
            $video
                ->filters()
                ->framerate(new FFMpeg\Coordinate\FrameRate(30), 9);
            $export_file = $slug.'-'.$time.'.mp4';
            //$video_file_new_2 = public_path('uploads/marketingvideo/video/download/export-x264.mp4');
            $video_file_new_2 = public_path('uploads/marketingvideo/video/download/'.$export_file);
            
            // Synchronize audio and video
            if(!empty($watermarkPath2)){
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'bottom' => 30,
                        'right' => 0,
                    ))
                    ->watermark($watermarkPath2, array(
                        'position' => 'relative',
                        'top' => 30,
                        'right' => 40,
                    ))
                    ->synchronize();
                    
                   
                
            }else{
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'bottom' => 30,
                        'right' => 40,
                    ))
                    ->synchronize();
            }
            // dd($video);
            $video->save($format, $video_file_new_2);
            
            UserHistory::create([
                'download_count' => 1,
                'user_id' => Auth::user()->id,
                'main_page' => "Marketing Video",
                'page_type' => "",
                'page_name' => "Marketing Video"
            ]);
            
            } catch(Exception $e){}
    
            $export_video = public_path('uploads/marketingvideo/video/download/'.$export_file);
            // dd($export_video);
            return response()->download($export_video);
            //dd("ok");


    }
    
    public function video_making_new_13112023($slug, $video){
        ini_set('memory_limit', -1);
        // dd($video);
        $marketing_video = Marketingvideo::where('slug',$slug)->first();

        $ip_address = getIp();
        HistorySite::create([
            'page_id' => $marketing_video->id,
            'user_id' => Auth::user()->id,
            'page_type' => "Marketing Videos",
            'ip' => $ip_address
        ]);

        //$increase_download_count = Marketingvideo::where('id',$premium_banner->id)->first();
        $download_count = $marketing_video->downloads;
        
        $download_count++;
        $saveData = [
            'downloads' => $download_count
        ];
        $res = $marketing_video->update($saveData);
        
        $user = Auth::user();
        $displayInfo = Displayinfo::where('user_id',$user->id)->first();
        $username = $displayInfo->name;
        $company_name = $displayInfo->company_name;
        $phone_no = $displayInfo->phone_no;
        $company_logo = $user->company_logo;
        /*if(!empty($company_logo)){
            $logo = public_path('uploads/logo/original/'.$company_logo);
        }else{
            $logo = public_path('f/images/no-image-logo.jpg');
        }*/
        $time = time();
        $logo = '';
        if(!empty($company_logo)){
            // dd($company_logo);
            $imagePath = public_path('uploads/logo/original/1582615792.jpg');
            if (file_exists($imagePath)) {
                $logo = Image::make($imagePath);
            } else {
                // Handle the case when the image file doesn't exist.
            }
            // dd($logo);
            //$logo->resize(600, 300);
            $logo_width = $logo->getSize()->width;
            $logo_height = $logo->getSize()->height;
            
            if($logo_width == $logo_height){
                $logo->resize(100, 100);
            }else{
                $logo_diff = $logo_width/$logo_height;
                
                if($logo_diff > 1.75){
                    $rate_width = 20000/$logo_width;
                    $rate_height = (int) ($logo_height*$rate_width/100);
                    $logo->resize(200, $rate_height);
                }else{
                    $rate_width = 14500/$logo_width;
                    $rate_height = (int) ($logo_height*$rate_width/100);
                    // echo $rate_height; exit;
                    $logo->resize(145, $rate_height);
                }
                // $logo->resize(200, 100);
            }
            
            
            $logo->save(public_path('images/download/'.$time.'.png'));
            $logo = public_path('images/download/'.$time.'.png');
            
        }else{
            //$logo = public_path('f/images/no-image-logo.jpg');
            $logo = '';
        }
        
        $video_file_path = public_path('uploads/marketingvideo/video/'.$video);

        //$video_file_path = public_path('uploads/videos/'.$video_file);
        //$watermarkPath = public_path('uploads/logo/1580902504.png');
        $watermarkPath2 = $logo;



        $branding = Image::make(public_path('f/images/video-bg.png'))->fit(1245, 62);
        $branding->fill("#FFFFFF");
        // $branding = Image::make(public_path('f/images/video-bg.jpg'));
        $branding_info = '';
        if($displayInfo->name_check){
            $branding_info = $username;
        }
        if($displayInfo->company_name_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$company_name;
            }else{
                $branding_info = $company_name;
            }

        }
        if($displayInfo->phone_no_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$phone_no;
            }else{
                $branding_info = $phone_no;
            }
        }
        if($branding_info){
            // $branding_info = $branding_info;
            $stringlength = strlen($branding_info);
            // dd($stringlength);
            
            $first_pos = 730;//$this->getFontWidth($stringlength);
            
            
            if($displayInfo->amfi_registered == 1){
                $last_pos = 33;
            }else{
                $last_pos = 43;
            }
            
            $branding->text($branding_info, $first_pos, $last_pos, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(22);
                $font->align('center');
                //$font->color($address_color);
            });
            if($displayInfo->amfi_registered == 1){
                $branding->text('AMFI-Registered Mutual Fund Distributor', 740, 62, function ($font) {
                    //$branding->text('AMFI-registered Mutual Fund Distributor', 1300, 55, function ($font) {
                    $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                    $font->size(20);
                    $font->align('center');
                    //$font->color($address_color);
                });
            }

        }
    
        $time = time();
        $branding_filename = 'branding'.$time.'.jpg';
        // if (!empty($ffmpegCheck)) {
        //     echo 'FFmpeg is installed.';
        // } else {
        //     echo 'FFmpeg is not installed.';
        // }

        // if (!empty($ffprobeCheck)) {
        //     echo 'FFProbe is installed.';
        // } else {
        //     echo 'FFProbe is not installed.';
        // }
        
        

        $branding->save(public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename));
        $filepath = public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename);

        //$watermarkPath = public_path('f/images/video-bg.jpg');
        $watermarkPath = $filepath;
        //  dd($watermarkPath);

        try{
            
            $ffmpeg = $ffmpeg = FFMpeg\FFMpeg::create(['timeout'=>3600, 'ffmpeg.thread'=>12]);

            $ffprobe_prep = FFMpeg\FFProbe::create();
            $ffprobe = $ffprobe_prep->format($video_file_path);
            // dd($ffprobe);
            $video = $ffmpeg->open($video_file_path);

            // Get video duration to ensure our videos are never longer than our video limit.
            $duration = $ffprobe->get('duration');
            
            // Use mp4 format and set the audio bitrate to 56Kbit and Mono channel.
            // TODO: Try stereo later...
            $format = new FFMpeg\Format\Video\X264('libfdk_aac','libx264');
            $format
                //-> setKiloBitrate(256)
                -> setKiloBitrate(1000)
                -> setAudioChannels(1)
                //-> setAudioKiloBitrate(8);
                -> setAudioKiloBitrate(256);

            $first = $ffprobe_prep
                        ->streams($video_file_path)
                        ->videos()
                        ->first();

            $width = $first->get('width');

            
            
            if($width){
                // Resize to 558 x 314 and resize to fit width.
                $video
                    ->filters()
                    ->resize(new FFMpeg\Coordinate\Dimension(1024, 1024));
            }
            // Trim to videos longer than three minutes to 3 minutes.
            if($duration){

                $video
                    ->filters()
                    ->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds($duration));
            }

            // Change the framerate to 16fps and GOP as 9.
            $video
                ->filters()
                ->framerate(new FFMpeg\Coordinate\FrameRate(30), 9);
            $export_file = $slug.'-'.$time.'.mp4';
            //$video_file_new_2 = public_path('uploads/marketingvideo/video/download/export-x264.mp4');
            $video_file_new_2 = public_path('uploads/marketingvideo/video/download/'.$export_file);
            
            // Synchronize audio and video
            if(!empty($watermarkPath2)){
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'top' => 30,
                        'right' => 40,
                    ))
                    ->watermark($watermarkPath2, array(
                        'position' => 'relative',
                        'bottom' => 0,
                        'right' => 0,
                    ))
                    ->synchronize();
                    
                   
                
            }else{
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'bottom' => 30,
                        'right' => 40,
                    ))
                    ->synchronize();
            }
            // dd($video);
            $video->save($format, $video_file_new_2);
            
            UserHistory::create([
                'download_count' => 1,
                'user_id' => Auth::user()->id,
                'main_page' => "Marketing Video",
                'page_type' => "",
                'page_name' => "Marketing Video"
            ]);
            
            } catch(Exception $e){}
    
            $export_video = public_path('uploads/marketingvideo/video/download/'.$export_file);
            dd($export_video);
            return response()->download($export_video);
            //dd("ok");


    }

    //New version

    public function video_making_new1($slug, $video){
        ini_set('memory_limit', -1);
        //dd($video);
        
        $user = Auth::user();
        $displayInfo = Displayinfo::where('user_id',$user->id)->first();
        $username = $displayInfo->name;
        $company_name = $displayInfo->company_name;
        $phone_no = $displayInfo->phone_no;
        $company_logo = $user->company_logo;
        /*if(!empty($company_logo)){
            $logo = public_path('uploads/logo/original/'.$company_logo);
        }else{
            $logo = public_path('f/images/no-image-logo.jpg');
        }*/
        $time = time();
        $logo = '';
        if(!empty($company_logo)){
            $logo = Image::make(public_path('uploads/logo/original/'.$company_logo));
            //$logo->resize(600, 300);
            $logo_width = $logo->getSize()->width;
            $logo_height = $logo->getSize()->height;
            
            if($logo_width == $logo_height){
                $logo->resize(160, 160);
            }else{
                $logo->resize(160, 80);
            }
            
            
            $logo->save(public_path('images/download/'.$time.'.png'));
            $logo = public_path('images/download/'.$time.'.png');
            
        }else{
            //$logo = public_path('f/images/no-image-logo.jpg');
            $logo = '';
        }

        $video_file_path = public_path('uploads/marketingvideo/video/'.$video);

   
        //$video_file_path = public_path('uploads/videos/'.$video_file);
        //$watermarkPath = public_path('uploads/logo/1580902504.png');
        $watermarkPath2 = $logo;



        $branding = Image::make(public_path('f/images/video-bg.jpg'));
        //$addressbar->fill($displayInfo->address_color_background);
        $branding_info = '';
        if($displayInfo->name_check){
            $branding_info = $username;
        }
        if($displayInfo->company_name_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$company_name;
            }else{
                $branding_info = $company_name;
            }

        }
        if($displayInfo->phone_no_check){
            if($branding_info != ''){
                $branding_info .= ' | '.$phone_no;
            }else{
                $branding_info = $phone_no;
            }

        }
            


        if($branding_info){
            $stringlength = strlen($branding_info);
            //dd($stringlength);
            if($stringlength > 100){
                $first_pos = 600;
                $last_pos = 40;
            }
            elseif($stringlength > 80){
                $first_pos = 700;
                $last_pos = 40;
            }
            elseif($stringlength > 70){
                $first_pos = 875;
                $last_pos = 40;
            }
            elseif($stringlength > 60){
                $first_pos = 900;
                $last_pos = 40;
            }elseif($stringlength > 55){
                $first_pos = 950;
                $last_pos = 40;
            }elseif($stringlength > 50){
                $first_pos = 950;
                $last_pos = 40;
            }elseif($stringlength > 45){
                $first_pos = 1000;
                $last_pos = 40;
            }elseif($stringlength > 40){
                $first_pos = 1050;
                $last_pos = 40;
            }elseif($stringlength > 35){
                $first_pos = 1100;
                $last_pos = 40;
            }elseif($stringlength > 30){
                $first_pos = 1150;
                $last_pos = 40;
            }elseif($stringlength > 25){
                $first_pos = 1200;
                $last_pos = 40;
            }elseif($stringlength > 20){
                $first_pos = 1250;
                $last_pos = 40;
            }elseif($stringlength > 15){
                $first_pos = 1300;
                $last_pos = 40;
            }elseif($stringlength > 9){
                $first_pos = 1400;
                $last_pos = 40;
            }elseif($stringlength > 5){
                $first_pos = 1450;
                $last_pos = 40;
            }else{
                $first_pos = 600;
                $last_pos = 40;
            }

            //$address_color = $displayInfo->address_color;
            $branding->text($branding_info, $first_pos, $last_pos, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(35);
                $font->align('center');
                //$font->color($address_color);
            });
            

        }
    
        $time = time();
        $branding_filename = 'branding'.$time.'.jpg';
        $branding->save(public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename));
        $filepath = public_path('uploads/marketingvideo/video/download/branding/'.$branding_filename);

        //$watermarkPath = public_path('f/images/video-bg.jpg');
        $watermarkPath = $filepath;
        
        //dd($watermarkPath);

        try{
            
            $ffmpeg = $ffmpeg = FFMpeg\FFMpeg::create(['timeout'=>3600, 'ffmpeg.thread'=>12]);

            $ffprobe_prep = FFMpeg\FFProbe::create();
            $ffprobe = $ffprobe_prep->format($video_file_path);
            //dd($ffprobe);
            $video = $ffmpeg->open($video_file_path);

            // Get video duration to ensure our videos are never longer than our video limit.
            $duration = $ffprobe->get('duration');

            // Use mp4 format and set the audio bitrate to 56Kbit and Mono channel.
            // TODO: Try stereo later...
            $format = new FFMpeg\Format\Video\X264('libmp3lame','libx264');
            $format
                -> setKiloBitrate(256)
                -> setAudioChannels(1)
                -> setAudioKiloBitrate(8);

            $first = $ffprobe_prep
                        ->streams($video_file_path)
                        ->videos()
                        ->first();

            $width = $first->get('width');

            

            if($width){
                // Resize to 558 x 314 and resize to fit width.
                $video
                    ->filters()
                    ->resize(new FFMpeg\Coordinate\Dimension($width, ceil($width / 16 * 9)));
            }
            // Trim to videos longer than three minutes to 3 minutes.
            if($duration){

                $video
                    ->filters()
                    ->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds($duration));
            }

            // 03-09-2020
            
            

            /*$video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($duration));
            $frame->save($end_video_image);*/

            //End 03-09-2020

            // Change the framerate to 16fps and GOP as 9.
            $video
                ->filters()
                ->framerate(new FFMpeg\Coordinate\FrameRate(16), 9);
            $export_file = $slug.'-'.$time.'.mp4';
            //$video_file_new_2 = public_path('uploads/marketingvideo/video/download/export-x264.mp4');
            $video_file_new_2 = public_path('uploads/marketingvideo/video/download/'.$export_file);
            
            // Synchronize audio and video
            if(!empty($watermarkPath2)){
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'top' => 30,
                        'right' => 40,
                    ))
                    ->watermark($watermarkPath2, array(
                        'position' => 'relative',
                        'bottom' => 0,
                        'right' => 0,
                    ))
                    ->synchronize();
                    
                   
                
            }else{
                $video
                    ->filters()
                    ->watermark($watermarkPath, array(
                        'position' => 'relative',
                        'bottom' => 30,
                        'right' => 40,
                    ))
                    ->synchronize();
            }
            $end_video_image = public_path('uploads/marketingvideo/1591188999.png');
            $video
            ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(42))
            ->save($end_video_image);
             //echo $video_file_new_2; die;
            $video->save($format, $video_file_new_2);
            } catch(Exception $e){}
    
            $export_video = public_path('uploads/marketingvideo/video/download/'.$export_file);
            return response()->download($export_video);
            //dd("ok");

        }

}
