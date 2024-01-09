<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Cart;
use App\Models\MembershipReferralSetting;
use App\Models\Displayinfo;
use Illuminate\Support\Facades\Mail;

use App\User;
use Auth;
use DB;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MembershipReferralController extends Controller
{

	public function index(){
		$data = [];
		$data['user'] = Auth::user();
		$data['list'] = DB::table("referral_links")->where("user_id",$data['user']->id)->latest()->get();
		$data['left_menu'] = "membershipReferral";
		$data['search_text'] = "";
		return view('frontend.membership_referral.index',$data);
	}

	public function add(){
		$data = [];
		$data['left_menu'] = "membershipReferral";
		return view('frontend.membership_referral.add',$data);
	}

	public function edit($id){
		$data = [];
		$data['user'] = DB::table("referral_links")->where("id",$id)->first();
		$data['left_menu'] = "membershipReferral";
		return view('frontend.membership_referral.edit',$data);
	}
	
	public function save(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email'
        ],[
            'email.unique' => 'The email has already been registered.'
        ]);

        $user = Auth::user();

        $dataExits = DB::table("referral_links")->where([
                'user_id'=> $user->id,
                'email' => $request->email
        ])->first();

        if ($dataExits) {
            return back()->withInput()->withErrors([
                'email' => 'The email has already been taken.',
            ]);
        }

        $link = $user->id.rand(100000,100000000);

        $detail = MembershipReferralSetting::where('id',1)->first();
        $ip_address = getIp();
        $date=strtotime(date('Y-m-d'));
        $expire_at = date('Y-m-d',strtotime('+'.$detail->value.' day',$date));

        $saveData = [
            "name"=>$request->name,
            "email"=>$request->email,
            "phone_number"=>$request->phone_number,
            "user_id"=>$user->id,
            "expire_at"=>$expire_at,
            "ip"=>$ip_address,
            "link"=>$link
        ];
        
        // dd($saveData);

        DB::table("referral_links")->insert($saveData);

        if($request->email){
            $name = $request->name;
            $email = $request->email;
            $link = url('/')."/membership/".$link;

            $messageData = ['email'=>$email,'name'=>$name,'link'=>$link];
            
            Mail::send('emails.membership_referral',$messageData,function($message) use($email){
                $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Membership referral');
            });
        	
            //$this->sendMail($name,$email,$link);
        }

        if($request->phone_number){
        	
        }

        return redirect('account/membership-referral')->with('success','Membership referral created successfully.');
    }


    
    public function sendMail($name,$email,$link){
        require public_path('f/emailtest/vendor/autoload.php');
            
        $mail = new PHPMailer(true);
        try {
            
            //  dd($mail);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = env('SMTPCredentialServer');  
            $mail->SMTPAuth   = true;
            $mail->Username   = env('SMTPCredentialUsername');
            $mail->Password   = env('SMTPCredentialPassword');
            $mail->Port       = env('SMTPCredentialPort');
            
            $mail->setFrom('info@masterstrokeonline.com', 'Master stroke');
            $mail->addAddress($email, $name);
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
              )
            );
            
            $html = '<html>
                <head>
                    <title>User</title>
                </head>
                <body>
                    <p>Dear '.$name.',</p>
                    <table>
                        <tr><td>Membership referral link</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td><a href="'.$link.'">'.$link.'</a></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Thanks & Regards,</td></tr>
                        <tr><td>Team-Masterstroke</td></tr>
                    </table>
                </body>
            </html>';
           
            $mail->isHTML(true);
            $mail->Subject = "Membership referral";
            $mail->Body    = $html;
            $mail->AltBody    = $html;
            $mail->send();
            
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

	public function save123(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);

        $user = Auth::user();

        $link = $user->id.rand(100000,100000000);

        $saveData = [
        	"name"=>$request->name,
        	"email"=>$request->email,
        	"phone_number"=>$request->phone_number,
        	"user_id"=>$user->id,
        	"link"=>$link
        ];

        DB::table("referral_links")->insert($saveData);

        if($request->email){
            $email = $request->email;
            $messageData = [];
            $messageData['name'] = $request->name;
            $messageData['email'] = $request->email;
            $messageData['link'] = url('/')."/membership/".$link;
        	Mail::send('emails.membership_referral',$messageData,function($message) use($email){
	             $message->from('info@masterstrokeonline.com', 'Masterstroke');
	            $message->to($email)->cc('info@masterstrokeonline.com')
	            ->subject('Membership with Masterstrokeonline'); 
	        });
        }

        if($request->phone_number){
        	
        }

        return redirect('account/membership-referral')->with('success','Membership referral created successfully.');
    }

	public function update(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);

        $user = Auth::user();

        $link = $user->id.rand(100000,100000000);
        $ip_address = getIp();

        $saveData = [
        	"name"=>$request->name,
            "ip"=>$ip_address
        ];

        DB::table("referral_links")->where("id",$request->id)->update($saveData);

        return redirect('account/membership-referral')->with('success','Membership referral updated successfully.');
    }

	public function delete($id){

        $user = Auth::user();

        DB::table("referral_links")->where("id",$id)->delete();

        return redirect('account/membership-referral')->with('success','Membership referral deleted successfully.');
    }
}

?>