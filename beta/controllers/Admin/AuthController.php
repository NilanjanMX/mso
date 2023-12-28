<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminAuth;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function login(Request $request){
        $validatedData =  Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validatedData->fails())
        {
            return redirect()->back()->withInput();
        }

        $input = $request->all();
        $loginCheck = AdminAuth::where(
            [
                'email'=> $input['email'],
                'password'=> md5($input['password'])
            ])->first();
        if ($loginCheck){
            session()->put('adminAuth',$loginCheck['id']);
            session()->put('admmin_is_super',$loginCheck['is_super']);
            toastr()->success('You have successfully logged in.');
            return redirect()->route('webadmin.dashboard');
        }else{
            return back()->withInput()->with('error-message','Incorrect login credentials!');
        }

    }

    public function logout(){
        session()->put('adminAuth',null);
        return redirect()->route('webadmin.index')->with('success-message','You have successfully logged out.');
    }

    public function changePassword(){
        // dd("ok");
        return view('admin.change_password');
    }

    public function updatePassword(Request $request){
        $validatedData = $request->validate([
                                    'current_password' => 'required|min:6',
                                    'password' => 'required|min:6|confirmed',
                                ]);

        $input = $request->all();
        $loginCheck = AdminAuth::where(
            [
                'id'=> session()->get('adminAuth'),
                'password'=> md5($input['current_password'])
            ])->first();

        if ($loginCheck){
            $loginCheck->update([
                'password' => md5($input['password'])
            ]);
            toastr()->success('Password successfully changed.');
            return redirect()->route('webadmin.changePassword');
        }else{
            toastr()->warning('Current password does not match!');
            return back()->withInput();
        }
    }
    

    public function set_password($id){
        // dd("ok");
        $data = [];
        $data['id'] = $id;
        return view('admin.set_password',$data);
    }

    public function update_set_password(Request $request){
        $validatedData = $request->validate([
                            'confirm_password' => 'required',
                            'password' => 'required',
                        ]);

        $input = $request->all();
        $loginCheck = AdminAuth::where('remember_token',$input['id'])->first();

        if ($loginCheck){
            $loginCheck->update([
                'password' => md5($input['password']),
                'remember_token' => ''
            ]);
            toastr()->success('Password successfully set.');
            return redirect()->route('webadmin.index');
        }else{
            toastr()->warning('Token expired');
            return back()->withInput();
        }
    }

    public function settings(){
        $data['options'] = Option::orderBy('id','asc')->get();
        return view('admin.settings',$data);
    }

    public function update_settings(Request $request){
        $input = Arr::except($request->all(), ['_token']);
        foreach( $input as $key=>$item){
            if ($key=='site_logo' || $key=='mock_exams_icon' || $key=='ifa_tools_icon'){
                if ($image = $request->file($key)){
                    $logoname = $key.'_'.time().'.'.$image->getClientOriginalExtension();

                    $destinationPath = public_path('/uploads/logo/thumbnail');
                    $img = Image::make($image->getRealPath());
                    $img->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$logoname);


                    $destinationPath = public_path('/uploads/logo');
                    $image->move($destinationPath, $logoname);

                    $exsistData = Option::where('option_name',$key)->first();
                    if ($exsistData){
                        $exsistData->update([
                            'option_value'=> $logoname
                        ]);
                    }
                }
            }else{
                $exsistData = Option::where('option_name',$key)->first();
                if ($exsistData){
                    $exsistData->update([
                        'option_value'=> $input[$key]
                    ]);
                }
            }
        }

        toastr()->success('Settings successfully saved.');
        return redirect()->route('webadmin.settings');
    }
    
    public function forgotpassword(){
        return view('admin.forgot_password');
    }
    
    public function updateforgotpassword(Request $request){

        $validatedData =  Validator::make($request->all(),[
            'email' => 'required|email',
        ]);

        if ($validatedData->fails())
        {
            return redirect()->back()->withInput();
        }

        $input = $request->all();
        // echo "<pre>"; print_r($input['email']); exit;
        $loginCheck = AdminAuth::where('email',$input['email'])->first();
        // 332e0a665b2683bd8ddce94b40ea145c
        $pass = rand(111111,1111111111);
        //dd($pass);
        if ($loginCheck){
            // $loginCheck->update([
            //     'password' => md5($pass)
            // ]);
            // $email = $loginCheck->email;
            //$email = 'subhasishsamanta28@gmail.com';
            // $messageData = ['password'=>$pass];
            // Mail::send('emails.forget-password',$messageData,function($message) use($email){
            //      $message->from('info@masterstrokeonline.com', 'Masterstroke');
            //     $message->to($email)
            //     ->subject('Password Reset with Masterstrokeonline admin');
            // });
            
            toastr()->success('Please check your email');
            return redirect()->route('webadmin.index');
        }else{
            return back()->withInput()->with('error-message','Invalid email id.');
        }
        //dd($pass);
    }
    
}
