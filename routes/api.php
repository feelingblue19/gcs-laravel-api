<?php

use App\Http\Controllers\StorageController;
use App\Http\Controllers\StoragePortalController;
use App\Http\Controllers\StorageIRKController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('', function () {
    return response()->json('Test', 200);
});

Route::post('upload', [StorageController::class, 'upload']);
Route::get('download', [StorageController::class, 'download']);
Route::get('delete', [StorageController::class, 'delete']);

//added by andrew bucket portal
Route::post('uploadPortal', [StoragePortalController::class, 'upload']);
Route::post('downloadPortal', [StoragePortalController::class, 'download']);
Route::post('deletePortal', [StoragePortalController::class, 'delete']);
Route::post('generateUrlPortal', [StoragePortalController::class, 'generateUrlFile']);

//end

//add by bima - irk

Route::group(['prefix' => 'irk'], function () {
    Route::post('upload', [StorageIRKController::class, 'upload']);
    Route::get('download', [StorageIRKController::class, 'download']);
    Route::post('downloadjoss', [StorageIRKController::class, 'downloadneww']);
    Route::get('delete', [StorageIRKController::class, 'delete']);
});

//end
