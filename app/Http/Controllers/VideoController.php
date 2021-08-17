<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channels\UploadVideoRequest;
use App\Jobs\Videos\ConvertingForStreaming;
use App\Jobs\Videos\CreateVideoThumbnail;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    public function index(Channel $channel)
    {
        if (!$channel->is_owner){
            abort(403, 'You Can Not Access This Page!');
        }
        return view('channels.upload', compact('channel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /** @noinspection PhpParamsInspection */
    public function store(UploadVideoRequest $request, Channel $channel)
    {
        $video = $channel->videos()->create([
            'title' => $request -> input('title'),
            'path' => $request -> file('video') -> store("channels/{$channel->id}")
        ]);
        $this->dispatch(new CreateVideoThumbnail($video));
        $this->dispatch(new ConvertingForStreaming($video));
        return $video;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
