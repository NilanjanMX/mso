<?php

namespace App\Http\Controllers\Admin\Cron;

use App\Models\SchemecodeData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

class FundPerformanceCron extends Controller
{
    public function schemecodeData(){
        //Direct
        $directendpoint = "http://mf.accordwebservices.com/MF/GetSchemeReturnswithNAV_New?schemecode=&option=direct&token=kFgHuo5nbdJEg7ZfFyQMSngxi0z6WtVG";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $directendpoint);
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody());
        if (isset($content->Table) && count($content->Table)>0){
            $schemelist = $content->Table;
            foreach ($schemelist as $scheme){
                $scheme = (array)$scheme;
                $existScheme = SchemecodeData::where('schemecode',$scheme['Schemecode'])->first();
                if ($existScheme){
                    $existScheme->update([
                        'data' => json_encode($scheme)
                    ]);
                }else{
                    SchemecodeData::create([
                        'schemecode' => $scheme['Schemecode'],
                        'data' => json_encode($scheme)
                    ]);
                }
            }
        }

        //Regular
        $regularendpoint = "http://mf.accordwebservices.com/MF/GetSchemeReturnswithNAV_New?schemecode=&option=regular&token=kFgHuo5nbdJEg7ZfFyQMSngxi0z6WtVG";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $regularendpoint);
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody());
        if (isset($content->Table) && count($content->Table)>0){
            $schemelist = $content->Table;
            foreach ($schemelist as $scheme){
                $scheme = (array)$scheme;
                $existScheme = SchemecodeData::where('schemecode',$scheme['Schemecode'])->first();
                if ($existScheme){
                    $existScheme->update([
                        'data' => json_encode($scheme)
                    ]);
                }else{
                    SchemecodeData::create([
                        'schemecode' => $scheme['Schemecode'],
                        'data' => json_encode($scheme)
                    ]);
                }
            }
        }

    }

    public function testCronData()
    {
        \Log::info('start test corn');
        echo 'hello';
        \Log::info(date('Y-m-d H:i:s'));
        \Log::info('end test cron');
        // \Log::info('test log');
    }
}
