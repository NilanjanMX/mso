<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Databasebackup;

class DatabasebackupController extends Controller
{
    
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Databasebackup::latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
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
                    if(is_permitted('database-backup-index', 'download')){
                    $btn = '<a href="'.asset('backup').'/'.$row->name.'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-download" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('database-backup-index', 'delete')){
                    $btn .= '<a href="'.url('webadmin/database-backup-delete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.backup.database_index');
    }

    public function databasebackupnow(Request $request){

        $input = $request->all();
        // $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".sql";
        
        $filename = "backup.sql";
        
        
        
        $saveData = [
            "name"=>$filename,
            "is_active"=>1
        ];
        $res = Databasebackup::create($saveData);
        // 
        return redirect()->back()->withInput();
    }

    public function databasebackupdelete($id){
        $info = Databasebackup::where('id',$id)->first();
        if ($info){
            $filename = public_path('backup').'/'.$info->name;
            if (file_exists($filename)) {
                // chmod($filename, 0644);
                // unlink($filename);
            }
            $info->delete();
        }
        return redirect()->back()->withInput();
    }
    

}
