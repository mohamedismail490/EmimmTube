<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Subscription;

class SubscriptionController extends Controller
{

    public function store(Channel $channel)
    {
        if ($channel->is_owner){
            return response()->json([
               'status'  => false,
               'message' => "You can't Subscribe to Your Channel"
            ]);
        }
        $channel->subscriptions()->create([
            'user_id' => auth() -> user() -> id
        ]);
        $subChannel = Channel::find($channel->id);
        return response()->json(['status' => true, 'channel' => $subChannel]);
    }

    public function destroy(Channel $channel, Subscription $subscription)
    {
        $userSub = Subscription::where('id', $subscription->id)
            ->where('channel_id', $channel->id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();
        $userSub->delete();
        $subChannel = Channel::find($channel->id);
        return response()->json(['status' => true, 'channel' => $subChannel]);
    }
}
