<?php

namespace App\Http\Controllers\Admin;

use App\Models\Salespresentercategory;
use App\Models\Salespresentersoftcopy;
use App\Models\Savelist;
use App\Models\Savelistsoftcopy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SuggestedSalespresenterController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Savelist::where('user_id',0)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function($row){
                    return $row->title.' ( '.$row->softcopies->count().' images )';
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('sales-presenters-suggested', 'edit')){
                    $btn = '<a href="'.route('webadmin.suggestedSalespresentersoftcopyEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('sales-presenters-suggested', 'sort')){
                    $btn .= '<a href="'.route('webadmin.suggestedSalespresentersoftcopySort',['id'=> $row->id ]).'"  class="edit btn btn-info btn-sm ml-1">Sort</a>';
                    }
                    if(is_permitted('sales-presenters-suggested', 'delete')){
                    $btn .= '<a href="'.route('webadmin.suggestedSalespresentersoftcopyDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.suggestedsalespresenter.index');
    }

    public function add(){
        $data['salespresentercategories'] = Salespresentercategory::with('softcopies')->where('is_active',1)->get();
        $data['totalSoftCopy'] = Salespresentersoftcopy::where('is_active',1)->count();
        $data['softCopies'] = Salespresentersoftcopy::where('is_active',1)->get();
        return view('admin.suggestedsalespresenter.add',$data);
    }

    public function checkSavelist($listname){
        $savelist = Savelist::where('title',$listname)->first();
        if(!empty($savelist)){
            $response = 1;
        }else{
            $response = 0;
        }

        return $response;
    }

    public function saveListData(Request $request){
        $date=strtotime(date('Y-m-d'));
        $validate_at = date('Y-m-d',strtotime('+364 days',$date));
        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'user_id' => 0,
            'validate_at' => $validate_at
        ];

        $res = Savelist::create($saveData);
        $last_insert_id = $res->id;
        $softcopy_ids = array_unique($input['softcopy_id']);
        if(isset($softcopy_ids) && !empty($softcopy_ids)){
            foreach($softcopy_ids as $softcopy){
                $last_position = Savelistsoftcopy::max('position');
                $last_position++;
                $saveData = [
                    'savelist_id' => $last_insert_id,
                    'softcopy_id' => $softcopy,
                    'position' => $last_position
                ];
                $res = Savelistsoftcopy::create($saveData);
            }
        }

        if ($res){
            return redirect()->route('webadmin.suggestedSalespresentersoftcopy')->with('success','Sales presenters suggested list successfully saved.');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['salespresentercategories'] = Salespresentercategory::with('softcopies')->where('is_active',1)->get();
        $data['totalSoftCopy'] = Salespresentersoftcopy::where('is_active',1)->count();
        $data['softCopies'] = Salespresentersoftcopy::where('is_active',1)->get();
        $editList = Savelist::with('softcopies')->where('id',$id)->first();
        $savesoftcopy = [];
        foreach($editList->softcopies as $softcopy){
            $savesoftcopy[] = $softcopy['softcopy_id'];
        }
        $data['savesoftcopy'] = $savesoftcopy;
        $data['editList'] = $editList;
        return view('admin.suggestedsalespresenter.edit',$data);
    }

    public function updateListData(Request $request){
        $input = $request->all();
        $listUpdate = Savelist::where('id',$input['list_id'])->first();

        $list = Savelistsoftcopy::where('savelist_id',$input['list_id'])->delete();

        $softcopy_ids = array_unique($input['softcopy_id']);

        if(isset($softcopy_ids) && !empty($softcopy_ids)){
            foreach($softcopy_ids as $softcopy){
                $last_position = Savelistsoftcopy::max('position');
                $last_position++;
                $saveData = [
                    'savelist_id' => $input['list_id'],
                    'softcopy_id' => $softcopy,
                    'position' => $last_position
                ];
                $res = Savelistsoftcopy::create($saveData);
            }
        }

        if ($res){
            return redirect()->route('webadmin.suggestedSalespresentersoftcopy')->with('success','Sales presenters suggested list successfully updated.');
        }
    }

    public function deleteList($id){
        $list = Savelist::where('id',$id)->first();
        if (!$list){
            return redirect()->back()->with('error','Something went wrong, please try again later.');
        }
        $res = $list->delete();
        if ($res){
            return redirect()->back()->with('success','List remove successfully.');
        }
    }

    public function arrangeSaveList($id){
        $data['savelist'] = Savelist::where('id',$id)->first();
        $data['getSoftCopyList'] = Savelistsoftcopy::with('salespresenterssoftcopy')->where('savelist_id',$data['savelist']->id)->orderBy('position','asc')->get();
        return view('admin.suggestedsalespresenter.sort',$data);
    }

    public function updatePosition(Request $request){
        $savelistsoftcopy_ids = $request->all();
        foreach($savelistsoftcopy_ids['softcopy_id'] as $id){
            $last_position = Savelistsoftcopy::max('position');
            $last_position++;
            $savesoftcopy = Savelistsoftcopy::where('id',$id)->first();
            $saveData = [
                'position' => $last_position
            ];

            $positionUpdate = $savesoftcopy->update($saveData);
        }
        if($positionUpdate){
            return redirect()->route('webadmin.suggestedSalespresentersoftcopy')->with('success','Successfully updated your list.');
        }else{
            return redirect()->back()->with('error','Something is wrong. Please try again!');
        }
    }

}
