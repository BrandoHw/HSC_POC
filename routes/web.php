<?php

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TagDataLogController;
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
    
    Route::get('/', 'HomeController@index')->name('home');
    
    Route::resource('dashboard', 'DashboardController');

    Route::get('tag_data_logs/view/', [TagDataLogController::class, 'process'])->name('tag_data_logs.process');
 

    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::resource('projects', 'ProjectController');
    Route::resource('buildings', 'BuildingController');
    Route::resource('companies', 'CompanyController');
    Route::resource('schedules', 'ScheduleController');
    Route::resource('tags', 'TagController');
    // Route::resource('tag_data_logs', 'TagDataLogController');
    Route::resource('readers', 'ReaderController');
    // Route::resource('attendances', 'TagDataLogController');
    Route::resource('companies.readers', 'CompanyReaderController');
    Route::resource('groups', 'GroupController');
    Route::resource('groups.users', 'GroupUserController');
    Route::resource('groups.timeblocks', 'TimeblockController');
   
});