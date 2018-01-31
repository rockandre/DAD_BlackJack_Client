<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Card;
use App\Deck;

use Intervention\Image\ImageManagerStatic as Image;

class CardControllerAPI extends Controller
{
	public function addCard(Request $request) {
		$validatorRequest = Validator::make($request->all(), [
			'deckId' => 'required',
			'deckName' => 'required|string',
		]);

		$valid = 0;

		foreach ($request->cards as $card) {
			$validator = Validator::make($card, [
				'value' => 'required|string',
				'suite' => 'required|string',
			]);

			if(!$validator->passes()) {
				$valid ++;
			}
		}

		if ($validatorRequest->passes() && $valid == 0) {

			$deckId = $request->get('deckId');
			$deckName = $request->get('deckName');

			foreach ($request->cards as $card) {

				$fileName = '';
				$value = $card['value'];
				$suite = $card['suite'];

				switch ($suite) {
					case 'Club':
					$fileName = $fileName.'p';
					break;
					case 'Diamond':
					$fileName = $fileName.'o';
					break;
					case 'Heart':
					$fileName = $fileName.'c';
					break;
					case 'Spade':
					$fileName = $fileName.'e';
					break;
				}

				switch ($value) {
					case 'Ace':
					$fileName = $fileName.'1';
					break;
					case 'Jack':
					$fileName = $fileName.'11';
					break;
					case 'Queen':
					$fileName = $fileName.'12';
					break;
					case 'King':
					$fileName = $fileName.'13';
					break;
					default:
					$fileName = $fileName.$value;
				}

				$fileName = $fileName.'.png';

				$path = $deckName.'/'.$fileName;

				if($card['image'] != '' && $card['image'] != 'REMOVED') {
					$cardBD = Card::where('value', $value)->where('suite', $suite)->where('deck_id', $deckId)->first();
					if(is_null($cardBD)) {
						$cardCreated = Card::create(['value' => $value, 'suite' => $suite, 'deck_id' => $deckId, 'path' => $path]);

						$cardCreated->save();

            			// save in storage folder
						var_dump($card['image']);
						$image = Image::make($card['image']);
						$image->heighten(726);
						$image->save(storage_path('app/public/decks/'.$path), 90);
					}
				}

				if($card['image']=='REMOVED') {
					$card = Card::where('value', $value)->where('suite', $suite)->where('deck_id', $deckId)->first();
					if(!is_null($card)) {
						$card->delete();
						$file = storage_path('app/public/decks/'.$path);
						array_map('unlink', glob($file));
					}
				}
			}

			$deck = Deck::findOrFail($deckId);
			if($deck->cards->count()==52) {
				$deck->complete = 1;
			} else {
				$deck->complete = 0;
			}

			$deck->save();

			return response()->json(['msg' => 'Card updated with success.', 'complete' => $deck->cards->count()==52 ? true : false], 200);
		} else {
			return response()->json(['msg' => 'Invalid Request.'], 400);
		}
	}
}


