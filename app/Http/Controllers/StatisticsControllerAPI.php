<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Game;

use DB;

class StatisticsControllerAPI extends Controller
{
    public function statistics() {
    	$statistics = [];

    	$statistics['registedUsers'] = $this->registedUsers();
    	$statistics['activeGames'] = $this->activeGames();
    	$statistics['totalGames'] = $this->totalGames();

    	$statistics['top5MostGames'] = $this->top5MostGames();
    	$statistics['top5MostPoints'] = $this->top5MostPoints();
    	$statistics['top5BestAvg'] = $this->top5BestAvg();

    	return response()->json(['statistics' => $statistics], 200);

    }

    public function registedUsers() {
    	return User::count();
    }

    public function activeGames() {
    	return Game::where('status', 'active')->count();
    }

    public function totalGames() {
    	return Game::where('status', 'terminated')->count();
    }

    public function top5MostGames() {
    	$users = User::orderBy('total_games_played', 'desc')->take(5)->get();

    	return $users;
    }

    public function top5MostPoints() {
    	$users = User::orderBy('total_points', 'desc')->take(5)->get();

    	return $users;
    }

    public function top5BestAvg() {
    	$users = DB::table('users')
                ->orderByRaw('(total_points / total_games_played) DESC')
                ->take(5)
                ->get();

    	return $users;
    }
}
