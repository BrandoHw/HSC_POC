<?php

use App\Building;
use App\Http\Controllers\AwsController;
use App\Http\Controllers\AttendanceController;
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
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LocationController;

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
    Route::resource('users', UserController::class)
        ->except(['show']);

    Route::resource('buildings', BuildingController::class);
    Route::resource('floors', FloorController::class);
    Route::get('floors/destroy/{id}', [FloorController::class, 'destroyHref'])->name('floor.destroy');
    // Route::resource('beacons', TagController::class);
    // Route::resource('gateways', ReaderController::class);

    Route::resource('beacons', TagController::class)
        ->parameters(['beacons' => 'tag'])
        ->except(['show']);

    Route::resource('gateways', ReaderController::class)->parameters(['gateways' => 'reader']);

    Route::resource('map', MapController::class);
    Route::resource('locations', LocationController::class);
    Route::post('location/delete', [LocationController::class, 'delete'])->name('locations.delete');
    // Route::get('map/users/list', [MapController::class, 'listdata'])->name('map.listdata');
    Route::get('map/form/data', [MapController::class, 'formdata'])->name('map.formdata');
    Route::get('map/form/location', [MapController::class, 'locationdata'])->name('map.location');
    Route::get('map/view/dashboard', [MapController::class, 'dashboard'])->name('map.dashboard');
    Route::resource('policy', PolicyController::class);

    Route::resource('zones', GatewayZoneController::class);
    Route::post('gateway-zones/destroy', [GatewayZoneController::class, 'destroyAjax'])->name('zone.destroy');
    Route::post('gateway-zones/update', [GatewayZoneController::class, 'updateAjax'])->name('zone.update');
    Route::resource('user-position', UserLastSeenController::class);
    Route::get('user/get', [UserLastSeenController::class, 'get']);
    Route::get('user/group', [UserLastSeenController::class, 'group']);
    
    Route::resource('residents', ResidentController::class)
        ->only(['index', 'edit', 'update']);
    
    Route::resource('attendance', AttendanceController::class)
        ->only(['index', 'show']);

    Route::resource('policies', PolicyController::class)
        ->name('*', 'policies');
    Route::resource('policies', PolicyController::class)
        ->except(['show']);

    Route::resource('alerts', AlertController::class);

    // Route::resource('tracking', TrackingController::class);
    Route::resource('tracking', MapController::class);
    
    Route::resource('reports', ReportController::class);
    Route::resource('settings', SettingController::class)
        ->except(['show']);
});