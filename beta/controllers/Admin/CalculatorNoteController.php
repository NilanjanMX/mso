<?php

namespace App\Http\Controllers\Admin;

use App\Models\Calculator_note;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class CalculatorNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Calculator_note::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function($row){
                    return ucfirst(str_replace('_', ' ', $row->category));
                })
                ->addColumn('calculator', function($row){
                    return ucfirst(str_replace('_', ' ', $row->calculator));
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-notes', 'edit')){
                    $btn = '<a href="'.route('webadmin.note-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-notes', 'delete')){
                    $btn .= '<a href="'.route('webadmin.note-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name','calculator','category','action'])
                ->make(true);
        }
        return view('admin.notes.note');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notes.add_note');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'calculator' => 'required',
            'category' => 'required',
            'description' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'category' => $input['category'],
            'calculator' => $input['calculator'],
            'description' => $input['description']
        ];
    
        Calculator_note::create($saveData);

        toastr()->success('Note successfully saved.');
        return redirect()->route('webadmin.notes');


        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Calculator_note  $calculator_note
     * @return \Illuminate\Http\Response
     */
    public function show(Calculator_note $calculator_note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Calculator_note  $calculator_note
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['note'] = Calculator_note::where('id',$id)->first();
        return view('admin.notes.edit_note',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calculator_note  $calculator_note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'calculator' => 'required',
            'description' => 'required'
        ]);

        $questionBank = Calculator_note::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.notes');
        }

        $input = $request->all();
        //
        $saveData = [
            'name' => $input['name'],
            'category' => $input['category'],
            'calculator' => $input['calculator'],
            'description' => $input['description']
        ];

        $res = $questionBank->update($saveData);

        toastr()->success('Note successfully Updated.');
        return redirect()->route('webadmin.notes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Calculator_note  $calculator_note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = Calculator_note::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
    
        toastr()->success('Note successfully deleted.');
        return redirect()->route('webadmin.notes');
    }
}
