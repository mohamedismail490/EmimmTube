<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channels\UpdateRequest;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    public function __construct()
    {
        $this -> middleware('auth')->only('store','update');
    }

    public function show(Channel $channel)
    {
        return view('channels.show', compact('channel'));
    }

    public function axiosShow(Channel $channel)
    {
        $channel = Channel::with(['user'])->findOrFail($channel->id);
        $channelVideos = $channel->videos();
        if (!$channel->is_owner){
            $channelVideos = $channelVideos->where('percentage', 100);
        }

        return $channelVideos->paginate(12);
    }

    public function update(UpdateRequest $request, Channel $channel)
    {
        if ($request->hasFile('image')) {
            $channel -> clearMediaCollection('images');
            $channel -> addMediaFromRequest('image')
                ->toMediaCollection('images');
        }

        $channel -> update([
            'name' => $request -> input('name'),
            'description' => $request -> input('description')
        ]);

        return back();
    }
}
