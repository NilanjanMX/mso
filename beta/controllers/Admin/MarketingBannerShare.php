<?php

namespace App\Http\Controllers\Admin;

use App\Models\Displayinfo;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Membership;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class MarketingBannerShare extends Controller
{
    public function index(Request $request){
        ini_set('memory_limit', -1);
        if ($request->ajax()) {
            if ($request->user_type!='all'){
                $membership = Membership::select('user_id')->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->get()->toArray();
                if ($request->user_type=='paid'){
                    $data = User::whereIn('id',$membership)->latest()->get();
                }else{
                    $data = User::whereNotIn('id',$membership)->latest()->get();
                }

            }
            else{
                $data = User::latest()->get();
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" name="user[]" value="'.$row->id.'" />';
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        return view('admin.marketingbannershare.index');
    }

    public function saveGroup(Request $request)
    {
        $input = $request->all();
        $groupInfo = Group::create([
            'name' => $input['group_name']
        ]);
        if ($groupInfo){
            foreach ($input['users'] as $user){
                GroupUser::create([
                    'group_id' => $groupInfo->id,
                    'user_id' => $user
                ]);
            }
        }
    }

    public function groupIndex(Request $request){
        if ($request->ajax()) {
            $data = Group::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('group_count', function($row){
                    return $row->groupUsers()->count();
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('whatsapp-share-group-list', 'view')){
                    $btn = '<a href="'.route('webadmin.groupUserindex',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">View</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.marketingbannershare.groupindex');
    }

    public function groupUserindex(Request $request,$id){
        if ($request->ajax()) {

            $data = GroupUser::where('group_id',$request->group_id)->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" name="groupuser[]" value="'.$row->id.'" />';
                })
                ->addColumn('name', function ($row) {
                    return $row->userDetails['name'];
                })
                ->addColumn('email', function ($row) {
                    return $row->userDetails['email'];
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->userDetails['phone_no'];
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        $data['groupinfo'] = Group::where('id',$id)->first();
        return view('admin.marketingbannershare.groupuserindex',$data);
    }

    public function removeGroupUser(Request $request)
    {
        $input = $request->all();

        if (count($input['users'])>0){
            foreach ($input['users'] as $user){
                $info = GroupUser::where('id',$user)->first();
                if ($info){
                    $info->delete();
                }
            }
        }
    }

    public function sendForm(){
        $data['grouplists'] = Group::orderBy('name','asc')->get();
        return view('admin.marketingbannershare.send',$data);
    }

    public function saveToGroupMember(Request $request)
    {
        if ($request->send_type == 'by_group'){
            $request->validate([
                'caption' => 'required',
                'group' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
           }else{
            $request->validate([
                'caption' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'csv_file' => 'required'
            ]);
        }

        $input = $request->all();

        if ($request->send_type == 'by_group') {
            $saveData = [
                'caption' => $input['caption'],
                'group' => $input['group']
            ];

            if ($image = $request->file('image')) {
                $file = time() . '.' . $image->getClientOriginalExtension();
                $saveData['image'] = $file;
                $destinationPath = public_path('/uploads/shareimage');
                $image->move($destinationPath, $file);
                $users = GroupUser::where('group_id', $request->group)->latest()->get();
                if (isset($users) && count($users)) {
                    foreach ($users as $user) {
                        $imagePath = $this->createBranding($file, $user['user_id']);
                        $userInfo = User::where('id', $user['user_id'])->first();
                        //$this->sendFile($userInfo['phone_no'],$imagePath,$input['caption']);
                        $this->sendFile('91' . $userInfo['phone_no'], $imagePath, $input['caption']);
                    }
                }

                toastr()->success('Image successfully shared.');
                return redirect()->route('webadmin.sendForm');
            }
        }else{
            $path = $request->file('csv_file')->getRealPath();
            $customerArr = $this->csvToArray($path);
            if ($image = $request->file('image')) {
                $file = time() . '.' . $image->getClientOriginalExtension();
                $saveData['image'] = $file;
                $destinationPath = public_path('/uploads/shareimage');
                $image->move($destinationPath, $file);
                $watermark = (isset($input['watermark']))?1:0;
                if (isset($customerArr) && count($customerArr)) {
                    for ($i = 0; $i < count($customerArr); $i ++) {
                        $imagePath = $this->createMasterstrokeBranding($file, $watermark);
                        $this->sendFile('91' . $customerArr[$i]['phone_no'], $imagePath, $input['caption']);
                    }
                }

                toastr()->success('Image successfully shared.');
                return redirect()->route('webadmin.sendForm');
            }
        }
        return redirect()->back()->withInput();
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }


    public function createMasterstrokeBranding($image,$watermark)
    {
        //ini_set('memory_limit','1024M');
        ini_set('memory_limit', -1);
        $displayInfo = [
            'company_name' => 'Dalmia Advisory Services Pvt. Ltd',
            'display_name' => 'Brijesh Dalmia',
            'phone_no' => '9883818627',
            'email' => 'info@masterstrokeonline.com',
            'website' => 'www.masterstrokeonline.com',
            'address' => '203, Vinayak Chambers 10/A,Hospital Street | Kolkata - 700072'
        ];

        $img = Image::make(public_path('uploads/shareimage/'.$image));
        $foo = Image::make(public_path('uploads/shareimage/'.$image))->fit($img->getSize()->width, $img->getSize()->height);

        $branding = Image::make(public_path('f/images/bottom-img-border.jpg'))->fit($img->getSize()->width, 500);

        /*$logo = Image::make(public_path('f/images/logo.png'));
        $logo_width = $logo->getSize()->width;
        $logo_height = $logo->getSize()->height;

        if($logo_width == $logo_height){
            //$logo->resize(350, 350);
            $first_pos = 300;
            $last_pos = 120;
        }else{
            //$logo->resize(600, 300);
            $first_pos = 160;
            $last_pos = 150;
        }
        //$logo->save(public_path('f/images/no-image-logo-custome.png'));*/
        $first_pos = 160;
        $last_pos = 150;
        //Company Logo
        $branding->insert(public_path('f/images/default-logo.jpg'), 'bottom-left', $first_pos, $last_pos);
        
        //Display Name
        $font_size = 50;
        $first_pos = 940;
        $last_pos = 120;
        $namecolor = '#000000';
        $branding->text($displayInfo['display_name'], $first_pos, $last_pos, function ($font) use ($font_size,$namecolor) {
            $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
            $font->size($font_size);
            $font->color($namecolor);
        });
        //Company Name
         $font_size = 40;
         $font_size = 55;
         $first_pos = 940;
         $last_pos = 190;
        $company_name_color = '#000000';
        $branding->text($displayInfo['company_name'], $first_pos, $last_pos, function ($font) use ($font_size,$company_name_color) {
            $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
            $font->size($font_size);
            $font->color($company_name_color);
        });
        //Phone
            $first_icon_pos = 940;
            $last_icon_pos = 224;
            $first_pos = 1000;
            $last_pos = 266;
            $branding->insert(public_path('f/images/call-icon.png'), 'bottom-left', $first_icon_pos, $last_icon_pos);
            $phone_no_color = '#000000';

            $branding->text(' +91 '.$displayInfo['phone_no'], $first_pos, $last_pos, function ($font) use ($phone_no_color) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(50);
                $font->color($phone_no_color);
            });
        //Email

            $first_icon_pos = 1430;
            $last_icon_pos = 224;
            $first_pos = 1496;
            $last_pos = 266;
            $branding->insert(public_path('f/images/email-icon.png'), 'bottom-left', $first_icon_pos, $last_icon_pos);
            $email_color = '#000000';

            $branding->text($displayInfo['email'], $first_pos, $last_pos, function ($font) use ($email_color) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(50);
                $font->color($email_color);
            });
        //Website
        $branding->insert(public_path('f/images/web-icon.png'), 'bottom-left', 940, 150);
        $website_color = '#000000';
        $branding->text($displayInfo['website'], 1016, 336, function ($font) use ($website_color) {
            $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
            $font->size(50);
            $font->color($website_color);
        });
        //Address
        $addressbar = Image::make(public_path('f/images/address-bg.jpg'))->fit($img->getSize()->width, 102);
        $addressbar->fill('#ffffff');

            $stringlength = strlen($displayInfo['address']);
            if($stringlength > 60){
                $first_pos = 500;
                $last_pos = 66;
            }elseif($stringlength > 55){
                $first_pos = 525;
                $last_pos = 66;
            }elseif($stringlength > 50){
                $first_pos = 550;
                $last_pos = 66;
            }elseif($stringlength > 45){
                $first_pos = 600;
                $last_pos = 66;
            }elseif($stringlength > 40){
                $first_pos = 650;
                $last_pos = 66;
            }elseif($stringlength > 35){
                $first_pos = 700;
                $last_pos = 66;
            }elseif($stringlength > 30){
                $first_pos = 750;
                $last_pos = 66;
            }elseif($stringlength > 25){
                $first_pos = 800;
                $last_pos = 66;
            }elseif($stringlength > 20){
                $first_pos = 850;
                $last_pos = 66;
            }elseif($stringlength > 15){
                $first_pos = 900;
                $last_pos = 66;
            }else{
                $first_pos = 1000;
                $last_pos = 66;
            }
            $address_color = '#000000';
            $addressbar->text($displayInfo['address'], $first_pos, $last_pos, function ($font) use ($address_color) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(48);
                $font->color($address_color);
            });

        $height = $img->getSize()->height+500;

        $canvas = Image::canvas($img->getSize()->width, $height);
        $canvas->insert($foo, 'top');
        $canvas->insert($branding, 'bottom');
        $canvas->insert($addressbar, 'bottom');

        if(isset($watermark) && $watermark==1){
            $canvas->text('MasterStoke', 1850, $img->getSize()->height-30, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(100);
                $font->color('rgba(0,0,0,0.10)');
                $font->angle(90);
            });
        }

        $today_date = date('d-m-Y');

        $canvas->text($today_date, 100, $img->getSize()->height-30, function ($font) {
            $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
            $font->size(20);
            $font->color('rgba(0,0,0,0.10)');
            $font->angle(90);
        });
        $time = time();
        $canvas->save(public_path('uploads/shareimage/branding/'.$time.'.jpg'));
        $filepath = public_path('uploads/shareimage/branding/'.$time.'.jpg');
        return $filepath;
    }

        // Marketing Banner Or Premium Banner
    public function createBranding($image,$user_id)
    {
        //ini_set('memory_limit','1024M');
        ini_set('memory_limit', -1);
        $user = User::where('id',$user_id)->first();

        $displayInfo = Displayinfo::where('user_id',$user_id)->first();
        $username = $displayInfo->name;
        $membership = Membership::where('user_id', $user_id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();

        $img = Image::make(public_path('uploads/shareimage/'.$image));
        $foo = Image::make(public_path('uploads/shareimage/'.$image))->fit($img->getSize()->width, $img->getSize()->height);

        $branding = Image::make(public_path('f/images/bottom-img-border.jpg'))->fit($img->getSize()->width, 500);

        if(!empty($user->company_logo)){
            $logo = Image::make(public_path('uploads/logo/original/'.$user->company_logo));
            $logo_width = $logo->getSize()->width;
            $logo_height = $logo->getSize()->height;

            if($logo_width == $logo_height){
                $logo->resize(350, 350);
                $first_pos = 300;
                $last_pos = 120;
            }else{
                $logo->resize(600, 300);
                $first_pos = 160;
                $last_pos = 150;
            }


            $logo->save(public_path('images/download/logo.png'));
            $branding->insert(public_path('images/download/logo.png'), 'bottom-left', $first_pos, $last_pos);
        }else{
            $branding->insert(public_path('f/images/no-image-logo.jpg'), 'bottom-left', 160, 150);
        }
        $number_ofcheck_fields = 0;
        if($displayInfo->name_check){
            $number_ofcheck_fields++;
        }
        if($displayInfo->company_name_check){
            $number_ofcheck_fields++;
        }
        if($displayInfo->phone_no_check){
            $number_ofcheck_fields++;
        }
        if($displayInfo->email_check){
            $number_ofcheck_fields++;
        }
        if($displayInfo->website_check){
            $number_ofcheck_fields++;
        }
        if($displayInfo->name_check){
            $stringlength = strlen($username);
            if($stringlength > 40){
                $font_size = 40;
            }elseif($stringlength > 30){
                $font_size = 60;
            }elseif($stringlength > 15){
                $font_size = 80;
            }else{
                $font_size = 50;
            }
            if($number_ofcheck_fields == 2 && $displayInfo->company_name_check != 1){
                $first_pos = 940;
                $last_pos = 190;
            }elseif($displayInfo->company_name_check != 1 && $displayInfo->phone_no_check != 1){
                $first_pos = 940;
                $last_pos = 170;
            }else{
                $first_pos = 940;
                $last_pos = 120;
            }


            $namecolor = $displayInfo->name_color;

            $branding->text($username, $first_pos, $last_pos, function ($font) use ($font_size,$namecolor) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size($font_size);
                $font->color($namecolor);
            });
        }
        if($displayInfo->company_name_check){
            $stringlength = strlen($displayInfo->company_name);
            if($displayInfo->name_check == 1){
                if($stringlength > 30){
                    $font_size = 40;
                }elseif($stringlength > 15){
                    $font_size = 60;
                }else{
                    $font_size = 50;
                }
                $font_size = 55;
                $first_pos = 940;
                $last_pos = 190;

            }else{
                if($stringlength > 40){
                    $font_size = 40;
                }elseif($stringlength > 30){
                    $font_size = 60;
                }elseif($stringlength > 15){
                    $font_size = 80;
                }else{
                    $font_size = 50;
                }
                $first_pos = 940;
                $last_pos = 165;

            }

            $company_name_color = $displayInfo->company_name_color;

            $branding->text($displayInfo->company_name, $first_pos, $last_pos, function ($font) use ($font_size,$company_name_color) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size($font_size);
                $font->color($company_name_color);
            });
        }
        if($displayInfo->phone_no_check){
            if($displayInfo->company_name_check ==1){
                $first_icon_pos = 940;
                $last_icon_pos = 224;
                $first_pos = 1000;
                $last_pos = 266;
            }else{
                $first_icon_pos = 940;
                $last_icon_pos = 300;
                $first_pos = 1000;
                $last_pos = 190;
            }

            if($number_ofcheck_fields == 2 && $displayInfo->company_name_check != 1 && $displayInfo->website_check != 1){
                $first_icon_pos = 940;
                $last_icon_pos = 200;
                $first_pos = 1016;
                $last_pos = 290;
            }
            if($displayInfo->phone_no != ''){
                $branding->insert(public_path('f/images/call-icon.png'), 'bottom-left', $first_icon_pos, $last_icon_pos);
                $phone_no_color = $displayInfo->phone_no_color;

                $branding->text(' +91 '.$displayInfo->phone_no, $first_pos, $last_pos, function ($font) use ($phone_no_color) {
                    $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                    $font->size(50);
                    $font->color($phone_no_color);
                });
            }
        }
        if($displayInfo->email_check){
            if($displayInfo->phone_no_check == 1){
                $first_icon_pos = 1430;
                $last_icon_pos = 224;
                $first_pos = 1496;
                $last_pos = 266;
            }else{
                $first_icon_pos = 940;
                $last_icon_pos = 224;
                $first_pos = 1000;
                $last_pos = 266;
            }
            if($displayInfo->company_name_check !=1){
                $first_icon_pos = 940;
                $last_icon_pos = 224;
                $first_pos = 1000;
                $last_pos = 266;
            }
            if($displayInfo->email != ''){

                $branding->insert(public_path('f/images/email-icon.png'), 'bottom-left', $first_icon_pos, $last_icon_pos);
                $email_color = $displayInfo->email_color;

                $branding->text($displayInfo->email, $first_pos, $last_pos, function ($font) use ($email_color) {
                    $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                    $font->size(50);
                    $font->color($email_color);
                });
            }
        }
        if($displayInfo->website_check){
            if($displayInfo->website != ''){
                $branding->insert(public_path('f/images/web-icon.png'), 'bottom-left', 940, 150);
                $website_color = $displayInfo->website_color;
                $branding->text($displayInfo->website, 1016, 336, function ($font) use ($website_color) {
                    $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                    $font->size(50);
                    $font->color($website_color);
                });
            }
        }
        $addressbar = Image::make(public_path('f/images/address-bg.jpg'))->fit($img->getSize()->width, 102);
        $addressbar->fill($displayInfo->address_color_background);
        if($displayInfo->address_check){
            $stringlength = strlen($displayInfo->address);
            if($stringlength > 60){
                $first_pos = 500;
                $last_pos = 66;
            }elseif($stringlength > 55){
                $first_pos = 525;
                $last_pos = 66;
            }elseif($stringlength > 50){
                $first_pos = 550;
                $last_pos = 66;
            }elseif($stringlength > 45){
                $first_pos = 600;
                $last_pos = 66;
            }elseif($stringlength > 40){
                $first_pos = 650;
                $last_pos = 66;
            }elseif($stringlength > 35){
                $first_pos = 700;
                $last_pos = 66;
            }elseif($stringlength > 30){
                $first_pos = 750;
                $last_pos = 66;
            }elseif($stringlength > 25){
                $first_pos = 800;
                $last_pos = 66;
            }elseif($stringlength > 20){
                $first_pos = 850;
                $last_pos = 66;
            }elseif($stringlength > 15){
                $first_pos = 900;
                $last_pos = 66;
            }else{
                $first_pos = 1000;
                $last_pos = 66;
            }

            $address_color = $displayInfo->address_color;
            $addressbar->text($displayInfo->address, $first_pos, $last_pos, function ($font) use ($address_color) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(48);
                $font->color($address_color);
            });

        }

        $height = $img->getSize()->height+500;

        $canvas = Image::canvas($img->getSize()->width, $height);
        $canvas->insert($foo, 'top');
        $canvas->insert($branding, 'bottom');
        $canvas->insert($addressbar, 'bottom');


        if($displayInfo->company_name_watermark){
            $canvas->text($displayInfo->company_name, 2150, $img->getSize()->height-30, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(100);
                $font->color('rgba(0,0,0,0.10)');
                $font->angle(90);
            });
        }

        if($displayInfo->name_watermark){
            $canvas->text($displayInfo->name, 2000, $img->getSize()->height-30, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(100);
                $font->color('rgba(0,0,0,0.10)');
                $font->angle(90);
            });
        }

        if($membership < 1){
            $canvas->text('MasterStoke', 1850, $img->getSize()->height-30, function ($font) {
                $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
                $font->size(100);
                $font->color('rgba(0,0,0,0.10)');
                $font->angle(90);
            });
        }

        $today_date = date('d-m-Y');

        $canvas->text($today_date, 100, $img->getSize()->height-30, function ($font) {
            $font->file(public_path('f/fonts/Poppins-Regular.ttf'));
            $font->size(20);
            $font->color('rgba(0,0,0,0.10)');
            $font->angle(90);
        });



        $time = time();
        $canvas->save(public_path('uploads/shareimage/branding/'.$time.'.jpg'));
        $filepath = public_path('uploads/shareimage/branding/'.$time.'.jpg');
        return $filepath;
    }

    public function sendFile($phone,$imgpath,$caption)
    {
        $path_parts = pathinfo($imgpath);
        $type = pathinfo($imgpath, PATHINFO_EXTENSION);
        $data = file_get_contents($imgpath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $filename = $path_parts['filename'];
        $data = [
            'phone' => (int)$phone,
            'body' => $base64,
            'filename' => $filename.'.jpg',
            'caption' => $caption
        ];

        $json = json_encode($data); 

        //$url = 'https://eu126.chat-api.com/instance140243/sendFile?token=ixrfxfy6zngx6dew';
        //$url = 'https://eu71.chat-api.com/instance141114/sendFile?token=36q2y20th1rdqiys';
        $url = 'https://eu149.chat-api.com/instance154818/sendFile?token=0a2tz64db3s8vzcg';
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($curl_handle);
        curl_close($curl_handle);
    }
}
