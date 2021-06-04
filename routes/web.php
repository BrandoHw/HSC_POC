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
use App\Http\Controllers\AwsBucketController;
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

Auth::routes(['register' => false, 'reset' => false]);


Route::group(['middleware' => ['auth']], function() { 
    
    Route::get('/', [HomeController::class, 'index'])->name('home')->block();
    
    //Route::resource('dashboard', DashboardController::class);

    Route::resource('buildings', BuildingController::class);
    Route::resource('floors', FloorController::class);
    Route::get('floors/destroy/{id}', [FloorController::class, 'destroyHref'])->name('floor.destroy');

    Route::resource('beacons', TagController::class)
        ->parameters(['beacons' => 'tag'])
        ->except(['show', 'destroy']);
    Route::delete('beacons/destroys', [TagController::class, 'destroys'])
        ->name('beacons.destroys');

    Route::resource('gateways', ReaderController::class)
        ->parameters(['gateways' => 'reader'])
        ->except(['show', 'destroy']);
    Route::delete('gateways/destroys', [ReaderController::class, 'destroys'])
        ->name('gateways.destroys');

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
    Route::get('user/get', [UserLastSeenController::class, 'get'])->block();
    Route::get('user-positions', [UserLastSeenController::class, 'getUserPositions'])->block();
    Route::get('user/group', [UserLastSeenController::class, 'group']);
    
    Route::resource('residents', ResidentController::class)
        ->except(['show', 'destroy']);
    Route::delete('residents/destroys', [ResidentController::class, 'destroys'])
        ->name('residents.destroys');
    
    Route::resource('attendance', AttendanceController::class)
        ->only(['index', 'destroy']);
    Route::get('attendance/date', [AttendanceController::class, 'show_date'])
        ->name('attendance.date');
    Route::get('attendance/badge', [AttendanceController::class, 'show_badge'])
        ->name('attendance.badge');

    Route::resource('policies', PolicyController::class)
        ->name('*', 'policies')
        ->except(['show', 'destroy']);
    Route::delete('policies/destroys', [PolicyController::class, 'destroys'])
        ->name('policies.destroys');

    Route::get('alerts-unresolved', [AlertController::class, 'getAlerts'])->block();
    // Route::resource('tracking', TrackingController::class);
    Route::resource('tracking', MapController::class);
    
    Route::resource('reports', ReportController::class);
    Route::get('report-data', [ReportController::class, 'getData'])->name('report.data');
    Route::resource('alerts', AlertController::class)
        ->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('alerts/updates', [AlertController::class, 'updates'])
        ->name('alerts.updates');
    Route::delete('alerts/destroys', [AlertController::class, 'destroys'])
        ->name('alerts.destroys');
    Route::post('alerts/new', [AlertController::class, 'new_alerts'])
        ->name('alerts.new');
    Route::post('alerts/new/table', [AlertController::class, 'new_alerts_table'])
        ->name('alerts.new_table');
    Route::patch('alerts/resolve/all', [AlertController::class, 'resolve_all'])
        ->name('alerts.resolve_all');
    Route::patch('alerts/resolve', [AlertController::class, 'resolve'])
        ->name('alerts.resolve');

    Route::resource('settings', SettingController::class)
        ->except(['show']);
        
    Route::resource('roles', RoleController::class)
        ->except(['show', 'index', 'destroy']);
    Route::delete('roles/destroys', [RoleController::class, 'destroys'])
        ->name('roles.destroys');

    Route::resource('users', UserController::class)
        ->except(['show', 'index', 'destroy']);
    Route::post('users/profile', [UserController::class, 'change_profile'])
        ->name('users.profile');
    Route::post('users/password', [UserController::class, 'change_password'])
        ->name('users.password');
    Route::post('users/reset', [UserController::class, 'reset_password'])
        ->name('users.reset');
    Route::delete('users/destroys', [UserController::class, 'destroys'])
        ->name('users.destroys');

    Route::get('refresh-csrf', function(){
        return csrf_token();
    });
    // Route::get('/foo', function () {
    //     Artisan::call('storage:link');
    // });

    Route::get('get-image', [AwsBucketController::class, 'getImage']);
    Route::get('test', [GeneralController::class, 'index']);
    Route::post('test', [GeneralController::class, 'storeTest']);
});