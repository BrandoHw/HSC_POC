<?php

use App\Building;
use App\Http\Controllers\AwsController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BuildingsManagmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyReaderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\GatewayZoneController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TagDataLogController;
use App\Http\Controllers\TimeblockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLastSeenController;
use App\Http\Controllers\PolicyController;
use App\UserLastSeen;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//use Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function() { 
    
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    Route::resource('dashboard', DashboardController::class);

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('buildings', BuildingController::class);
    Route::resource('floors', FloorController::class);

    Route::resource('beacons', TagController::class);
    Route::resource('gateways', ReaderController::class);

    Route::resource('map', MapController::class);
    Route::resource('policy', PolicyController::class);

    Route::resource('zones', GatewayZoneController::class);
    Route::post('gateway-zones/destroy', [GatewayZoneController::class, 'destroyAjax'])->name('zone.destroy');
    Route::post('gateway-zones/update', [GatewayZoneController::class, 'updateAjax'])->name('zone.update');
    Route::resource('user-position', UserLastSeenController::class);
    Route::get('user/get', [UserLastSeenController::class, 'get']);

});