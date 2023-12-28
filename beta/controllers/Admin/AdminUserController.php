<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AdminAuth;
use App\Models\AdminUserRole;
use App\Models\AdminUserAccess;
use App\Models\AdminUserRoleAccess;
use ZipArchive;
use Response;
use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AdminUserController extends Controller
{

    public function index(Request $request){
        
        if ($request->ajax()) {
            $data = AdminAuth::select(['admin_auths.*','admin_user_roles.name as role_name'])->LeftJoin('admin_user_roles', 'admin_user_roles.id', '=', 'admin_auths.role')->where('is_super',0)->orderBy('created_at','DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    $created_at = "";
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
                    $btn = '<a href="'.route('webadmin.admin_userEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.admin_userDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    //$btn .= '<a href="javascript:void(0);" onclick="openModal(\''.$row->id.'\',\''.$row->monthly_points.'\')"  class="edit btn btn-warning btn-sm ml-1">Set</a>';
                    return $btn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('admin.admin_user.index');
    }

    public function add(){
        $data['roles'] = AdminUserRole::where("is_active",1)->get();

        return view('admin.admin_user.add', $data);
    }
    
    public function sendMail($email,$name,$id){
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
            
            $link = url('/')."/webadmin/set-admin-user-password/".$id;
            
            $html = '<html>
                <head>
                    <title>User</title>
                </head>
                <body>
                    <p>Dear '.$name.',</p>
                    <table>
                        <tr><td>Set password link</td></tr>
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
            $mail->Subject = "Set Password";
            $mail->Body    = $html;
            $mail->AltBody    = $html;
            $mail->send();
            
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admin_auths,email',
            'role' => 'required',
        ]);

        $input = $request->all();
        $input['remember_token'] = time().md5($input['email']);
        $saveData = [
            'name' => $input['name'],
            'email' => $input['email'],
            'monthly_points' => $input['monthly_points'],
            'role' => $input['role'],
            'remember_token' => $input['remember_token'],
            'is_active' => isset($input['status'])?1:0
        ];
        
        // dd($saveData);
        
        $res = AdminAuth::create($saveData);
        if ($res){
            
            $link = url('/')."/webadmin/set-admin-user-password/".$input['remember_token'];
            
            $input['link'] = $link;
            $email = $input['email'];
            Mail::send('emails.admin_user_r',$input,function($message) use($email){
                $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Admin User with Masterstrokeonline');
            });
            toastr()->success('Admin User successfully saved.');
            return redirect()->route('webadmin.admin_userIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['adminlogo'] = AdminAuth::where('id',$id)->first();
        $data['roles'] = AdminUserRole::where("is_active",1)->get();
        return view('admin.admin_user.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $previousArticle = AdminAuth::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.admin_userIndex');
        }

        $input = $request->all();
        
        $saveData = [
            'name' => $input['name'],
            'email' => $input['email'],
            'monthly_points' => $input['monthly_points'],
            'role' => $input['role'],
            'is_active' => isset($input['status'])?1:0
        ];
        // dd($saveData);
        $res = $previousArticle->update($saveData);
        if ($res){
            toastr()->success('Admin User successfully updated.');
            return redirect()->route('webadmin.admin_userIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousArticle = AdminAuth::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.admin_userIndex');
        }

        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Admin User successfully deleted.');
            return redirect()->route('webadmin.admin_userIndex');
        }

        return redirect()->back()->withInput();
    }

    public function user_role(Request $request){
        if ($request->ajax()) {
            $data = AdminUserRole::orderBy('created_at','DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    $created_at = "";
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
                    $btn = '<a href="'.route('webadmin.admin_user_roleEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.admin_user_roleDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
        return view('admin.admin_user.user_role_index');
    }

    public function user_role_add(){

        $data = [];
        $data['list'] = AdminUserAccess::where("is_active",1)->where("parent_id",0)->orderBy('order', 'asc')->get();

        foreach ($data['list'] as $key => $value) {
            $data['list'][$key]->list = AdminUserAccess::where("is_active",1)->where("parent_id",$value->id)->get();
        }
        return view('admin.admin_user.user_role_add',$data);
    }

    public function user_role_save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = AdminUserRole::create($saveData);
        
        if ($res){

            foreach($input['permission'] as $val){
                $parentData = [
                    'admin_user_role_id' => $res->id,
                    'admin_user_access_id' => $val,
                    'is_add' => 0,
                    'is_edit' => 0,
                    'is_list' => 0,
                    'is_delete' => 0,
                    'is_reorder' => 0,
                    'parent_id' => 0,
                    'is_active' => 1
                ];
                AdminUserRoleAccess::create($parentData);
            }

            $list = AdminUserAccess::where("is_active",1)->where("parent_id", '!=', 0)->get();
            // dd($input['permission_list'][203]);
            foreach($list as $val){
                $subData = [];
                $subData = [
                    'admin_user_role_id' => $res->id,
                    'admin_user_access_id' => $val->id,
                    'parent_id' => $val->parent_id,
                    'is_add' => isset($input['permission_add'][$val->id])?1:0,
                    'is_edit' => isset($input['permission_edit'][$val->id])?1:0,
                    'is_list' => isset($input['permission_list'][$val->id])?1:0,
                    'is_delete' => isset($input['permission_delete'][$val->id])?1:0,
                    'is_reorder' => isset($input['permission_reorder'][$val->id])?1:0,
                    'is_copy' => isset($input['permission_copy'][$val->id])?1:0,
                    'is_export' => isset($input['permission_export'][$val->id])?1:0,
                    'is_active' => 1
                ];  
                $others = array();
                $oth_val = array();
                if($val->is_other != "0"){
                    
                    $others = explode(',', $val->is_other);
                    $others = array_map(function($value) {
                        $value = trim($value, "\"\n\r\t "); 
                        return $value;
                    }, $others);
                    foreach ($others as $other){
                        $oth_val[] = isset($input['permission_other'][$val->id][$other])?$val->id.'_'.$other:"0";
                    }
                    $subData['is_other'] = implode(",",$oth_val);
                }
                
                AdminUserRoleAccess::create($subData);
            }

            
            

            toastr()->success('Admin User Role successfully saved.');
            return redirect()->route('webadmin.admin_user_roleIndex');
        }

        return redirect()->back()->withInput();
    }

    public function user_role_edit($id){
        $data['adminlogo'] = AdminUserRole::where('id',$id)->first();

        $data['list'] = AdminUserAccess::where("is_active",1)->where("parent_id",0)->orderBy('order', 'asc')->get();

        $list = AdminUserRoleAccess::where("admin_user_role_id", $id)->where("parent_id", 0)->get()->pluck('admin_user_access_id')->toArray();

        $sub_menu = AdminUserRoleAccess::where("admin_user_role_id", $id)->where("parent_id", '!=', 0)->get();
        $data['sub_is_list'] = $sub_menu->where('is_list', 1)->pluck('admin_user_access_id')->toArray();
        $data['sub_is_add'] = $sub_menu->where('is_add', 1)->pluck('admin_user_access_id')->toArray();
        $data['sub_is_edit'] = $sub_menu->where('is_edit', 1)->pluck('admin_user_access_id')->toArray();
        $data['sub_is_delete'] = $sub_menu->where('is_delete', 1)->pluck('admin_user_access_id')->toArray();
        $data['sub_is_reorder'] = $sub_menu->where('is_reorder', 1)->pluck('admin_user_access_id')->toArray();
        $data['sub_is_copy'] = $sub_menu->where('is_copy', 1)->pluck('admin_user_access_id')->toArray();
        $data['sub_is_export'] = $sub_menu->where('is_export', 1)->pluck('admin_user_access_id')->toArray();
        $sub_is_other = $sub_menu->where('is_other', '!=', "0")->pluck('is_other')->toArray();
        // dd($sub_is_other);
        $data['sub_is_other'] = array();
        if(count($sub_is_other) !=0){
            $resultArray = array();
            foreach ($sub_is_other as $value) {
                if ($value !== "0,0") {
                    $explodedValues = explode(",", $value);
                    foreach ($explodedValues as $explodedValue) {
                        $val = trim($explodedValue);
                        if($val != 0 || $val != "0"){
                            $resultArray[] = $val;
                        }
                    }
                }
            }
            $data['sub_is_other'] = $resultArray;
        }
        // dd($data['sub_is_other']);
        foreach ($data['list'] as $key => $value) {
            if(in_array($value->id, $list)){
                $data['list'][$key]->is_checked = 1;
            }else{
                $data['list'][$key]->is_checked = 0;
            }
            
            $data['list'][$key]->list = AdminUserAccess::where("is_active",1)->where("parent_id",$value->id)->get();
            
        }

        return view('admin.admin_user.user_role_edit',$data);
    }

    public function user_role_update(Request $request,$id){
        $request->validate([
            'name' => 'required',
        ]);

        $previousArticle = AdminUserRole::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.admin_user_roleIndex');
        }

        $input = $request->all();
        
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousArticle->update($saveData);
        if ($id){
            AdminUserRoleAccess::where('admin_user_role_id', $id)->delete();
            foreach($input['permission'] as $val){
                $parentData = [
                    'admin_user_role_id' => $id,
                    'admin_user_access_id' => $val,
                    'is_add' => 0,
                    'is_edit' => 0,
                    'is_list' => 0,
                    'is_delete' => 0,
                    'is_reorder' => 0,
                    'parent_id' => 0,
                    'is_active' => 1
                ];
                AdminUserRoleAccess::create($parentData);
            }

            $list = AdminUserAccess::where("is_active",1)->where("parent_id", '!=', 0)->get();
            
            foreach($list as $val){
                $subData = [];
                $subData = [
                    'admin_user_role_id' => $id,
                    'admin_user_access_id' => $val->id,
                    'parent_id' => $val->parent_id,
                    'is_add' => isset($input['permission_add'][$val->id])?1:0,
                    'is_edit' => isset($input['permission_edit'][$val->id])?1:0,
                    'is_list' => isset($input['permission_list'][$val->id])?1:0,
                    'is_delete' => isset($input['permission_delete'][$val->id])?1:0,
                    'is_reorder' => isset($input['permission_reorder'][$val->id])?1:0,
                    'is_copy' => isset($input['permission_copy'][$val->id])?1:0,
                    'is_export' => isset($input['permission_export'][$val->id])?1:0,
                    'is_active' => 1
                ];  
                $others = array();
                $oth_val = array();
                if($val->is_other != "0"){
                    
                    $others = explode(',', $val->is_other);
                    $others = array_map(function($value) {
                        $value = trim($value, "\"\n\r\t "); 
                        return $value;
                    }, $others);
                    foreach ($others as $other){
                        $oth_val[] = isset($input['permission_other'][$val->id][$other])?$val->id.'_'.$other:"0";
                    }
                    $subData['is_other'] = implode(",",$oth_val);
                }
                
                
                AdminUserRoleAccess::create($subData);
            }

            toastr()->success('Admin User Role successfully updated.');
            return redirect()->route('webadmin.admin_user_roleIndex');
        }

        return redirect()->back()->withInput();
    }

    public function user_role_delete(Request $request,$id){
        $previousArticle = AdminUserRole::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.admin_user_roleIndex');
        }

        $res = $previousArticle->delete();
        if ($res){
            toastr()->success('Admin User Role successfully deleted.');
            return redirect()->route('webadmin.admin_user_roleIndex');
        }

        return redirect()->back()->withInput();
    }


    public function monthly_points(Request $request){
        $role_id = $request->role_id;
        $monthly_points = $request->monthly_points;

        AdminAuth::where("id",$role_id)->update(["monthly_points"=>$monthly_points]);

        toastr()->success('Monthly point added successfully.');
        return redirect()->route('webadmin.admin_userIndex');
    }

    
}