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

Route::post('login', 'LoginControllerAPI@login');
Route::post('register', 'RegisterControllerAPI@register');

Route::middleware(['auth:api'])->group( function () {

	Route::get('/user', function (Request $request) {
		return $request->user();
	});

	Route::post('logout', 'LoginControllerAPI@logout');

});