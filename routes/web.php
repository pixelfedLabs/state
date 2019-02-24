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

	// Services
	Route::get('services', 'DashboardController@services')->name('dashboard.services');
	Route::get('services/create', 'DashboardController@serviceCreate')->name('dashboard.services.create');
	Route::post('services/create', 'DashboardController@serviceStore');
	Route::get('services/show/{id}', 'DashboardController@serviceShow');
	Route::delete('services/show/{id}', 'DashboardController@serviceDelete');

	// Incidents
	Route::get('incidents', 'DashboardController@incidents')->name('dashboard.incidents');
	Route::get('incidents/create', 'DashboardController@incidentCreate')->name('dashboard.incidents.create');
	Route::post('incidents/create', 'DashboardController@incidentStore');
	Route::get('incidents/show/{id}', 'DashboardController@incidentShow');
	Route::post('incidents/show/{id}', 'DashboardController@incidentStatusStore');
	Route::delete('incidents/show/{id}', 'DashboardController@incidentDelete');

	// Incident Updates
	Route::get('incidents/show/{incidentId}/update/{updatedId}', 'DashboardController@incidentUpdateShow');
	Route::post('incidents/show/{incidentId}/update/{updatedId}', 'DashboardController@incidentUpdateStore');
	Route::delete('incidents/show/{incidentId}/update/{updatedId}', 'DashboardController@incidentUpdateDelete');

	// Agents
	Route::get('agents', 'DashboardController@agents')->name('dashboard.agents');
	Route::get('agents/create', 'DashboardController@agentCreate')->name('dashboard.agents.create');
	Route::post('agents/create', 'DashboardController@agentStore');
	Route::get('agents/show/{id}', 'DashboardController@agentShow');
	Route::post('agents/show/{id}', 'DashboardController@agentUpdate');
	Route::delete('agents/show/{id}', 'DashboardController@agentDelete');
	Route::get('agents/show/{agent_id}/check/{check_id}', 'DashboardController@agentCheckShow');
});

Route::get('account/{id}', 'ActivityPubController@profile');
Route::get('account/{id}/outbox', 'ActivityPubController@outbox');

Route::get('.well-known/webfinger', 'ActivityPubController@webfinger')->name('well-known.webfinger');

Route::get('site/about', 'SiteController@about')->name('site.about');
Route::get('site/subscribe', 'SiteController@subscribe')->name('site.subscribe');

Route::group(['prefix' => 'feeds'], function() {
	Route::get('service/history.atom', 'ServiceController@atomFeed')->name('feed.service.atom');
});

Route::get('api/v1/services/uptime/{agentId}', 'ApiController@serviceUptime');

Route::group(['prefix' => 'api/v2'], function() {
	Route::get('agents', 'AdminApiController@agents');
});

Route::get('incident/{id}', 'IncidentController@show');
Route::get('uptime/{slug}/{year}/{month}/{day}', 'AgentCheckController@show');