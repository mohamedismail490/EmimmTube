<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channels\UpdateRequest;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    public function __construct()
    {
        $this -> middleware('auth')->only('store','update','destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    public function show(Channel $channel)
    {
        return view('channels.show', compact('channel'));
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
