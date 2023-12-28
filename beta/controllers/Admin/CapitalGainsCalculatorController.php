<?php

namespace App\Http\Controllers\Admin;

use App\Models\CapitalGainsCalculator;
use App\Models\CapitalGainsTaxRate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CapitalGainsCalculatorController extends Controller
{
    public function index(Request $request){
        // dd('here');
        if ($request->ajax()) {
            $data = CapitalGainsCalculator::orderBy('index','asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-capital-gains-calculator', 'edit')){
                    $btn = '<a href="'.route('webadmin.CapitalGainsCalculatorEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-capital-gains-calculator', 'delete')){
                    $btn .= '<a href="'.route('webadmin.CapitalGainsCalculatorDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.capital_gains_tax_calculator.index');
    }

    public function add(){
        return view('admin.capital_gains_tax_calculator.add');
    }

    public function save(Request $request){
        $request->validate([
            'financial_year' => 'required',
            'index' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'financial_year' => $input['financial_year'],
            'index' => $input['index']
        ];

        $res = CapitalGainsCalculator::create($saveData);
        if ($res){
            toastr()->success('Capital Gains Calculator Index successfully saved.');
            return redirect()->route('webadmin.CapitalGainsCalculatorIndex');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['page'] = CapitalGainsCalculator::where('id',$id)->first();
        return view('admin.capital_gains_tax_calculator.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'financial_year' => 'required',
            'index' => 'required'
        ]);

        $previousPage = CapitalGainsCalculator::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.CapitalGainsCalculatorIndex');
        }

        $input = $request->all();
        $saveData = [
            'financial_year' => $input['financial_year'],
            'index' => $input['index']
        ];

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Capital Gains Calculator Index successfully updated.');
            return redirect()->route('webadmin.CapitalGainsCalculatorIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete($id){
        $previousPage = CapitalGainsCalculator::where('id',$id)->first();
        $res = $previousPage->delete();
        if ($res){
            toastr()->success('Capital Gains Calculator Index successfully deleted.');
            return redirect()->route('webadmin.CapitalGainsCalculatorIndex');
        }
        return redirect()->back()->withInput();
    }


    public function indexTaxRate(Request $request){
        // dd('here');
        if ($request->ajax()) {
            $data = CapitalGainsTaxRate::orderBy('id','asc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-capital-gains-tax-rate', 'edit')){
                    $btn = '<a href="'.route('webadmin.CapitalGainsTaxRateEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('calculator-capital-gains-tax-rate', 'delete')){
                    $btn .= '<a href="'.route('webadmin.CapitalGainsTaxRateDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.term_tax_rate.index');
    }

    public function addTaxRate(){
        return view('admin.term_tax_rate.add');
    }

    public function saveTaxRate(Request $request){
        $request->validate([
            'asset_class' => 'required',
            // 'conditions' => 'required',
            // 'years' => 'required',
            'term' => 'required'
        ]);

        $input = $request->all();
        // dd($input);
        $saveData = [
            'asset_class' => $input['asset_class'],
            // 'conditions' => $input['conditions'],
            // 'years' => $input['years'],
            'term' => $input['term'],
            'rate' => $input['rate']
        ];

        $res = CapitalGainsTaxRate::create($saveData);
        if ($res){
            toastr()->success('Capital Gains Tax successfully saved.');
            return redirect()->route('webadmin.CapitalGainsTaxRateIndex');
        }

        return redirect()->back()->withInput();
    }

    public function editTaxRate($id){
        $data['page'] = CapitalGainsTaxRate::where('id',$id)->first();
        return view('admin.term_tax_rate.edit',$data);
    }

    public function updateTaxRate(Request $request,$id){
        $request->validate([
            'asset_class' => 'required',
            'term' => 'required'
        ]);

        $previousPage = CapitalGainsTaxRate::where('id',$id)->first();
        if (!$previousPage){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.CapitalGainsTaxRateIndex');
        }

        $input = $request->all();
        $saveData = [
            'asset_class' => $input['asset_class'],
            'term' => $input['term'],
            'rate' => $input['rate']
        ];

        $res = $previousPage->update($saveData);
        if ($res){
            toastr()->success('Capital Gains Tax successfully updated.');
            return redirect()->route('webadmin.CapitalGainsTaxRateIndex');
        }

        return redirect()->back()->withInput();
    }

    public function deleteTaxRate($id){
        $previousPage = CapitalGainsTaxRate::where('id',$id)->first();
        $res = $previousPage->delete();
        if ($res){
            toastr()->success('Capital Gains Tax successfully deleted.');
            return redirect()->route('webadmin.CapitalGainsTaxRateIndex');
        }
        return redirect()->back()->withInput();
    }
}
