<?php

use App\Http\Controllers\StorageController;
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
Route::delete('delete', [StorageController::class, 'delete']);
