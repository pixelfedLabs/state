<?php

use Illuminate\Http\Request;

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


Route::group(['prefix' => 'api/v1'], function() {
	Route::get('systems', 'ApiController@systems');
	Route::get('services', 'ApiController@services');
	Route::get('service/{id}', 'ApiController@service');
	Route::get('incidents', 'ApiController@incidents');
});

Route::post('account/{username}/inbox', 'ActivityPubController@inbox');
