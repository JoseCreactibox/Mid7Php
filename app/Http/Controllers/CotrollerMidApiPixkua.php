<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;

class CotrollerMidApiPixkua extends Controller  
{
    //VAL PARA RETORNAR COMO RESPUESTA 
    public $UrlApi;
    // 
    public function __construct(){
        // 
        $this->UrlApi = 'https://apiapp.pixkua.com/'; 
    }
    //  
    public function  RequestGet(Request $request, $Url){
        //CREO UN NUEVO RECURSO DE cURL  
        $curl = curl_init();
        //
        $UrlApi = $this->UrlApi.$Url;
        //
        $Token = $request->header('Authorization');
        //
        $response = Http::withHeaders([
            'Authorization'    =>  (is_null($Token)?'':$Token)
        ])->get($UrlApi); 
        //
        $CodeHTTP = $response->getStatusCode(); 
        //
        $data = null; 
        //
        if(in_array($CodeHTTP , [200, 400])){ 
            //
            $data = $response->json(); 
        }
        //
        if($CodeHTTP > 499 OR $CodeHTTP == 404){
            //
            $msg = 'ERROR NOT REGISTER';
            //
            switch ($CodeHTTP) {
                case 500:
                    //
                    $msg = 'ERROR EN SERVER';
                    break;
                case 404:
                    //
                    $msg = 'PAGE NOT FOUND';
                    break;
            }
            //
            return response()->json($msg, $CodeHTTP);
        }
        if($CodeHTTP == 0){
            //
            return response()->json('DATA NOT FOUND',404);
        }
        //
        return response()->json($data, $CodeHTTP);     
    }
    //  
    public function  RequestPost(Request $request, $Url){
        //CREO UN NUEVO RECURSO DE cURL  
        $UrlApi = $this->UrlApi.$Url;
        //
        $Token = $request->header('Authorization');
        //
        $data = [];
        //
        $response = Http::withHeaders([
            'Authorization'    =>  (is_null($Token)?'':$Token)
        ]);
        //
        $ExistFiles = false;
        //
        foreach($request->all() as $key => $val){
            //
            if($request->hasFile($key)){
                //
                if(gettype($request[$key]) == 'object'){
                    //
                    $AuxFile = $request->file($key);   
                    //
                    $response = $response->attach($key, $AuxFile->get(), $AuxFile->getClientOriginalName()); 
                    //
                    $ExistFiles = true;
                }
                else{
                    //
                    foreach($request[$key] as $keyF => $valF){ 
                        //
                        $AuxFile = $valF;   
                        //
                        $response = $response->attach($key.'['.$keyF.']', $AuxFile->get(), $AuxFile->getClientOriginalName()); 
                        // 
                        $ExistFiles = true; 
                    }
                }
            }
            else{ 
                //
                $data[] = [
                    'name'     => $key, 
                    'contents' => (gettype($val) == 'array')?'':$val
                ];
                //
                if(gettype($val) == 'array'){ 
                    //
                    foreach($val as $key2 => $val2){
                        //
                        if(gettype($val2) == 'object' AND is_file($val2)){
                            //
                            $response = $response->attach($key.'['.$key2.']', $val2->get(), $val2->getClientOriginalName()); 
                            //
                            $ExistFiles = true; 
                        }
                        else{
                            //
                            $data[] = [
                                'name'     => $key.'['.$key2.']', 
                                'contents' => (gettype($val2) == 'array')?'':$val2,
                            ];
                            //
                            if(gettype($val2) == 'array'){
                                //
                                foreach($val2 as $key3 => $val3){
                                    //
                                    if(gettype($val3) == 'object' AND is_file($val3)){ 
                                        //
                                        $response = $response->attach($key.'['.$key2.']'.'['.$key3.']', $val3->get(), $val3->getClientOriginalName()); 
                                        //
                                        $ExistFiles = true; 
                                    }
                                    else if(gettype($val3) == 'array'){
                                        //
                                        foreach($val3 as $key4 => $val4){
                                            //
                                            if(gettype($val4) == 'object' AND is_file($val4)){  
                                                //
                                                $response = $response->attach($key.'['.$key2.']'.'['.$key3.']'.'['.$key4.']', $val4->get(), $val4->getClientOriginalName()); 
                                                //
                                                $ExistFiles = true; 
                                            }
                                            else{
                                                //
                                                $data[] = [
                                                    'name'     => $key.'['.$key2.']'.'['.$key3.']'.'['.$key4.']', 
                                                    'contents' => $val4,  
                                                ];
                                            }
                                        }
                                    }
                                    else{
                                        //
                                        $data[] = [
                                            'name'     => $key.'['.$key2.']'.'['.$key3.']', 
                                            'contents' => $val3,  
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
        //
        if(!$ExistFiles){
            //
            $data = $request->all();  
        }
        // 
        if($Url == 'Api/Usuario/Autentificacion'){
            //
            $data['IP'] = $request->ip();
            $data['UserAgent'] = $request->header('User-Agent');
        }
        // 
        $response  = $response->post($UrlApi, $data);
        $CodeHTTP  = $response->getStatusCode();
        //
        $data = null;
        //
        if(in_array($CodeHTTP , [200, 400])){ 
            //
            $data = $response->json();  
        }
        //
        if($CodeHTTP > 499 OR $CodeHTTP == 404){
            //
            $msg = 'ERROR NOT REGISTER';
            //
            switch ($CodeHTTP) {
                case 500:
                    //
                    $msg = 'ERROR EN SERVER';
                    break;
                case 404:
                    //
                    $msg = 'PAGE NOT FOUND';
                    break;
            }
            //
            return response()->json($msg, $CodeHTTP);
        }
        if($CodeHTTP == 0){
            //
            return response()->json('DATA NOT FOUND',404);
        } 
        //
        return response()->json($data, $CodeHTTP);     
    }
    //  
    public function  RequestPut(Request $request, $Url){
        //CREO UN NUEVO RECURSO DE cURL  
        $UrlApi = $this->UrlApi.$Url;
        //
        $Token = $request->header('Authorization');
        //
        $data = [];
        //
        $response = Http::withHeaders([
            'Authorization'    =>  (is_null($Token)?'':$Token)
        ]);
        //
        $ExistFiles = false;
        //
        $SendMetPost = false;
        //
        foreach($request->all() as $key => $val){
            //
            if('_method' == $key){
                //
                $SendMetPost = true;
            }
            //
            if($request->hasFile($key)){
                //
                if(gettype($request[$key]) == 'object'){
                    //
                    $AuxFile = $request->file($key);   
                    //
                    $response = $response->attach($key, $AuxFile->get(), $AuxFile->getClientOriginalName()); 
                    //
                    $ExistFiles = true;
                }
                else{
                    //
                    foreach($request[$key] as $keyF => $valF){ 
                        //
                        $AuxFile = $valF;   
                        //
                        $response = $response->attach($key.'['.$keyF.']', $AuxFile->get(), $AuxFile->getClientOriginalName()); 
                        // 
                        $ExistFiles = true; 
                    }
                }
            }
            else{ 
                //
                $data[] = [
                    'name'     => $key, 
                    'contents' => (gettype($val) == 'array')?'':$val
                ];
                //
                if(gettype($val) == 'array'){ 
                    //
                    foreach($val as $key2 => $val2){
                        //
                        if(gettype($val2) == 'object' AND is_file($val2)){
                            //
                            $response = $response->attach($key.'['.$key2.']', $val2->get(), $val2->getClientOriginalName()); 
                            //
                            $ExistFiles = true; 
                        }
                        else{
                            //
                            $data[] = [
                                'name'     => $key.'['.$key2.']', 
                                'contents' => (gettype($val2) == 'array')?'':$val2,
                            ];
                            //
                            if(gettype($val2) == 'array'){
                                //
                                foreach($val2 as $key3 => $val3){
                                    //
                                    if(gettype($val3) == 'object' AND is_file($val3)){ 
                                        //
                                        $response = $response->attach($key.'['.$key2.']'.'['.$key3.']', $val3->get(), $val3->getClientOriginalName()); 
                                        //
                                        $ExistFiles = true; 
                                    }
                                    else if(gettype($val3) == 'array'){
                                        //
                                        foreach($val3 as $key4 => $val4){
                                            //
                                            if(gettype($val4) == 'object' AND is_file($val4)){  
                                                //
                                                $response = $response->attach($key.'['.$key2.']'.'['.$key3.']'.'['.$key4.']', $val4->get(), $val4->getClientOriginalName()); 
                                                //
                                                $ExistFiles = true; 
                                            }
                                            else{
                                                //
                                                $data[] = [
                                                    'name'     => $key.'['.$key2.']'.'['.$key3.']'.'['.$key4.']', 
                                                    'contents' => $val4,  
                                                ];
                                            }
                                        }
                                    }
                                    else{
                                        //
                                        $data[] = [
                                            'name'     => $key.'['.$key2.']'.'['.$key3.']', 
                                            'contents' => $val3,  
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
        //
        if(!$ExistFiles){
            //
            $data = $request->all();  
        }
        // 
        $response  = ($SendMetPost)?$response->post($UrlApi, $data):$response->put($UrlApi, $data); 
        $CodeHTTP  = $response->getStatusCode();
        //
        $data = null;
        //
        if(in_array($CodeHTTP , [200, 400])){ 
            //
            $data = $response->json();  
        }
        //
        if($CodeHTTP > 499 OR $CodeHTTP == 404){
            //
            $msg = 'ERROR NOT REGISTER';
            //
            switch ($CodeHTTP) {
                case 500:
                    //
                    $msg = 'ERROR EN SERVER';
                    break;
                case 404:
                    //
                    $msg = 'PAGE NOT FOUND';
                    break;
            }
            //
            return response()->json($msg, $CodeHTTP);
        }
        if($CodeHTTP == 0){
            //
            return response()->json('DATA NOT FOUND',404);
        } 
        //
        return response()->json($data, $CodeHTTP);     
    }
    //   
    public function  RequestDelete(Request $request, $Url){
        //CREO UN NUEVO RECURSO DE cURL  
        $curl = curl_init();
        //
        $UrlApi = $this->UrlApi.$Url; 
        //
        $Token = $request->header('Authorization');
        //
        $response = Http::withHeaders([ 
            'Authorization'    =>  (is_null($Token)?'':$Token)
        ])->delete($UrlApi); 
        //
        $CodeHTTP = $response->getStatusCode();  
        //
        $data = null;
        //
        if(in_array($CodeHTTP , [200, 400])){  
            //
            $data = $response->json(); 
        }
        //
        if($CodeHTTP > 499 OR $CodeHTTP == 404){
            //
            $msg = 'ERROR NOT REGISTER';
            //
            switch ($CodeHTTP) {
                case 500:
                    //
                    $msg = 'ERROR EN SERVER';
                    break;
                case 404:
                    //
                    $msg = 'PAGE NOT FOUND';
                    break;
            }
            //
            return response()->json($msg, $CodeHTTP);
        }
        //
        if($CodeHTTP == 0){
            //
            return response()->json('DATA NOT FOUND',404);
        }
        //
        return response()->json($data, $CodeHTTP);     
    }
}
