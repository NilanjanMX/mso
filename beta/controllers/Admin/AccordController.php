<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;
use Response;

class AccordController extends Controller
{
    
    public function index(){
        return view('admin.accord.index');
    }

    public function saveaccorddata(Request $request){

        $input = $request->all();

        if ($image = $request->file('zip_file')){
            $zip_file = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/accord_zip/zip');
            $image->move($destinationPath, $zip_file);
            
            $prefix = "accord_";
            $zip = new ZipArchive;
            $res = $zip->open(public_path('/uploads/accord_zip/zip')."/".$zip_file);
            if ($res === TRUE){
                $path = public_path('/uploads/accord_zip/extract_zip');
                $zip->extractTo($path);
                $zip->close();
                DB::table("accord_tables")->update(["is_updated"=>"1"]);
                return redirect()->route('webadmin.accordupdatedata')->with('success','Zip Uploaded successful');
            }else{
                return redirect()->back()->withInput();
            }
        }else{
            return redirect()->back()->withInput();
        }
    }

    public function accordupdatedata(){
        $data = [];
        $data['folder_list'] = DB::table("accord_tables")->orderBy('name','ASC')->get();
        return view('admin.accord.folderlist',$data);
    }

    public function shortcategory(){
        $data = [];
        $data['table_list'] = DB::table("short_categories")->paginate(20);
        return view('admin.accord.shortcategory',$data);
    }

    public function short_category_add_edit(Request $request){
        $input = $request->all();
        $insertData = [];
        $insertData['short_name'] = $input['short_category_name'];
        $insertData['category_name'] = $input['category_name'];
        if($input['id']){
            DB::table("short_categories")->where("id",$input['id'])->update($insertData);
        }else{
            DB::table('short_categories')->insert($insertData);
        }
        return redirect()->back()->withInput();
    }

    public function short_category_delete($id){
        if($id){
            DB::table("short_categories")->where("id",$id)->delete();
        }else{

        }
        return redirect()->back()->withInput();
    }

    public function ratting(Request $request){
        $data = [];
        $data['search_text'] = $request->search_text;
        $data['search_type'] = $request->search_type;
        $table_list = DB::table("rattings");
        if($data['search_type']){
            if($data['search_type'] == 1){
                $table_list  = $table_list->where('short_name','=','');
            }else{
                $table_list  = $table_list->where('short_name','!=','');
            }
        }
        if($data['search_text']){
            $search_type = $data['search_text'];
            $table_list  = $table_list->where(function($query) use ($search_type){
                    $query->orWhere('category_name', 'like','%'.$search_type.'%');
                    $query->orWhere('short_name', 'like','%'.$search_type.'%');
                });
        }
        $data['table_list'] = $table_list->paginate(20);
        return view('admin.accord.ratting',$data);
    }

    public function ratting_add_edit(Request $request){
        $input = $request->all();
        $insertData = [];
        $insertData['short_name'] = $input['short_category_name'];
        $insertData['category_name'] = $input['category_name'];
        if($input['id']){
            DB::table("rattings")->where("id",$input['id'])->update($insertData);
        }else{
            DB::table('rattings')->insert($insertData);
        }
        return redirect()->back()->withInput();
    }

    public function ratting_sync(){
        $list = DB::table('accord_mf_portfolio')->select(['rating'])->groupBy('rating')->get();
        foreach($list as $key=>$value){
            $rattings = DB::table('rattings')->where('category_name','=',$value->rating)->first();
            if(!$rattings){
                $insertData = [
                    "category_name"=>$value->rating
                ];
                DB::table("rattings")->insert($insertData);
            }
        }
        return redirect()->back()->withInput();
    }

    public function ratting_delete($id){
        if($id){
            DB::table("rattings")->where("id",$id)->delete();
        }else{

        }
        return redirect()->back()->withInput();
    }

    public function updateaccordtable($file_name){
        ini_set('memory_limit', -1);
        $prefix = "accord_";
        $path = public_path('/uploads/accord_zip/extract_zip');
        
        $table_data = file_get_contents($path."/".$file_name);
        $table_data = json_decode($table_data);
        $total_count = count($table_data->Table);

        $table_name = explode('.txt', $file_name);
        $table_name = $table_name[0];
        $table_name = strtolower($table_name);
        
        // dd($table_data);
        // DB::table($prefix.$table_name)->delete();
        $i = 0;
        $insertData = [];
        foreach ($table_data->Table as $key => $value) {
            $insertData[] = (array)$value;
            $i = $i+1;
            if($i == 500){
                $i=0;
                DB::table($prefix.$table_name)->insert($insertData);
                $insertData = [];
            }else{
                if($key == $total_count-1){
                    DB::table($prefix.$table_name)->insert($insertData);
                }
            }
        }
        // echo $table_name; exit;
        DB::table("accord_tables")->where("table_name",$table_name)->update(["is_updated"=>"0"]);
        return redirect()->back()->withInput();
    }

    public function downloadaccordtable($file_name){
        ini_set('memory_limit', -1);
        $prefix = "accord_";
        // $path = public_path('/uploads/accord_zip/extract_zip');
        
        // $table_data = file_get_contents($path."/".$file_name);
        // $table_data = json_decode($table_data);
        // $total_count = count($table_data->Table);

        $table_name = explode('.txt', $file_name);
        $table_name = $table_name[0];
        $table_name = strtolower($table_name);
        

        $table_data = DB::table($prefix.$table_name)->get();
        // dd($table_data);
        $filename = $table_name.".csv";
        $handle = fopen("./storage/app/".$filename, 'w');
        // echo "ok"; exit;
        // dd($handle);
        $insertData = [];
        foreach($table_data as $key=>$row) {
            $insertData = (array) $row;
            // dd($insertData);
            if($key == 0){
                $headers_array = array("S. NO.");
                foreach ($insertData as $key1 => $value1) {
                    array_push($headers_array, strtoupper(str_replace("_"," ",$key1)));
                }
                // dd($headers_array);
                fputcsv($handle, $headers_array);
            }

            $body_array = array($key+1);
            foreach ($insertData as $key1 => $value1) {
                array_push($body_array, strtoupper(str_replace("_"," ",$value1)));
            }
            fputcsv($handle, $body_array);
        }
        //dd($handle);
        
        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );
        return Response::download('./storage/app/'.$filename, $table_name.".csv", $headers);
    }

    public function viewaccord(){
        $data = [];
        // https://contentapi.accordwebservices.com/RawData/GetTxtFile?filename=Amc_mst&date=23022021&section=MFMaster&sub=&token=41J3y6vo6MOY577GDHs41Dehsv2NZEzG
        // $endpoint = "http://mf.accordwebservices.com/MF/GetAssetType?token=kFgHuo5nbdJEg7ZfFyQMSngxi0z6WtVG";
        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', $endpoint);
        // $statusCode = $response->getStatusCode();
        // $data['assetTypes'] = json_decode($response->getBody());
        $data['folder_list'] = DB::table("accord_tables")->get();
        return view('admin.accord.list',$data);
    }

    public function viewaccordtable($id){
        $prefix = "accord_";
        $table_name = explode('.txt', $id);
        $table_name = $table_name[0];
        $table_name = strtolower($table_name);
        $table_list = DB::table($prefix.$table_name)->paginate(20);
        $title = str_replace("_"," ",$table_name);

        $data['title'] = strtoupper($title);
        return view('admin.accord.detail',$data)->with('table_list', $table_list);
    }
    

}
