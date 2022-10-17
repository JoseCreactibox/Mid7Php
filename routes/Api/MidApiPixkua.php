<?php

use App\Http\Controllers\CotrollerMidApiPixkua as CtrlMid;

Route::get('{any}',[CtrlMid::class, 'RequestGet'])->where('any', '.*');
Route::post('{any}',[CtrlMid::class, 'RequestPost'])->where('any', '.*');
Route::put('{any}',[CtrlMid::class, 'RequestPut'])->where('any', '.*');
Route::delete('{any}',[CtrlMid::class, 'RequestDelete'])->where('any', '.*');  
 