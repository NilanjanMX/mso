<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Models\ReferralCodeSetting;
use App\Models\Adminlogo;
use App\Models\AdminAuth;
use App\User;

class ReferralCodeController extends Controller
{
    
    public function index(Request $request){
        $input = $request->all();
    	$data = [];
        $data['user_list'] = User::select(['id','name','email'])->orderBy('name','ASC')->get();
    	$data['list'] = ReferralCode::select(['referral_codes.*','users.name','users.email'])->join('users',"users.id","=","referral_codes.user_id");
    	// dd($data);
        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['user_id'] = isset($input['user_id'])?$input['user_id']:'';
        if($data['ustart_date'] && $data['uend_date']){
            $date_from = explode('/', $data['ustart_date']);
            $date_to = explode('/', $data['uend_date']);

            $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
            $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";
            $data['list'] = $data['list']->whereBetween('referral_codes.created_at', [$ustart_date , $uend_date]);
        }

        if($data['user_id']){
            $data['list'] = $data['list']->where('referral_codes.user_id', $data['user_id']);
            $data['total_point'] = ReferralCode::where('is_paid','0')->where('user_id',$data['user_id'])->sum('referral_amount');
            $data['used_point'] = ReferralCode::where('is_paid','1')->where('user_id',$data['user_id'])->sum('referral_amount');
            $data['liability_point'] = $data['total_point'] - $data['used_point'];
        }else{
            $data['total_point'] = ReferralCode::where('is_paid','0')->sum('referral_amount');
            $data['used_point'] = ReferralCode::where('is_paid','1')->sum('referral_amount');
            $data['liability_point'] = $data['total_point'] - $data['used_point'];
        }
        
        $data['list'] = $data['list']->latest()->get();
        return view('admin.referral_code.index',$data);
    }

    public function add(){
        $data['user_list'] = User::select(['id','name','email'])->orderBy('name','ASC')->get();
        $admin_id = session()->get('adminAuth');
        $data['admin_detail'] = AdminAuth::where("id",$admin_id)->first();
        // dd($data['admin_detail']);
        return view('admin.referral_code.add',$data);
    }

    public function save(Request $request){
        $request->validate([
            'user_id' => 'required',
            'referral_amount' => 'required'
        ]);

        $input = $request->all();

        $admin_id = session()->get('adminAuth');

        $total_amount_plus = ReferralCode::where('user_id','=',$input['user_id'])->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
        $total_amount_mins = ReferralCode::where('user_id','=',$input['user_id'])->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
        $total_amount = $total_amount_plus - $total_amount_mins;
        $total_amount = $total_amount + $input['referral_amount'];

         $detail = ReferralCodeSetting::where('id',4)->first();

        $date=strtotime(date('Y-m-d'));
        $expire_at = date('Y-m-d',strtotime('+'.$detail->value.' month',$date));

        $saveData = [
            'user_id' => $input['user_id'],
            'referral_amount' => $input['referral_amount'],
            'note' => $input['note'],
            'total_amount' => $total_amount,
            'is_paid' => 0,
            'is_from_admin' => $admin_id,
            'expire_at' => $expire_at
        ];

        // dd($saveData);
        $start_date = date('Y-m-d');
        $date=strtotime($start_date);
        $start_date = date('d-m-Y',strtotime('+'.$detail->value.' day',$date));
        $message = '"Your account has been credited with '.$input['referral_amount'].' membership points. Validity '.$start_date.'. Masterstrokeonline-MLAPL"';
        $user = User::select(['id','name','email','phone_no'])->where('id','=',$input['user_id'])->first();
        // dd($user);
        $username = "masterstroke";
        $mobile_number = $user->phone_no;
        $password = "Mstroke@2021";
        $sender = "MSTRKE";
        $template_id = "1507162877049171494";
        $endpoint = "http://api.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3')."&template_id=".urlencode($template_id);
        // exit;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();

        $Scheme_details = json_decode($response->getBody());
        // dd($saveData);
        
        $res = ReferralCode::create($saveData);
        if ($res){
            
            toastr()->success('Referral Point successfully saved.');
            return redirect()->route('webadmin.referral_code_list');
        }

        return redirect()->back()->withInput();
    }

    public function delete($id){
        $previousArticle = ReferralCode::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.referral_code_list');
        }

        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Referral Point successfully deleted.');
            return redirect()->route('webadmin.referral_code_list');
        }

        return redirect()->back()->withInput();
    }

    public function setting(Request $request){
        $data = [];
        $data['list'] = ReferralCodeSetting::latest()->get();
        return view('admin.referral_code.setting',$data);
    }

    public function settingedit($id){
        $data = [];
        $data['detail'] = ReferralCodeSetting::where('id',$id)->first();
        return view('admin.referral_code.setting_edit',$data);
    }
    
    public function settingupdate(Request $request){
        $request->validate([
            'value' => 'required'
        ]);

        $input = $request->all();
        $detail = ReferralCodeSetting::where('id',$input['id'])->first();
        $saveData = [
            'value' => $input['value'],
            'type_name' => $input['type_name'],
        ];
        
        $detail->update($saveData);
        if ($detail){
            toastr()->success('Referral Point setting successfully updated.');
            return redirect()->route('webadmin.referral_code_setting');
        }

        return redirect()->back()->withInput();
    }
}
