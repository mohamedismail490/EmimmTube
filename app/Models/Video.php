<?php

namespace App\Models;

class Video extends Model
{
    public function isChannelVideo($channelId){
        return $this->channel_id === $channelId;
    }
}
