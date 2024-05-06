<?php

namespace App\Http\Controllers;

use App\Http\Requests\StreamerCreate;
use App\Http\Requests\StreamerDelete;
use App\Http\Requests\StreamerEdit;
use App\Models\Streamer;

class StreamersController extends Controller
{
    public function showStreamers()
    {
        $streamers = Streamer::paginate(5);

        return view('streamers', ['streamers' => $streamers]);
    }

    public function processStreamerCreation(StreamerCreate $request)
    {
        Streamer::create([
            'name' => $request->get('name'),
            'twitchName' => $request->get('twitchName'),
            'image' => $request->get('image')
        ]);

        return back()->with('success', $request->get('name') . ' created successfully!');
    }

    public function processStreamerDelete(StreamerDelete $request)
    {
        $streamer = Streamer::where('id', $request->get('streamerID'))->firstOrFail();

        $streamer->delete();

        return back()->with('success', 'Streamer successfully deleted!');
    }

    public function processStreamerEdit(StreamerEdit $request)
    {
        $streamer = Streamer::where('id', $request->get('streamerID'))->firstOrFail();

        $streamer->update([
            'name' => $request->get('name'),
            'twitchName' => $request->get('twitchName'),
            'image' => $request->get('image')
        ]);

        return back()->with('success', 'Successfully edit this streamer!');
    }
}
