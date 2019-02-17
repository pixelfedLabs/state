<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'dashboard'], function() {

	Route::redirect('/', '/dashboard/home');
	Route::get('home', 'DashboardController@home')->name('dashboard.home');
	Route::get('systems', 'DashboardController@systems')->name('dashboard.systems');
	Route::get('services', 'DashboardController@services')->name('dashboard.services');
	Route::get('incidents', 'DashboardController@incidents')->name('dashboard.incidents');

});