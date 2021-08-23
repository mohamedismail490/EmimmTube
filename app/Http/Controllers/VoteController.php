<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Comment;
use App\Models\Video;

class VoteController extends Controller
{
    public function vote($entityId, $entityType, $type) {
        if (($type !== 'up') && ($type !== 'down')) {
            return response()->json([
                'status'  => false,
                'message' => "Vote Type is Invalid!"
            ]);
        }
        if (($entityType !== 'video') && ($entityType !== 'comment')) {
            return response()->json([
                'status'  => false,
                'message' => "Something wrong happened. Please, try again."
            ]);
        }
        if ($entityType === 'video') {
            $entity = Video::findOrFail($entityId);
            if ($entity->channel->is_owner){
                return response()->json([
                    'status'  => false,
                    'message' => "You Can't Vote this Item."
                ]);
            }
        }else {
            $entity = Comment::findOrFail($entityId);
            if ($entity->is_owner){
                return response()->json([
                    'status'  => false,
                    'message' => "You Can't Vote this Item."
                ]);
            }
        }

        return auth()->user()->toggleVote($entity, $type);
    }
}
