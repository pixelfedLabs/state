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
	Route::get('systems/show/{id}', 'DashboardController@systemShow');
	Route::get('services', 'DashboardController@services')->name('dashboard.services');
	Route::get('services/show/{id}', 'DashboardController@serviceShow');
	Route::get('incidents', 'DashboardController@incidents')->name('dashboard.incidents');
	Route::get('incidents/show/{id}', 'DashboardController@incidentShow');
	Route::get('incidents/create', 'DashboardController@incidentCreate')->name('dashboard.incidents.create');
	Route::post('incidents/create', 'DashboardController@incidentStore');
});

Route::get('account/{id}', 'ActivityPubController@profile');
Route::get('account/{id}/outbox', 'ActivityPubController@outbox');

Route::get('site/about', 'SiteController@about')->name('site.about');
Route::get('site/subscribe', 'SiteController@subscribe')->name('site.subscribe');

Route::get('incident/{id}', 'IncidentController@show');