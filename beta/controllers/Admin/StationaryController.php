<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Stationary;
use App\Models\Productcategory;
use App\Models\Packagepremade;
use App\Models\Packageimage;
use App\Models\StationaryProductType;
use App\Models\StationaryCategory;
use App\Models\StationaryGiftCard;
use App\Models\StationarySubCategory;
use App\Models\Membership;
use App\Models\StationaryGroup as StationaryGroupModel;
use App\Models\StationaryGroupUser;
use App\Models\PackageCreationDropdown;
use App\User;

class StationaryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Stationary::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_image', function ($row) {
                    $url=asset("uploads/stationary/thumbnail/$row->product_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.stationary-details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
                    }else{
                        $view_details = '';
                    }
                    return $view_details;
                })
                ->addColumn('created_at', function ($row) {
                    
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
                    $btn = '';
                    if(is_permitted('product-management-products', 'edit')){
                    $btn = '<a href="'.route('webadmin.stationaryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-management-products', 'delete')){
                    $btn .= '<a href="'.route('webadmin.stationaryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    if($row->store_type == "package"){
                        if(is_permitted('product-management-products', 'package')){
                            $btn .= '<a href="'.route('webadmin.managepackage',['id'=> $row->id ]).'"  class="edit btn btn-primary btn-sm ml-1">Manage Package</a>';
                        }
                    }else if($row->store_type == "premade"){
                        if(is_permitted('product-management-products', 'premade')){
                            $btn .= '<a href="'.route('webadmin.managepremade',['id'=> $row->id ]).'"  class="edit btn btn-primary btn-sm ml-1">Manage Pre made</a>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['product_image','view_details','created_at','status','action'])
                ->make(true);
        }
        return view('admin.stationary.index');
    }

    public function add(){
        // $data['categories'] = Productcategory::get();
        $data['categories'] = StationaryCategory::where('is_active',1)->get();
        $data['sub_categories'] = StationarySubCategory::where('is_active',1)->get();
        $data['product_types'] = StationaryProductType::where('is_active',1)->get();
        $data['groups'] = StationaryGroupModel::get();
        
        return view('admin.stationary.add',$data);
    }

    public function save(Request $request){
        
        $request->validate([
            'title' => 'required',
            'product_type_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $input = $request->all();
        
        $amount = 0;
        $input['offer_price'] = ($input['offer_price'])?$input['offer_price']:0;
        $input['member_price'] = ($input['member_price'])?$input['member_price']:0;
        if($input['type'] == 1){
            $amount = $input['mrp'] - $input['offer_price'] - $input['member_price'];
        } else if($input['type'] == 2){
            $amount = $input['mrp'] - (($input['mrp']*$input['offer_price'])/100) - (($input['mrp']*$input['member_price'])/100);
        }else{
            $amount = $input['mrp'];
        }

        $group_ids='';
        if(isset($input['group_ids'])){
            foreach ($input['group_ids'] as $key=>$value){
                if($group_ids){
                    $group_ids =  $group_ids.','.$value;
                }else{
                    $group_ids =  $value;
                }
            }
        }
        
        $mrp = $input['mrp'];
        if($input['category_id'] == 4){
            $amount = 0;
            $mrp = 0;
        }

        $saveData = [
            'title' => $input['title'],
            'category_id' => $input['category_id'],
            'sub_category_id' => $input['sub_category_id'],
            'product_type_id' => $input['product_type_id'],
            'store_type' => $input['store_type'],
            'content' => $input['content'],
            'amount' => $amount,
            'mrp' => $mrp,
            'type' => $input['type'],
            'offer_price' => $input['offer_price'],
            'member_price' => $input['member_price'],
            'member_coupon_code' => $input['member_coupon_code'],
            'member_coupon_type' => $input['member_coupon_type'],
            'member_coupon_value' => $input['member_coupon_value'],
            'non_member_coupon_code' => $input['non_member_coupon_code'],
            'non_member_coupon_type' => $input['non_member_coupon_type'],
            'non_member_coupon_value' => $input['non_member_coupon_value'],
            'group_type' => $input['group_type'],
            'group_ids' => $group_ids,
            'user_status' => $input['user_status'],
            'expired_at' => $input['expired_at'],
            'is_active' => isset($input['status'])?1:0
        ];
        
        // dd($saveData);

        if ($image = $request->file('product_image')){
            $saveData['product_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/stationary/thumbnail');
            $img = Image::make($image->getRealPath());
            //$img->resize(233, 182, function ($constraint) {
            $img->resize(233, NULL, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['product_image']);


            $destinationPath = public_path('/uploads/stationary');
            $image->move($destinationPath, $saveData['product_image']);
        }
        
        if($input['store_type'] == 'package'){
            if ($image = $request->file('default_image')){
                $saveData['default_image'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['default_image']);
            }
            if ($image = $request->file('logo_top_right_text_bottom_center_image')){
                $saveData['logo_top_right_text_bottom_center_image'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['logo_top_right_text_bottom_center_image']);
            }
            if ($image = $request->file('no_logo_text_center_image')){
                $saveData['no_logo_text_center_image'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['no_logo_text_center_image']);
            }
            if ($image = $request->file('logo_bottom_left_text_right')){
                $saveData['logo_bottom_left_text_right'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['logo_bottom_left_text_right']);
            }
            
            
        }

        $res = Stationary::create($saveData);
        
        // dd($res);
        if ($res){
            toastr()->success('Stationary successfully saved.');
            return redirect()->route('webadmin.stationary');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['categories'] = StationaryCategory::where('is_active',1)->get();
        $data['sub_categories'] = StationarySubCategory::where('is_active',1)->get();
        $data['product_types'] = StationaryProductType::where('is_active',1)->get();
        $data['groups'] = StationaryGroupModel::get();
        $data['stationary'] = Stationary::where('id',$id)->first();

        if(!empty($data['stationary']) && $data['stationary']->group_type != '' || $data['stationary']->group_type != 'all' ){
            $ids = explode(',', $data['stationary']->group_ids);
            $table = '';
            if($data['stationary']->group_type == 'group'){
                $groups = StationaryGroupModel::latest()->get();
                
                if (count($groups)>0){
                    foreach ($groups as $group){
                        if(in_array($group->id, $ids)){
                            $table .= '<option value="'.$group->id.'" selected>'.$group->name.'</option>';
                        }else{
                            $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                        }
                    }
                }
                $data['ph'] = 'Select Group(s)';
            }elseif($data['stationary']->group_type == 'user_type'){
                $userType = PackageCreationDropdown::latest()->get();
                // dd($userType);
                if (count($userType)>0){
                    foreach ($userType as $group){
                        if(!empty($group->id)){
                            if(in_array($group->id, $ids)){
                                $table .= '<option value="'.$group->id.'" selected>'.$group->name.'</option>';
                            }else{
                                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                            }
                        }
                    }
                }
                $data['ph'] = 'Select User Type(s)';
                // dd($table);
            }
            $data['options'] = $table;
            
        }

        // dd($data);

        return view('admin.stationary.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required'
        ]);

        $previousStationary = Stationary::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationary');
        }

        $input = $request->all();
        // dd($input);
        $amount = $input['mrp'];
        $mrp = $input['mrp'];
        if($input['category_id'] == 4){
            $amount = 0;
            $mrp = 0;
        }
        

        $group_ids='';
        if(isset($input['group_ids'])){
            foreach ($input['group_ids'] as $key=>$value){
                if($group_ids){
                    $group_ids =  $group_ids.','.$value;
                }else{
                    $group_ids =  $value;
                }
            }
        }

        $saveData = [
            'title' => $input['title'],
            'category_id' => $input['category_id'],
            'sub_category_id' => $input['sub_category_id'],
            'product_type_id' => $input['product_type_id'],
            'store_type' => $input['store_type'],
            'content' => $input['content'],
            'amount' => $amount,
            'mrp' => $mrp,
            'type' => $input['type'],
            'offer_price' => $input['offer_price'],
            'member_price' => $input['member_price'],
            'member_coupon_code' => $input['member_coupon_code'],
            'member_coupon_type' => $input['member_coupon_type'],
            'member_coupon_value' => $input['member_coupon_value'],
            'non_member_coupon_code' => $input['non_member_coupon_code'],
            'non_member_coupon_type' => $input['non_member_coupon_type'],
            'non_member_coupon_value' => $input['non_member_coupon_value'],
            'group_type' => $input['group_type'],
            'group_ids' => $group_ids,
            'user_status' => $input['user_status'],
            'expired_at' => $input['expired_at'],
            'is_active' => isset($input['status'])?1:0
        ];
        
        // dd($saveData);

        if ($image = $request->file('product_image')){
            $saveData['product_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/stationary/thumbnail');
            $img = Image::make($image->getRealPath());
            //$img->resize(233, 182, function ($constraint) {
            $img->resize(233, NULL, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['product_image']);
            $destinationPath = public_path('/uploads/stationary');
            $image->move($destinationPath, $saveData['product_image']);

            if (file_exists(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']))) {
                //chmod(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']));
            }
            if (file_exists(public_path('uploads/stationary/'.$previousStationary['product_image']))) {
                //chmod(public_path('uploads/stationary/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/stationary/'.$previousStationary['product_image']));
            }

        }
        
        if($input['store_type'] == 'package'){
            if ($image = $request->file('default_image')){
                $saveData['default_image'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['default_image']);
            }
            if ($image = $request->file('logo_top_right_text_bottom_center_image')){
                $saveData['logo_top_right_text_bottom_center_image'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['logo_top_right_text_bottom_center_image']);
            }
            if ($image = $request->file('no_logo_text_center_image')){
                $saveData['no_logo_text_center_image'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['no_logo_text_center_image']);
            }
            if ($image = $request->file('logo_bottom_left_text_right')){
                $saveData['logo_bottom_left_text_right'] = $image->getClientOriginalName().'-'.time().'.'.$image->getClientOriginalExtension();
    
                $destinationPath = public_path('/uploads/stationary');
                $image->move($destinationPath, $saveData['logo_bottom_left_text_right']);
            }
            
            
        }

        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Stationary successfully updated.');
            return redirect()->route('webadmin.stationary');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousStationary = Stationary::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationary');
        }

        if (file_exists(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']))) {
            //chmod(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']), 0644);
            unlink(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']));
        }
        if (file_exists(public_path('uploads/stationary/'.$previousStationary['product_image']))) {
            //chmod(public_path('uploads/stationary/'.$previousStationary['product_image']), 0644);
            unlink(public_path('uploads/stationary/'.$previousStationary['product_image']));
        }
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Stationary successfully deleted.');
            return redirect()->route('webadmin.stationary');
        }

        return redirect()->back()->withInput();
    }
    
    public function manage_package($id){
        //dd($id);
        $data['packageimages'] = Packageimage::where('stationary_id',$id)->get();
        $data['stationary'] = Stationary::where('id',$id)->first();
        return view('admin.stationary.managepackage',$data);
    }
    
    public function managepremade($id){
        //dd($id);
        $data['packageimages'] = Packageimage::where('stationary_id',$id)->get();
        $data['stationary'] = Stationary::where('id',$id)->first();
        $data['premade'] = Packagepremade::where('stationary_id',$id)->first();
        return view('admin.stationary.managepremade',$data);
    }
    
    public function manage_package_save(request $request){
        /*$request->validate([
            'images' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);*/
        
        $input = $request->all();
        
        //dd($input);
        
        if($files=$request->file('images')){
            foreach($files as $image){
                $saveData = [
                    'stationary_id' => $input['stationary_id'],
                ];
                $saveData['image'] = str_replace('.'.$image->getClientOriginalExtension(),'',$image->getClientOriginalName()).'-'.time().'.'.$image->getClientOriginalExtension();
                
                //dd($saveData['image']);

                $destinationPath = public_path('/uploads/packageimages/thumbnail');
                $img = Image::make($image->getRealPath());
                $img->resize(500, 625, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$saveData['image']);
                $destinationPath = public_path('/uploads/packageimages');
                $image->move($destinationPath, $saveData['image']);
                
                
                
                /*$name=$file->getClientOriginalName();
                $file->move('image',$name);
                $images[]=$name;*/
                
                $res = Packageimage::create($saveData);
        
                
            }
            
            if ($res){
                toastr()->success('Successfully Uploaded.');
                return redirect()->back();
            }
        }
        
    }
    
    public function manage_premade_save(request $request){
        $input = $request->all();
        $saveData = [];
        // dd($input);
        if ($image = $request->file('pdf_file')){
            $file = str_replace(' ', '-', $input['stationary_id']).time().'.'.$image->getClientOriginalExtension();
            $saveData['pdf_file'] = $file;

            $destinationPath = public_path('/uploads/packagepdf');
            $image->move($destinationPath, $file);
            
        }

        if ($image = $request->file('landscape_pdf')){
            $file = str_replace(' ', '-', $input['stationary_id']).time().'-land.'.$image->getClientOriginalExtension();
            $saveData['landscape_pdf'] = $file;

            $destinationPath = public_path('/uploads/packagepdf');
            $image->move($destinationPath, $file);
            
        }
        
        if ($image = $request->file('pdf_file_free')){
            $file = str_replace(' ', '-', $input['stationary_id']).time().'-free.'.$image->getClientOriginalExtension();
            $saveData['pdf_file_free'] = $file;

            $destinationPath = public_path('/uploads/packagepdf');
            $image->move($destinationPath, $file);
            
        }

        
        if ($image = $request->file('pdf_file_free_landscape')){
            $file = str_replace(' ', '-', $input['stationary_id']).time().'-free-land.'.$image->getClientOriginalExtension();
            $saveData['pdf_file_free_landscape'] = $file;

            $destinationPath = public_path('/uploads/packagepdf');
            $image->move($destinationPath, $file);
            
        }

        if(count($saveData)){
            $packagepremade = Packagepremade::where('stationary_id',$input['stationary_id'])->first();
            if($packagepremade){
                $packagepremade->update($saveData);
            }else{
                $saveData['stationary_id'] = $input['stationary_id'];
                $res = Packagepremade::create($saveData);
            }
            toastr()->success('Successfully Uploaded.');
            return redirect()->back();
        }else{
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->back();
        }
        
    }
    
    public function managepackagedelete($id){
        //dd($id);
        $previousStationary = Packageimage::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->back();
        }

        if (file_exists(public_path('uploads/packageimages/thumbnail/'.$previousStationary['image']))) {
            unlink(public_path('uploads/packageimages/thumbnail/'.$previousStationary['image']));
        }
        if (file_exists(public_path('uploads/packageimages/'.$previousStationary['image']))) {
            unlink(public_path('uploads/packageimages/'.$previousStationary['image']));
        }
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Package image successfully deleted.');
            return redirect()->back();
        }

        return redirect()->back()->withInput();
        
    }
    public function product_type_index(Request $request){
        if ($request->ajax()) {
            $data = StationaryProductType::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
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
                    $btn = '';
                    if(is_permitted('product-management-product-type', 'edit')){
                    $btn = '<a href="'.route('webadmin.stationaryProductTypeEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-management-product-type', 'delete')){
                    $btn .= '<a href="'.route('webadmin.stationaryProductTypeDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','status','action'])
                ->make(true);
        }
        return view('admin.stationary.product_type_index');
    }

    public function product_type_add(){
        $data = [];
        return view('admin.stationary.product_type_add',$data);
    }

    public function product_type_save(Request $request){
        
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = StationaryProductType::create($saveData);
        if ($res){
            toastr()->success('Product Type successfully saved.');
            return redirect()->route('webadmin.stationaryProductType');
        }

        return redirect()->back()->withInput();
    }

    public function product_type_edit($id){
        $data['stationary'] = StationaryProductType::where('id',$id)->first();
        return view('admin.stationary.product_type_edit',$data);
    }

    public function product_type_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $previousStationary = StationaryProductType::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationary');
        }

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];


        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Product Type successfully updated.');
            return redirect()->route('webadmin.stationaryProductType');
        }

        return redirect()->back()->withInput();
    }

    public function product_type_delete(Request $request,$id){
        $previousStationary = StationaryProductType::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationaryProductType');
        }

        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Product Type successfully deleted.');
            return redirect()->route('webadmin.stationaryProductType');
        }

        return redirect()->back()->withInput();
    }

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = StationaryCategory::latest()->get();

            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
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
                    $btn = '';
                    if(is_permitted('product-management-product-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.stationaryCategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-management-product-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.stationaryCategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','status','action'])
                ->make(true);
        }
        return view('admin.stationary.category_index');
    }

    public function category_add(){
        $data = [];
        return view('admin.stationary.category_add',$data);
    }

    public function category_save(Request $request){
        
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = StationaryCategory::create($saveData);
        if ($res){
            toastr()->success('Categories successfully saved.');
            return redirect()->route('webadmin.stationaryCategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['stationary'] = StationaryCategory::where('id',$id)->first();
        return view('admin.stationary.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $previousStationary = StationaryCategory::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationaryCategory');
        }

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];


        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Categories successfully updated.');
            return redirect()->route('webadmin.stationaryCategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousStationary = StationaryCategory::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationaryCategory');
        }

        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Categories successfully deleted.');
            return redirect()->route('webadmin.stationaryCategory');
        }

        return redirect()->back()->withInput();
    }

    public function sub_category_index(Request $request){
        if ($request->ajax()) {
            $data = StationarySubCategory::latest()->get();

            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('category', function ($row) {

                    $category = "";
                    if(!empty($row->category_id)){
                        $category = StationaryCategory::where('id',$row->category_id)->first();
                        if($category){
                            $category = $category->name;
                        }else{
                           $category = ""; 
                        }
                    }
                    return $category;
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
                    $btn = '';
                    if(is_permitted('product-management-product-subcategory', 'edit')){
                    $btn = '<a href="'.route('webadmin.stationarySubCategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-management-product-subcategory', 'delete')){
                    $btn .= '<a href="'.route('webadmin.stationarySubCategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['category','created_at','status','action'])
                ->make(true);
        }
        return view('admin.stationary.sub_category_index');
    }

    public function sub_category_add(){
        $data = [];
        $data['categories'] = StationaryCategory::where('is_active',1)->get();
        return view('admin.stationary.sub_category_add',$data);
    }

    public function sub_category_save(Request $request){
        
        $request->validate([
            'name' => 'required',
            'category' => 'required'
        ]);

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'category_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = StationarySubCategory::create($saveData);
        if ($res){
            toastr()->success('Sub Categories successfully saved.');
            return redirect()->route('webadmin.stationarySubCategory');
        }
        return redirect()->back()->withInput();
    }

    public function sub_category_edit($id){
        $data['stationary'] = StationarySubCategory::where('id',$id)->first();
        $data['categories'] = StationaryCategory::where('is_active',1)->get();
        return view('admin.stationary.sub_category_edit',$data);
    }

    public function sub_category_update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'category' => 'required'
        ]);

        $previousStationary = StationarySubCategory::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationarySubCategory');
        }

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'category_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];


        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Sub Categories successfully updated.');
            return redirect()->route('webadmin.stationarySubCategory');
        }

        return redirect()->back()->withInput();
    }

    public function sub_category_delete(Request $request,$id){
        $previousStationary = StationarySubCategory::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationarySubCategory');
        }

        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Sub Categories successfully deleted.');
            return redirect()->route('webadmin.stationarySubCategory');
        }

        return redirect()->back()->withInput();
    }

    public function gift_card_index(Request $request){
        if ($request->ajax()) {
            $data = StationaryGiftCard::latest()->get();

            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
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
                    $btn = '';
                    if(is_permitted('product-management-product-giftcard', 'edit')){
                    $btn = '<a href="'.route('webadmin.stationaryGiftCardEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-management-product-giftcard', 'delete')){
                    $btn .= '<a href="'.route('webadmin.stationaryGiftCardDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','status','action'])
                ->make(true);
        }
        return view('admin.stationary.gift_card_index');
    }

    public function gift_card_add(){
        $data = [];
        return view('admin.stationary.gift_card_add',$data);
    }

    public function gift_card_save(Request $request){
        
        $request->validate([
            'name' => 'required',
            'claim_code' => 'required',
            'amount' => 'required'
        ]);

        $input = $request->all();


        $group_ids='';
        if(isset($input['group_ids'])){
            foreach ($input['group_ids'] as $key=>$value){
                if($group_ids){
                    $group_ids =  $group_ids.','.$value;
                }else{
                    $group_ids =  $value;
                }
            }
        }

        $saveData = [
            'name' => $input['name'],
            'amount' => $input['amount'],
            'claim_code' => $input['claim_code'],
            'group_type' => $input['group_type'],
            'group_ids' => $group_ids,
            'is_active' => isset($input['status'])?1:0
        ];

        $res = StationaryGiftCard::create($saveData);
        if ($res){
            toastr()->success('Gift Card successfully saved.');
            return redirect()->route('webadmin.stationaryGiftCard');
        }

        return redirect()->back()->withInput();
    }

    public function gift_card_edit($id){
        $data['stationary'] = StationaryGiftCard::where('id',$id)->first();

        if(!empty($data['stationary']) && $data['stationary']->group_type != '' || $data['stationary']->group_type != 'all' ){
            $ids = explode(',', $data['stationary']->group_ids);
            $table = '';
            if($data['stationary']->group_type == 'group'){
                $groups = StationaryGroupModel::latest()->get();
                
                if (count($groups)>0){
                    foreach ($groups as $group){
                        if(in_array($group->id, $ids)){
                            $table .= '<option value="'.$group->id.'" selected>'.$group->name.'</option>';
                        }else{
                            $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                        }
                    }
                }
                $data['ph'] = 'Select Group(s)';
            }elseif($data['stationary']->group_type == 'user_type'){
                $userType = PackageCreationDropdown::select('id','name')->get();
                if (count($userType)>0){
                    foreach ($userType as $group){
                        if(!empty($group->id)){
                            if(in_array($group->id, $ids)){
                                $table .= '<option value="'.$group->id.'" selected>'.$group->name.'</option>';
                            }else{
                                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                            }
                        }
                    }
                }
                $data['ph'] = 'Select User Type(s)';
                // dd($table);
            }
            $data['options'] = $table;
            
        }
        // dd($data);

        return view('admin.stationary.gift_card_edit',$data);
    }

    public function gift_card_update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'claim_code' => 'required',
            'amount' => 'required'
        ]);

        $previousStationary = StationaryGiftCard::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationaryGiftCard');
        }

        $input = $request->all();

        $group_ids='';
        if(isset($input['group_ids'])){
            foreach ($input['group_ids'] as $key=>$value){
                if($group_ids){
                    $group_ids =  $group_ids.','.$value;
                }else{
                    $group_ids =  $value;
                }
            }
        }
        
        $saveData = [
            'name' => $input['name'],
            'amount' => $input['amount'],
            'claim_code' => $input['claim_code'],
            'group_type' => $input['group_type'],
            'group_ids' => $group_ids,
            'is_active' => isset($input['status'])?1:0
        ];


        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Gift Card successfully updated.');
            return redirect()->route('webadmin.stationaryGiftCard');
        }

        return redirect()->back()->withInput();
    }

    public function gift_card_delete(Request $request,$id){
        $previousStationary = StationaryGiftCard::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.stationaryGiftCard');
        }

        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Gift Card successfully deleted.');
            return redirect()->route('webadmin.stationaryGiftCard');
        }

        return redirect()->back()->withInput();
    }

    public function group_index(Request $request){
        $data['users'] = User::latest()->get();
        return view('admin.stationary.group_index',$data);
    }

    public function saveGroup(Request $request)
    {
        $input = $request->all();
        $groupInfo = StationaryGroupModel::create([
            'name' => $input['group_name']
        ]);
        if ($groupInfo){
            foreach ($input['users'] as $user){
                StationaryGroupUser::create([
                    'group_id' => $groupInfo->id,
                    'user_id' => $user
                ]);
            }
        }
    }

    public function groupIndex(Request $request){
        if ($request->ajax()) {
            $data = StationaryGroupModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('group_count', function($row){
                    return $row->groupUsers()->count();
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('product-management-product-group', 'edit')){
                    $btn = '<a href="'.route('webadmin.stationaryGroupUserindex',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">View</a>';
                    }
                    if(is_permitted('product-management-product-group', 'delete')){
                    $btn .= '<a href="'.route('webadmin.stationaryRemoveGroup',['id'=> $row->id ]).'" class="edit btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.stationary.groupindex');
    }

    public function groupUserindex(Request $request,$id){
        if ($request->ajax()) {

            $data = StationaryGroupUser::where('group_id',$request->group_id)->latest()->get();

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
        $data['groupinfo'] = StationaryGroupModel::where('id',$id)->first();
        return view('admin.stationary.groupuserindex',$data);
    }

    public function removeGroupUser(Request $request)
    {
        $input = $request->all();

        if (count($input['users'])>0){
            foreach ($input['users'] as $user){
                $info = StationaryGroupUser::where('id',$user)->first();
                if ($info){
                    $info->delete();
                }
            }
        }
    }

    public function removeGroup(Request $request,$id)
    {

        StationaryGroupModel::where('id',$id)->delete();
        StationaryGroupUser::where('group_id',$id)->delete();
        return redirect()->back()->withInput();
    }

    public function getGroups()
    {
        $data = StationaryGroupModel::latest()->get();

        $table = '';
        if (count($data)>0){
            foreach ($data as $group){
                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }

        return $table;
    }
    
    
    public function getUsertype()
    {
        $data = PackageCreationDropdown::latest()->get();

        $table = '';
        if (count($data)>0){
            foreach ($data as $group){
                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }

        return $table;
    }
    public function getUser()
    {
        $data = User::latest()->get();

        $table = '';
        if (count($data)>0){
            foreach ($data as $group){
                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }

        return $table;
    }
    
    
    
}