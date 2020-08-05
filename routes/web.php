<?php

use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});*/
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'DashboardController@dashboard')->name('admin.index');
    Route::resource('/reports', 'ReportController', ['as' => 'admin']);
    Route::get('/reports/download/{report}', 'ReportController@download')->name('admin.report.download');
    Route::resource('/cities', 'CityController', ['as' => 'admin']);
    Route::resource('/weather', 'WeatherController', ['as' => 'admin']);
    Route::group(['prefix' => 'user_management', 'namespace' => 'UserManagement'], function () {
        Route::get('/profile', ['uses' => 'UserController@show_self', 'as' => 'admin.user_management.user.self']);
        Route::resource('/users', 'UserController', ['as' => 'admin.user_management']);
        Route::resource('/roles', 'RoleController', ['as' => 'admin.user_management']);
        Route::resource('/permissions', 'PermissionController', ['as' => 'admin.user_management']);
        /*  Route::resource('/sections', 'SectionsController', ['as' => 'admin.user_management']);*/

    });
});
Route::get('/', 'HomeController@index')->name('home');
Auth::routes();
