<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Video;

class VoteController extends Controller
{
    public function vote(Channel $channel, Video $video, $type) {
        if ($channel->is_owner){
            return response()->json([
                'status'  => false,
                'message' => "You Can't Vote this Item."
            ]);
        }
        if (($type !== 'up') && ($type !== 'down')) {
            return response()->json([
                'status'  => false,
                'message' => "Vote Type is Invalid!"
            ]);
        }
        return auth()->user()->toggleVote($video, $type);
    }
}
