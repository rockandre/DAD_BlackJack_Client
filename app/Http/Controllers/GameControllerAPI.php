<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameControllerAPI extends Controller
{
    public function getPendentGames() {
    	
    }

    public function create(Request $request) {

    	


		return response()->json(['msg' => "Everything Fine"], 200);
    }	
}
