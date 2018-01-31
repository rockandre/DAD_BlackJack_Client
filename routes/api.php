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
Route::post('password/email', 'LoginControllerAPI@sendEmail');
Route::post('password/reset', 'LoginControllerAPI@resetPassword');

// image
Route::get('storage/{deck}/{card}', function ($deck, $card)
{
    return Image::make(Storage::disk('local')->get('public/decks/'.$deck.'/'. $card))->response();
});
Route::get('storage/{deckpath}', function ($deckpath)
{
    return Image::make(Storage::disk('local')->get('public/decks/'.$deckpath))->response();
});

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

	// decks
	Route::get('decks', 'DecksControllerAPI@getDecks');
	Route::delete('decks/{id}', 'DecksControllerAPI@destroy');
	Route::put('decks/{id}', 'DecksControllerAPI@update');
	Route::post('deck', 'DecksControllerAPI@addDeck');

	// cards
	Route::post('card', 'CardControllerAPI@addCard');
});
