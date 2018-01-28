<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\DeckResource as DeckResource;
use Illuminate\Support\Facades\DB;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $deck->delete();
        return response()->json(null, 204);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|string|max:255',
                //'hidden_face_image_path' => 'mimes:jpeg,png',
                'hidden_face_image_path' => 'required|string|max:255',
                'active' => 'required|integer',
                'complete' => 'required|integer'
            ]);

        $deck = Deck::findOrFail($id);
        $deck->update($request->all());
        return new DeckResource($deck);
    }
}
