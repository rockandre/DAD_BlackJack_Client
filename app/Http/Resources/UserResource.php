<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'blocked' => $this->blocked,
            'reason_blocked' => $this->reason_blocked,
            'reason_reactivated' => $this->reason_reactivated,
            'total_points' => $this->total_points,
            'total_games_played' => $this->total_games_played
        ];
    }
}
