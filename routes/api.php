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

Route::group(['middleware' => ['cors']], function() {
	// login register route
	Route::post('/auth/login', 'AuthController@login');
	Route::post('/auth/register', 'AuthController@register');
	// check token with 1 user
	Route::group(['middleware' => ['checkToken']], function() {
		// check token exist
		Route::group(['middleware' => ['jwt.auth']], function() {
			Route::get('/auth/check', 'AuthController@check');
			Route::post('/auth/logout', 'AuthController@logout');

		});

	});

});
