<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasApiTokens, Notifiable;


    protected $table = 'games';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'total_players'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function creator()
    {
        return $this->hasOne('App\User', 'created_by');
    }

    public function deck()
    {
        return $this->hasOne('App\Deck', 'deck_used');
    }

    public function players()
    {
        return $this->belongsToMany('App\User', 'game_user', 'game_id', 'user_id');
    }
}
