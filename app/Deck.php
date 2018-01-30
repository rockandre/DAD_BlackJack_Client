<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
	protected $table = 'decks';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hidden_face_image_path', 'active', 'complete'
    ];

    public function games()
    {
        return $this->belongsTo('App\Game', 'deck_used');
    }
}
