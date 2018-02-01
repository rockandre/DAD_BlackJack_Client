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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// image
Route::get('storage/{deck}/{card}', function ($deck, $card)
{
    return Image::make(Storage::disk('local')->get('public/decks/'.$deck.'/'. $card))->response();
});

Route::get('storage/{deckpath}', function ($deckpath)
{
    return Image::make(Storage::disk('local')->get('public/decks/'.$deckpath))->response();
});

Route::get('storage/user/avatar/{avatarPath}', function ($avatarPath)
{
    return Image::make(Storage::disk('local')->get('public/users/'.$avatarPath))->response();
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
	Route::put('users/{id}/avatar', 'UserControllerAPI@updateAvatar');
	Route::delete('users/{id}', 'UserControllerAPI@delete');
	Route::put('user/password/update', 'UserControllerAPI@updatePassword');
	Route::put('user/email/update', 'UserControllerAPI@updateEmail');


	// settings
	Route::post('/settings/update', 'ConfigEmailControllerAPI@update');

	// decks
	Route::get('decks', 'DeckControllerAPI@getDecks');
	Route::delete('decks/{id}', 'DeckControllerAPI@destroy');
	Route::put('decks/{id}', 'DeckControllerAPI@update');
	Route::post('deck', 'DeckControllerAPI@addDeck');

	// cards
	Route::post('card', 'CardControllerAPI@addCard');
});


// Server to server
Route::post('game/create', 'GameControllerAPI@create');
Route::put('game/update/{id}', 'GameControllerAPI@update');
Route::get('decks/minMax', 'DeckControllerAPI@getMinMax');
Route::get('decks/{id}', 'DeckControllerAPI@getCardsByDeck');
Route::get('statistics', 'StatisticsControllerAPI@statistics');

Route::get('/images/event/{filename}', function ($filename)
{
	return Image::make(Storage::disk('local')->get('public/deck1/' . $filename))->response();
});