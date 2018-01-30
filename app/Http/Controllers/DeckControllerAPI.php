<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deck;

class DeckControllerAPI extends Controller
{
    public function getQuantity() {
    	$count = Deck::where('complete', 1)->where('active', 1)->count();

    	return response()->json(['decks' => $count], 200);
    }

    public function getCardsByDeck($id) {
    	$deck = Deck::findOrFail($id);

    	if(!is_null($deck)) {
    		return response()->json(['cards' => $deck->cards], 200);
    	}

    		return response()->json(['error' => "Invalid Request"], 400);
    }
}
