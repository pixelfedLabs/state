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

Route::view('/', 'welcome');

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
	Route::post('incidents/show/{id}', 'DashboardController@incidentStatusStore');
	Route::delete('incidents/show/{id}', 'DashboardController@incidentDelete');
	Route::get('incidents/create', 'DashboardController@incidentCreate')->name('dashboard.incidents.create');
	Route::post('incidents/create', 'DashboardController@incidentStore');
	Route::get('incidents/show/{incidentId}/update/{updatedId}', 'DashboardController@incidentUpdateShow');
	Route::post('incidents/show/{incidentId}/update/{updatedId}', 'DashboardController@incidentUpdateStore');
	Route::delete('incidents/show/{incidentId}/update/{updatedId}', 'DashboardController@incidentUpdateDelete');
});

Route::get('account/{id}', 'ActivityPubController@profile');
Route::get('account/{id}/outbox', 'ActivityPubController@outbox');

Route::get('.well-known/webfinger', 'ActivityPubController@webfinger')->name('well-known.webfinger');

Route::get('site/about', 'SiteController@about')->name('site.about');
Route::get('site/subscribe', 'SiteController@subscribe')->name('site.subscribe');

Route::get('incident/{id}', 'IncidentController@show');