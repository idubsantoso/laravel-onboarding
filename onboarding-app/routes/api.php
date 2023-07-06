<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GetDataInvestController;
use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::apiResource('customers', CustomerController::class)->middleware(['auth:sanctum']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/products', GetDataInvestController::class);
Route::get('/call-helper', function(){

    $mdY = convertYmdToMdy('2022-02-12');
    var_dump("Converted into 'MDY': " . $mdY);

    $ymd = convertMdyToYmd('02-12-2022');
    var_dump("Converted into 'YMD': " . $ymd);
});
