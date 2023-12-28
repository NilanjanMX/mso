<?php

namespace App\Http\Controllers\Admin\Asset_allocation_exam;

use App\Models\Asset_allocation_exam\Asset_allocation_product;
use App\Models\Asset_allocation_exam\Asset_allocation_product_more;
use App\Models\Asset_allocation_exam\Asset_allocation_product_option;
use App\Models\Asset_allocation_exam\Product_balance_sheet;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class AssetAllocationProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $data = Asset_allocation_product::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('asset-allocation-asset-allocation-products', 'edit')){
                    $btn = '<a href="'.route('webadmin.asset-allocation-product-edit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('asset-allocation-asset-allocation-products', 'delete')){
                    $btn .= '<a href="'.route('webadmin.asset-allocation-product-destroy',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['name','action'])
                ->make(true);
        }
        return view('admin.asset_allocation_exam.product');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $endpoint = "http://mf.accordwebservices.com/MF/GetAssetType?token=kFgHuo5nbdJEg7ZfFyQMSngxi0z6WtVG";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $data['assetTypes'] = json_decode($response->getBody());
        return view('admin.asset_allocation_exam.add_product',$data);
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
            'totalOption' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'asset_order' => $input['asset_order'],
            'name' => $input['name'],
            'disclaimer' => $input['disclaimer'],
            'category' => $input['category'],
            'total_option' => $input['totalOption']
        ];
    
        $res = Asset_allocation_product::create($saveData);

        if ($res){

            if(isset($input['option_name']) && !empty($input['option_name'][0]))
            {
                $option_name_count=count($input['option_name']);
                for ($i=0;$i<$option_name_count;$i++){
                    $savOptnew = [
                        'option_name' => $input['option_name'][$i],
                        'asset_allocation_product_id' => $res['id']
                    ];
                    Asset_allocation_product_option::create($savOptnew);
                }

            }

            if(isset($input['opening_balance']) && !empty($input['opening_balance'][0]))
            {
                $opening_balance=count($input['opening_balance']);
                for ($i=0;$i<$opening_balance;$i++){
                    $savOptnew2 = [
                        'opening_balance' => $input['opening_balance'][$i],
                        'closing_balance' => $input['closing_balance'][$i],
                        'year' => $input['year'][$i],
                        'asset_allocation_product_id' => $res['id']
                    ];
                    Product_balance_sheet::create($savOptnew2);
                }

            }

            if (isset($input['totalOption']) && $input['totalOption']>0){
                for ($i=0;$i<$input['totalOption'];$i++){
                    $savOpt = [
                        'serial_no' => $input['serial_no'][$i],
                        'gross' => $input['gross'][$i],
                        'date' => $input['date'][$i],
                        'asset_allocation_product_id' => $res['id']
                    ];
                    Asset_allocation_product_more::create($savOpt);
                }
            }
            toastr()->success('Product successfully saved.');
            return redirect()->route('webadmin.asset-allocation-products');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asset_allocation_product  $asset_allocation_product
     * @return \Illuminate\Http\Response
     */
    public function show(Asset_allocation_product $asset_allocation_product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asset_allocation_product  $asset_allocation_product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['product'] = Asset_allocation_product::where('id',$id)->first();
        $endpoint = "http://mf.accordwebservices.com/MF/GetAssetType?token=kFgHuo5nbdJEg7ZfFyQMSngxi0z6WtVG";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $data['assetTypes'] = json_decode($response->getBody());
        return view('admin.asset_allocation_exam.edit_product',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asset_allocation_product  $asset_allocation_product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'totalOption' => 'required',
        ]);

        $questionBank = Asset_allocation_product::where('id',$id)->first();
        if (!$questionBank){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.asset-allocation-products');
        }

        $input = $request->all();

        if(isset($input['serial_no']))
        {
            $total_serial_no=count($input['serial_no']);
        }else{
            $total_serial_no=0;
        }

        $saveData = [
            'asset_order' => $input['asset_order'],
            'name' => $input['name'],
            'disclaimer' => $input['disclaimer'],
            'category' => $input['category'],
            'total_option' => $total_serial_no,
        ];


        $res = $questionBank->update($saveData);

        if ($res){
            $delExtOpt = Asset_allocation_product_more::where('asset_allocation_product_id',$id);
            if ($delExtOpt){
                $delExtOpt->delete();
            }

            $delExtOptnew = Asset_allocation_product_option::where('asset_allocation_product_id',$id);
            if ($delExtOptnew){
                $delExtOptnew->delete();
            }

            $delExtOptnew2 = Product_balance_sheet::where('asset_allocation_product_id',$id);
            if ($delExtOptnew2){
                $delExtOptnew2->delete();
            }

            if(isset($input['option_name']) && !empty($input['option_name'][0]))
            {
                $option_name_count=count($input['option_name']);
                for ($i=0;$i<$option_name_count;$i++){
                    $savOptnew = [
                        'option_name' => $input['option_name'][$i],
                        'asset_allocation_product_id' => $id
                    ];
                    Asset_allocation_product_option::create($savOptnew);
                }

            }

            if(isset($input['opening_balance']) && !empty($input['opening_balance'][0]))
            {
                $opening_balance=count($input['opening_balance']);
                for ($i=0;$i<$opening_balance;$i++){
                    $savOptnew2 = [
                        'opening_balance' => $input['opening_balance'][$i],
                        'closing_balance' => $input['closing_balance'][$i],
                        'year' => $input['year'][$i],
                        'asset_allocation_product_id' => $id
                    ];
                    Product_balance_sheet::create($savOptnew2);
                }

            }

            if (isset($input['serial_no']) && !empty($input['serial_no'][0])){
                
                for ($i=0;$i<count($input['serial_no']);$i++){
                    if(empty($input['ans_mark'][$i]))
                    {
                        $myan='0';
                    }else{
                        $myan=$input['ans_mark'][$i];
                    }
                    $savOpt = [
                        'serial_no' => $input['serial_no'][$i],
                        'gross' => $input['gross'][$i],
                        'date' => $input['date'][$i],
                        'asset_allocation_product_id' => $id
                    ];
                    Asset_allocation_product_more::create($savOpt);
                }
            }
           // return $input;

            toastr()->success('Product successfully updated.');
            return redirect()->route('webadmin.asset-allocation-products');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asset_allocation_product  $asset_allocation_product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delExtOpt = Asset_allocation_product::where('id',$id);
        if ($delExtOpt){
            $delExtOpt->delete();
        }
        $delExtOptAns = Asset_allocation_product_more::where('asset_allocation_product_id',$id);
        if ($delExtOptAns){
            $delExtOptAns->delete();
        }
        toastr()->success('Product successfully deleted.');
        return redirect()->route('webadmin.asset-allocation-products');
    }
}
