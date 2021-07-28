<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\LocationController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/residents', [ResidentController::class, 'getResidents']);
Route::post('/residents', [ResidentController::class, 'createResident']);
Route::put('/residents/{id}', [ResidentController::class, 'updateResident']);
Route::delete('/residents/{id}', [ResidentController::class, 'destroyResidents']);
Route::get('/rooms', [ResidentController::class, 'getRooms']);
Route::get('/beacons', [TagController::class, 'getBeacons']);


