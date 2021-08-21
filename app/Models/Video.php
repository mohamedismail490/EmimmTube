<?php

namespace App\Models;

class Video extends Model
{
    public function channel() {
        return $this->belongsTo(Channel::class)->withDefault();
    }

    public function isChannelVideo($channelId){
        return $this->channel_id === $channelId;
    }

    public function editable() {
        return (auth() -> check()) && ($this -> channel -> user_id === auth() -> user() -> id);
    }
}
