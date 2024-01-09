<?php

namespace App\Http\Controllers;

use App\Models\Displayinfo;
use App\Models\Membership;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function getCurrentDate(){
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d")-1;
        if($day_date < 10){
            $day_date = "0".$day_date;
        }
        return $day_date."".$month_date."".$year_date;
    }

    public function getAccordData($file_name,$date,$section){
        $token = "41J3y6vo6MOY577GDHs41Dehsv2NZEzG";
        echo "<br>"; 
        echo $endpoint = "https://contentapi.accordwebservices.com/RawData/GetTxtFile?filename=".$file_name."&section=".$section."&sub=&token=".$token."&date=".$date;
        // exit;
        echo "<br>";
        echo "Date : ".$date."--- File Name : ".$file_name;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        // dd($response);
        $statusCode = $response->getStatusCode();
        if($statusCode == 200){
            $content = json_decode($response->getBody());
            if($content){
                echo " --- Count : ".count($content->Table);
                return $content->Table;
            }else{
                echo " --- Count : 0";
                return [];
            }
            
        }else{
            echo " --- Count : No data";
            return [];
        }
    }

    public function update_accord_data_1(){
        
        $date = $this->getCurrentDate();
        
        
        $amc_mst = $this->getAccordData('Amc_mst',$date,"MFMaster");
        if(count($amc_mst)){
            DB::table("accord_tables")->where('table_name','=','amc_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($amc_mst as $key => $value) {
                $amc_mst_detail = DB::table("accord_amc_mst")->where('amc_code','=',$value->amc_code);
                $insertData = (array)$value;
                if($amc_mst_detail){
                    DB::table("accord_amc_mst")->where('amc_code','=',$value->amc_code)->update($insertData);
                }else{
                    DB::table("accord_amc_mst")->insert($insertData);
                }
            }
        }
        exit;
    }

    public function update_accord_data_2(){
        $date = $this->getCurrentDate();
        
        $scheme_master = $this->getAccordData('Scheme_master',$date,"MFMaster");
        if(count($scheme_master)){
            DB::table("accord_tables")->where('table_name','=','scheme_master')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($scheme_master as $key => $value) {
                $amc_mst_detail = DB::table("accord_scheme_master")->where('schemecode','=',$value->schemecode);
                $insertData = (array)$value;
                if($amc_mst_detail){
                    DB::table("accord_scheme_master")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_scheme_master")->insert($insertData);
                }
            }
        }
        exit;
    }

    public function update_accord_data_3(){
        $date = $this->getCurrentDate();
        
        $scheme_details = $this->getAccordData('Scheme_details',$date,"MFMaster");
        if(count($scheme_details)){
            DB::table("accord_tables")->where('table_name','=','scheme_details')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($scheme_details as $key => $value) {
                $amc_mst_detail = DB::table("accord_scheme_details")->where('schemecode','=',$value->schemecode)->first();
                // dd($amc_mst_detail);
                echo "<br>".$key."--".$value->schemecode;
                $insertData = (array)$value;
                if($amc_mst_detail){
                    DB::table("accord_scheme_details")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_scheme_details")->insert($insertData);
                }
            }
        }
        exit;
    }

    public function update_accord_data_4(){
        
        $date = $this->getCurrentDate();
        
        $sclass_mst = $this->getAccordData('Sclass_mst',$date,"MFMaster");
        if(count($sclass_mst)){
            DB::table("accord_tables")->where('table_name','=','sclass_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($sclass_mst as $key => $value) {
                $amc_mst_detail = DB::table("accord_sclass_mst")->where('schemecode','=',$value->schemecode)->first();
                // dd($amc_mst_detail);
                $insertData = (array)$value;
                if($amc_mst_detail){
                    DB::table("accord_sclass_mst")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_sclass_mst")->insert($insertData);
                }
            }
        }
        exit;
    }

    public function update_accord_data_5(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Asect_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','asect_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_asect_mst")->where('asect_code','=',$value->asect_code)->first();
                // dd($amc_mst_detail);
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_asect_mst")->where('asect_code','=',$value->asect_code)->update($insertData);
                }else{
                    DB::table("accord_asect_mst")->insert($insertData);
                }
            }
        }
        exit;
    }

    public function update_accord_data_6(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('gsecmaster',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','gsecmaster')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_gsecmaster")->where('gsec_code','=',$value->gsec_code)->first();
                // dd($amc_mst_detail);
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_gsecmaster")->where('gsec_code','=',$value->gsec_code)->update($insertData);
                }else{
                    DB::table("accord_gsecmaster")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_7(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('cust_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','cust_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_cust_mst")->where('cust_code','=',$value->cust_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_cust_mst")->where('cust_code','=',$value->cust_code)->update($insertData);
                }else{
                    DB::table("accord_cust_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_8(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Option_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','option_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_option_mst")->where('opt_code','=',$value->opt_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_option_mst")->where('opt_code','=',$value->opt_code)->update($insertData);
                }else{
                    DB::table("accord_option_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_9(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Plan_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','plan_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_plan_mst")->where('plan_code','=',$value->plan_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_plan_mst")->where('plan_code','=',$value->plan_code)->update($insertData);
                }else{
                    DB::table("accord_plan_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_10(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Rt_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','rt_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_rt_mst")->where('Rt_code','=',$value->Rt_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_rt_mst")->where('Rt_code','=',$value->Rt_code)->update($insertData);
                }else{
                    DB::table("accord_rt_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_11(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Div_mst',$date,"MFMaster");
        
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','div_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_div_mst")->where('Div_code','=',$value->Div_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_div_mst")->where('Div_code','=',$value->Div_code)->update($insertData);
                }else{
                    DB::table("accord_div_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_12(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('sect_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','sect_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_sect_mst")->where('Sect_code','=',$value->Sect_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_sect_mst")->where('Sect_code','=',$value->Sect_code)->update($insertData);
                }else{
                    DB::table("accord_sect_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_13(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Type_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','type_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_type_mst")->where('type_code','=',$value->type_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_type_mst")->where('type_code','=',$value->type_code)->update($insertData);
                }else{
                    DB::table("accord_type_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_14(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('loadtype_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','loadtype_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_loadtype_mst")->where('ltypecode','=',$value->ltypecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_loadtype_mst")->where('ltypecode','=',$value->ltypecode)->update($insertData);
                }else{
                    DB::table("accord_loadtype_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_15(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('index_mst',$date,"MFMaster");
        // dd($list_data);
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','index_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_index_mst")->where('indexcode','=',$value->indexcode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_index_mst")->where('indexcode','=',$value->indexcode)->update($insertData);
                }else{
                    DB::table("accord_index_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_16(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Scheme_objective',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_objective')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_objective")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_objective")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_scheme_objective")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_17(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Amc_keypersons',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','amc_keypersons')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_amc_keypersons")->where('amc_code','=',$value->amc_code)->where('srno','=',$value->srno)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_amc_keypersons")->where('amc_code','=',$value->amc_code)->where('srno','=',$value->srno)->update($insertData);
                }else{
                    DB::table("accord_amc_keypersons")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_18(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('mf_sip',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_sip')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_sip")->where('schemecode','=',$value->schemecode)->where('frequency','=',$value->frequency)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_sip")->where('schemecode','=',$value->schemecode)->where('frequency','=',$value->frequency)->update($insertData);
                }else{
                    DB::table("accord_mf_sip")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_19(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('mf_swp',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_swp')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_swp")->where('schemecode','=',$value->schemecode)->where('frequency','=',$value->frequency)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_swp")->where('schemecode','=',$value->schemecode)->where('frequency','=',$value->frequency)->update($insertData);
                }else{
                    DB::table("accord_mf_swp")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_20(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('mf_stp',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_stp')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_stp")->where('schemecode','=',$value->schemecode)->where('frequency','=',$value->frequency)->where('stpinout','=',$value->stpinout)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_stp")->where('schemecode','=',$value->schemecode)->where('frequency','=',$value->frequency)->where('stpinout','=',$value->stpinout)->update($insertData);
                }else{
                    DB::table("accord_mf_stp")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_21(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Scheme_rtcode',$date,"MFMaster");
        if(count($list_data)){
            // dd($list_data);
            DB::table("accord_tables")->where('table_name','=','scheme_rtcode')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_rtcode")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_rtcode")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_scheme_rtcode")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_22(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('scheme_index_part',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_index_part')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_index_part")->where('SCHEMECODE','=',$value->SCHEMECODE)->where('INDEXCODE','=',$value->INDEXCODE)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_index_part")->where('SCHEMECODE','=',$value->SCHEMECODE)->where('INDEXCODE','=',$value->INDEXCODE)->update($insertData);
                }else{
                    DB::table("accord_scheme_index_part")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_23(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Companymaster',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','companymaster')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_companymaster")->where('fincode','=',$value->fincode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_companymaster")->where('fincode','=',$value->fincode)->update($insertData);
                }else{
                    DB::table("accord_companymaster")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_24(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Fundmanager_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','fundmanager_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_fundmanager_mst")->where('id','=',$value->id)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_fundmanager_mst")->where('id','=',$value->id)->update($insertData);
                }else{
                    DB::table("accord_fundmanager_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_25(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Schemeisinmaster',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','schemeisinmaster')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_schemeisinmaster")->where('ISIN','=',$value->ISIN)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_schemeisinmaster")->where('ISIN','=',$value->ISIN)->update($insertData);
                }else{
                    DB::table("accord_schemeisinmaster")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_26(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Scheme_rgess',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_rgess')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_rgess")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_rgess")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_scheme_rgess")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_27(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Industry_mst',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','industry_mst')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_industry_mst")->where('Ind_code','=',$value->Ind_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_industry_mst")->where('Ind_code','=',$value->Ind_code)->update($insertData);
                }else{
                    DB::table("accord_industry_mst")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_28(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Schemeload',$date,"MFMaster");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','schemeload')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_schemeload")->where('SCHEMECODE','=',$value->SCHEMECODE)->where('LDATE','=',$value->LDATE)->where('LTYPECODE','=',$value->LTYPECODE)->where('LSRNO','=',$value->LSRNO)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_schemeload")->where('SCHEMECODE','=',$value->SCHEMECODE)->where('LDATE','=',$value->LDATE)->where('LTYPECODE','=',$value->LTYPECODE)->where('LSRNO','=',$value->LSRNO)->update($insertData);
                }else{
                    DB::table("accord_schemeload")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_29(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Currentnav',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','currentnav')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_currentnav")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_currentnav")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_currentnav")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_30(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Divdetails',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','divdetails')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_divdetails")->where('schemecode','=',$value->schemecode)->where('recorddate','=',$value->recorddate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_divdetails")->where('schemecode','=',$value->schemecode)->where('recorddate','=',$value->recorddate)->update($insertData);
                }else{
                    DB::table("accord_divdetails")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_31(){
        
        $date = $this->getCurrentDate();
        
        $list_data = $this->getAccordData('Mf_portfolio',$date,"MFPortfolio");
        
        if(count($list_data)){
            echo "<br>";
            DB::table("accord_tables")->where('table_name','=','mf_portfolio')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                echo $key.", ";
                $detail = DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->update($insertData);
                }else{
                    DB::table("accord_mf_portfolio")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_311(){
        
        $date = $this->getCurrentDate();
        
        $list_data = $this->getAccordData('Mf_portfolio',$date,"MFPortfolio");
        
        if(count($list_data)){
            echo "<br>";
            DB::table("accord_tables")->where('table_name','=','mf_portfolio')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            $count = count($list_data);
            $count = ($count>=25000)?25000:$count;
            for($i=0; $i< $count;$i++){
                $value = $list_data[$i];
                echo $i.", ";
                $detail = DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->update($insertData);
                }else{
                    DB::table("accord_mf_portfolio")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_312(){
        
        $date = $this->getCurrentDate();
        
        $list_data = $this->getAccordData('Mf_portfolio',$date,"MFPortfolio");
        
        if(count($list_data)){
            echo "<br>";
            DB::table("accord_tables")->where('table_name','=','mf_portfolio')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            $count = count($list_data);
            if($count>25000){
                $count = ($count>=50000)?50000:$count;
                for($i=24999; $i< $count;$i++){
                    $value = $list_data[$i];
                    echo $i.", ";
                    $detail = DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->first();
                    $insertData = (array)$value;
                    if($detail){
                        DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->update($insertData);
                    }else{
                        DB::table("accord_mf_portfolio")->insert($insertData);
                    }
                }   
            }
        }
        exit;
    }
    
    public function update_accord_data_313(){
        
        $date = $this->getCurrentDate();
        
        $list_data = $this->getAccordData('Mf_portfolio',$date,"MFPortfolio");
        
        if(count($list_data)){
            echo "<br>";
            DB::table("accord_tables")->where('table_name','=','mf_portfolio')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            $count = count($list_data);
            if($count>50000){
                $count = ($count>=75000)?75000:$count;
                for($i=49999; $i< $count;$i++){
                    $value = $list_data[$i];
                    echo $i.", ";
                    $detail = DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->first();
                    $insertData = (array)$value;
                    if($detail){
                        DB::table("accord_mf_portfolio")->where('schemecode','=',$value->schemecode)->where('invdate','=',$value->invdate)->where('srno','=',$value->srno)->update($insertData);
                    }else{
                        DB::table("accord_mf_portfolio")->insert($insertData);
                    }
                }   
            }
        }
        exit;
    }
    
    public function update_accord_data_32(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('amc_aum',$date,"MFPortfolio");
        if(count($list_data)){
            // dd($list_data);
            DB::table("accord_tables")->where('table_name','=','amc_aum')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_amc_aum")->where('amc_code','=',$value->amc_code)->where('aumdate','=',$value->aumdate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_amc_aum")->where('amc_code','=',$value->amc_code)->where('aumdate','=',$value->aumdate)->update($insertData);
                }else{
                    DB::table("accord_amc_aum")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_33(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('scheme_aum',$date,"MFPortfolio");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_aum')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_aum")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_aum")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->update($insertData);
                }else{
                    DB::table("accord_scheme_aum")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_34(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Portfolio_inout',$date,"MFPortfolio");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','portfolio_inout')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_portfolio_inout")->where('fincode','=',$value->fincode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_portfolio_inout")->where('fincode','=',$value->fincode)->update($insertData);
                }else{
                    DB::table("accord_portfolio_inout")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_35(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('sect_allocation',$date,"MFPortfolio");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','sect_allocation')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_sect_allocation")->where('Amc_Code','=',$value->Amc_Code)->where('SchemeCode','=',$value->SchemeCode)->where('InvDate','=',$value->InvDate)->where('SECT_NAME','=',$value->SECT_NAME)->where('Asect_Code','=',$value->Asect_Code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_sect_allocation")->where('Amc_Code','=',$value->Amc_Code)->where('SchemeCode','=',$value->SchemeCode)->where('InvDate','=',$value->InvDate)->where('SECT_NAME','=',$value->SECT_NAME)->where('Asect_Code','=',$value->Asect_Code)->update($insertData);
                }else{
                    DB::table("accord_sect_allocation")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_36(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Navhist',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','navhist')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                echo "<br>".$key;
                $detail = DB::table("accord_navhist")->where('schemecode','=',$value->schemecode)->where('navdate','=',$value->navdate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_navhist")->where('schemecode','=',$value->schemecode)->where('navdate','=',$value->navdate)->update($insertData);
                }else{
                    DB::table("accord_navhist")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_37(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Mf_return',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_38(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('mf_abs_return',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_abs_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_abs_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_abs_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_abs_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_39(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('ClassWiseReturn',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','classwisereturn')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_classwisereturn")->where('classcode','=',$value->classcode)->where('opt_code','=',$value->opt_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_classwisereturn")->where('classcode','=',$value->classcode)->where('opt_code','=',$value->opt_code)->update($insertData);
                }else{
                    DB::table("accord_classwisereturn")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_40(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('mf_ans_return',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_ans_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_ans_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_ans_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_ans_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_41(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Navhist_HL',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','navhist_hl')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_navhist_hl")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_navhist_hl")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_navhist_hl")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_42(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Amc_paum',$date,"MFPortfolio");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','amc_paum')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_amc_paum")->where('amc_code','=',$value->amc_code)->where('aumdate','=',$value->aumdate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_amc_paum")->where('amc_code','=',$value->amc_code)->where('aumdate','=',$value->aumdate)->update($insertData);
                }else{
                    DB::table("accord_amc_paum")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_43(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Scheme_paum',$date,"MFPortfolio");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_paum')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_paum")->where('schemecode','=',$value->schemecode)->where('monthend','=',$value->monthend)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_paum")->where('schemecode','=',$value->schemecode)->where('monthend','=',$value->monthend)->update($insertData);
                }else{
                    DB::table("accord_scheme_paum")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_44(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('mf_ratio',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_ratio')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_ratio")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_ratio")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_ratio")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_45(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('MF_Ratios_DefaultBM',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_ratios_defaultbm')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_ratios_defaultbm")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_ratios_defaultbm")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_ratios_defaultbm")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_46(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('BM_AbsoluteReturn',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','bm_absolutereturn')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_bm_absolutereturn")->where('index_code','=',$value->index_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_bm_absolutereturn")->where('index_code','=',$value->index_code)->update($insertData);
                }else{
                    DB::table("accord_bm_absolutereturn")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_47(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Avg_scheme_aum',$date,"MFPortfolio");
        if(count($list_data)){
            // dd($list_data);
            DB::table("accord_tables")->where('table_name','=','avg_scheme_aum')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_avg_scheme_aum")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_avg_scheme_aum")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->update($insertData);
                }else{
                    DB::table("accord_avg_scheme_aum")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_48(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('BM_AnnualisedReturn',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','bm_annualisedreturn')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_bm_annualisedreturn")->where('index_code','=',$value->index_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_bm_annualisedreturn")->where('index_code','=',$value->index_code)->update($insertData);
                }else{
                    DB::table("accord_bm_annualisedreturn")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_49(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Expenceratio',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','expenceratio')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_expenceratio")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_expenceratio")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->update($insertData);
                }else{
                    DB::table("accord_expenceratio")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_50(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('scheme_eq_details',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_eq_details')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_eq_details")->where('SchemeCode','=',$value->SchemeCode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_eq_details")->where('SchemeCode','=',$value->SchemeCode)->update($insertData);
                }else{
                    DB::table("accord_scheme_eq_details")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_51(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('fmp_yielddetails',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','fmp_yielddetails')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_fmp_yielddetails")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_fmp_yielddetails")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_fmp_yielddetails")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_52(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('CompanyMcap',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','fmp_yielddetails')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_companymcap")->where('fincode','=',$value->fincode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_companymcap")->where('fincode','=',$value->fincode)->update($insertData);
                }else{
                    DB::table("accord_companymcap")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_53(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Avg_maturity',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','avg_maturity')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_avg_maturity")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_avg_maturity")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->update($insertData);
                }else{
                    DB::table("accord_avg_maturity")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_54(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Fvchange',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','fvchange')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_fvchange")->where('schemecode','=',$value->schemecode)->where('fvdate','=',$value->fvdate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_fvchange")->where('schemecode','=',$value->schemecode)->where('fvdate','=',$value->fvdate)->update($insertData);
                }else{
                    DB::table("accord_fvchange")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_55(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('DailyFundmanager',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','dailyfundmanage')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_dailyfundmanager")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_dailyfundmanager")->where('schemecode','=',$value->schemecode)->where('date','=',$value->date)->update($insertData);
                }else{
                    DB::table("accord_dailyfundmanager")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_56(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Mergedschemes',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mergedschemes')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mergedschemes")->where('schemecode','=',$value->schemecode)->where('mergedwith','=',$value->mergedwith)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mergedschemes")->where('schemecode','=',$value->schemecode)->where('mergedwith','=',$value->mergedwith)->update($insertData);
                }else{
                    DB::table("accord_mergedschemes")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_57(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('MFBULKDEALS',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mfbulkdeals')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mfbulkdeals")->where('fincode','=',$value->fincode)->where('date','=',$value->date)->where('clientname','=',$value->clientname)->where('dealtype','=',$value->dealtype)->where('volume','=',$value->volume)->where('price','=',$value->price)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mfbulkdeals")->where('fincode','=',$value->fincode)->where('date','=',$value->date)->where('clientname','=',$value->clientname)->where('dealtype','=',$value->dealtype)->where('volume','=',$value->volume)->where('price','=',$value->price)->update($insertData);
                }else{
                    DB::table("accord_mfbulkdeals")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_58(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Scheme_assetalloc',$date,"MFOther");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','scheme_assetalloc')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_assetalloc")->where('schemecode','=',$value->schemecode)->where('investment','=',$value->investment)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_assetalloc")->where('schemecode','=',$value->schemecode)->where('investment','=',$value->investment)->update($insertData);
                }else{
                    DB::table("accord_scheme_assetalloc")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_59(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Scheme_Name_Change',$date,"MFOther");
        if(count($list_data)){
            // dd($list_data);
            // DB::table("accord_tables")->where('table_name','=','scheme_name_change')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_scheme_name_change")->where('Amc_Code','=',$value->Amc_Code)->where('Schemecode','=',$value->Schemecode)->where('Effectivedate','=',$value->Effectivedate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_scheme_name_change")->where('Amc_Code','=',$value->Amc_Code)->where('Schemecode','=',$value->Schemecode)->where('Effectivedate','=',$value->Effectivedate)->update($insertData);
                }else{
                    DB::table("accord_scheme_name_change")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_60(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Extra_Return',$date,"MFExtra");
        // dd($list_data);
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','extra_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_extra_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_extra_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_extra_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_61(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Extra_ANS_Return',$date,"MFExtra");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','extra_ans_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_extra_ans_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_extra_ans_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_extra_ans_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_62(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('Extra_ABS_Return',$date,"MFExtra");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','extra_abs_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_extra_abs_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_extra_abs_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_extra_abs_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_63(){
        
        $date = $this->getCurrentDate();

        $list_data = $this->getAccordData('INDEX_EXTRA_RETURNS',$date,"MFExtra");
        // dd($list_data);
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','index_extra_returns')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_index_extra_returns")->where('INDEX_CODE','=',$value->INDEX_CODE)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_index_extra_returns")->where('INDEX_CODE','=',$value->INDEX_CODE)->update($insertData);
                }else{
                    DB::table("accord_index_extra_returns")->insert($insertData);
                }
            }
        }
        
        DB::table("mf_scanner_cron")->where("id","=",1)->update(['page_number'=>0,'status'=>1]);
        exit;
    }
    
    public function update_accord_data_64(){
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('Navhist',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','navhist')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_navhist")->where('schemecode','=',$value->schemecode)->where('navdate','=',$value->navdate)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_navhist")->where('schemecode','=',$value->schemecode)->where('navdate','=',$value->navdate)->update($insertData);
                }else{
                    DB::table("accord_navhist")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_65(){
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('Mf_return',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_66(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('mf_abs_return',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_abs_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_abs_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_abs_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_abs_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_67(){
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('ClassWiseReturn',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','classwisereturn')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_classwisereturn")->where('classcode','=',$value->classcode)->where('opt_code','=',$value->opt_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_classwisereturn")->where('classcode','=',$value->classcode)->where('opt_code','=',$value->opt_code)->update($insertData);
                }else{
                    DB::table("accord_classwisereturn")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_68(){
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('mf_ans_return',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','mf_ans_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_mf_ans_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_mf_ans_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_mf_ans_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_69(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('Currentnav',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','currentnav')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_currentnav")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_currentnav")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_currentnav")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_70(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('BM_AnnualisedReturn',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','bm_annualisedreturn')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_bm_annualisedreturn")->where('index_code','=',$value->index_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_bm_annualisedreturn")->where('index_code','=',$value->index_code)->update($insertData);
                }else{
                    DB::table("accord_bm_annualisedreturn")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_71(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('BM_AbsoluteReturn',$date,"MFNav");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','bm_absolutereturn')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_bm_absolutereturn")->where('index_code','=',$value->index_code)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_bm_absolutereturn")->where('index_code','=',$value->index_code)->update($insertData);
                }else{
                    DB::table("accord_bm_absolutereturn")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_72(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('Extra_Return',$date,"MFExtra");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','extra_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_extra_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_extra_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_extra_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_73(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('Extra_ANS_Return',$date,"MFExtra");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','extra_ans_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_extra_ans_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_extra_ans_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_extra_ans_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_74(){
        
        $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d")-1;
        $date = $day_date."".$month_date."".$year_date;

        $list_data = $this->getAccordData('Extra_ABS_Return',$date,"MFExtra");
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','extra_abs_return')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_extra_abs_return")->where('schemecode','=',$value->schemecode)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_extra_abs_return")->where('schemecode','=',$value->schemecode)->update($insertData);
                }else{
                    DB::table("accord_extra_abs_return")->insert($insertData);
                }
            }
        }
        exit;
    }
    
    public function update_accord_data_75(){
        
        // echo "ok"; exit;
         $year_date = date("Y");
        $month_date = date("m");
        $day_date = date("d");
        $date = $day_date."".$month_date."".$year_date;
        // exit;

        $list_data = $this->getAccordData('INDEX_EXTRA_RETURNS',$date,"MFExtra");
        // dd($list_data);
        if(count($list_data)){
            DB::table("accord_tables")->where('table_name','=','index_extra_returns')->update(["is_updated"=>"1","last_updated_date"=>date('Y-m-d H:i:s')]);
            foreach ($list_data as $key => $value) {
                $detail = DB::table("accord_index_extra_returns")->where('INDEX_CODE','=',$value->INDEX_CODE)->first();
                $insertData = (array)$value;
                if($detail){
                    DB::table("accord_index_extra_returns")->where('INDEX_CODE','=',$value->INDEX_CODE)->update($insertData);
                }else{
                    DB::table("accord_index_extra_returns")->insert($insertData);
                }
            }
        }
        
        DB::table("mf_scanner_cron")->where("id","=",1)->update(['page_number'=>0,'status'=>1]);
        exit;
    }





}
