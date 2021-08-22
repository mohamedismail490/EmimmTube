<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    public function channel() {
        return $this->belongsTo(Channel::class)->withDefault();
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function isChannelVideo($channelId){
        return $this->channel_id === $channelId;
    }

    public function editable() {
        return (auth() -> check()) && ($this -> channel -> user_id === auth() -> user() -> id);
    }
}
