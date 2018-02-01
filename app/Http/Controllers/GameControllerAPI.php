<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Game;
use App\GameUser;

class GameControllerAPI extends Controller
{
	public function getPendentGames() {

	}

	public function create(Request $request) {

		$validator = Validator::make($request->all(), [
			'total_players' => 'required|integer',
			'created_by' => 'required',
			'deck_used' => 'required',
		]);

		if($validator->passes()) {

			$user = User::where('nickname', $request['created_by'])->first();

			$game = Game::create([
				'total_players' => $request['total_players'],
				'created_by' => $user->id,
				'deck_used' => $request['deck_used'],
			]);

			$game->players()->attach($user->id);

			return response()->json(['msg' => "Everything Fine", 'game' => $game], 200);

		} else {
			return response()->json(['msg' => "Invalid Request"], 400);
		}


	}	

	public function update(Request $request, $id) {

		$game = Game::findOrFail($id);

		if($request['status']) {
			$game->status = $request['status'];

			if($request['winners']) {
				if(count($request['winners']) > 1) {
					foreach($request['winners'] as $nickname) {
						$user = User::where('nickname', $nickname)->first();
						$user->total_points += $request['points'];
						$user->save();
						$game->players()->updateExistingPivot($user->id, array('status' => 1));
					}
				} else {
					$user = User::where('nickname', $request['winners'])->first();
					$user->total_points += $request['points'];
					$user->save();
					$game->players()->updateExistingPivot($user->id, array('status' => 2));
				}
			}

			if($request['players']) {
				foreach($request['players'] as $nickname) {
					$user = User::where('nickname', $nickname)->first();
					$user->total_games_played ++;
					$user->save();
					$game->players()->updateExistingPivot($user->id, array('status' => 1));
				}
			}
		}

		if($request['total_players']) {
			$game->total_players = $request['total_players'];

			if($request['join']) {
				$user = User::where('nickname', $request['join'])->first();
				$game->players()->attach($user->id);
			}

			if($request['leave']) {
				$user = User::where('nickname', $request['leave'])->first();
				$game->players()->detach($user->id);
			}
		}

		$game->save();

		return response()->json(['msg' => "Game update"], 200);
	}

}