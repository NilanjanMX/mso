<?php

namespace App\Http\Controllers\Admin;

use App\Models\Autorenewal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;



class AutorenewalController extends Controller
{
    

    public function edit(){
        $data['autorenewal'] = Autorenewal::where('id',1)->first();
        return view('admin.autorenewal.edit',$data);
    }

    public function update(Request $request,$id){
        

        $previousArticle = Autorenewal::where('id',$id)->first();
        if (!$previousArticle){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.autorenewalEdit');
        }

        $input = $request->all();
        
        $saveData = [
            'autorenewal' => isset($input['autorenewal'])?1:0,
            'direct' => isset($input['direct'])?1:0,
            'icici' => isset($input['icici'])?1:0
        ];

        
        $res = $previousArticle->update($saveData);
        if ($res){
            toastr()->success('Autorenewal successfully updated.');
            return redirect()->route('webadmin.autorenewalEdit');
        }

        return redirect()->back()->withInput();
    }
    

}
