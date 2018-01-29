<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\DeckResource as DeckResource;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Deck;
use App\StoreUserRequest;
use Hash;



class DecksControllerAPI extends Controller
{
    public function getDecks(Request $request)
    {
        if ($request->has('page')) {
            return DeckResource::collection(Deck::paginate(5));
        } else {
            return DeckResource::collection(Deck::all());
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deck = Deck::findOrFail($id);

        $dir = storage_path('app/public/decks/'.$deck->name);
        array_map('unlink', glob($dir.'/*.*'));
        rmdir($dir);

        $deck->delete();
        return response()->json(null, 204);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'active' => 'required|integer',
                'complete' => 'required|integer'
            ]);

        $deck = Deck::findOrFail($id);
        $deck->update($request->all());
        return new DeckResource($deck);
    }

    public function addDeck(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:decks|string',
            'image' => 'required'
        ]);
        if ($validator->passes()) {

            $fileName = 'semFace.png';
            // save in datatable
            $name = $request->get('name');
            $image = $request->get('image');
            $imagePath = $name.'/'.$fileName;

            $deckCreated = Deck::create(['name' => $name, 'hidden_face_image_path' => $imagePath, 'active' => 0, 'complete' => 0]);

            $deckCreated->save();

            // save in storage folder
            $image = Image::make($image);
            $image->heighten(726);
            if (!file_exists(storage_path('app/public/decks/'.$name))) {
                mkdir(storage_path('app/public/decks/'.$name), 777, true);
            }
            $image->save(storage_path('app/public/decks/'.$name.'/'.$fileName), 90);

            return response()->json(['msg' => 'Deck Created.'], 200);
        } else {
            return response()->json(['msg' => 'Invalid Request.'], 400);
        }
    }
}
