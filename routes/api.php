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




// auth
Route::post('login', 'LoginControllerAPI@login');
Route::post('register', 'RegisterControllerAPI@register');

Route::middleware(['auth:api'])->group( function () {

	Route::get('user', function (Request $request) {
		return $request->user();
	});

	Route::post('logout', 'LoginControllerAPI@logout');

	// Game
	Route::get('pendentgames', 'GameControllerAPI@getPendentGames');
	
	// user
	Route::get('users', 'UserControllerAPI@getUsers');
	Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
	Route::get('users/{id}', 'UserControllerAPI@getUser');
	Route::post('users', 'UserControllerAPI@store');
	Route::put('users/{id}', 'UserControllerAPI@update');
	Route::delete('users/{id}', 'UserControllerAPI@delete');
	Route::put('user/password/update', 'UserControllerAPI@updatePassword');
	Route::put('user/email/update', 'UserControllerAPI@updateEmail');

	// settings
	Route::post('/settings/update', 'ConfigEmailControllerAPI@update');
});


// Server to server
Route::post('game/create', 'GameControllerAPI@create');
Route::get('decks/quantity', 'DeckControllerAPI@getQuantity');
Route::get('decks/{id}', 'DeckControllerAPI@getCardsByDeck');

Route::get('/images/event/{filename}', function ($filename)
{
	return Image::make(Storage::disk('local')->get('public/deck1/' . $filename))->response();
});