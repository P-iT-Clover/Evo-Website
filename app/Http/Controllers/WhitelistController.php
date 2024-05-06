<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhitelistActionRequest;
use App\Http\Requests\WhitelistQuestionCreate;
use App\Http\Requests\WhitelistQuestionRequest;
use App\Http\Requests\WhitelistRequestSearchRequest;
use App\Models\WhitelistQuestion;
use Illuminate\Support\Facades\Http;

class WhitelistController extends Controller
{
    public function showWhitelistRequests()
    {
        if (auth()->user()->role == "user" or auth()->user()->role == "banned") return back();

        $whitelist_requests = \App\Models\WhitelistRequest::paginate(10);

        return view('whitelist_requests', ['whitelist_requests' => $whitelist_requests, 'isSearch' => false]);
    }

    public function showWhitelistQuestions()
    {
        if (auth()->user()->role == "user" or auth()->user()->role == "banned") return back();

        $whitelist_questions = WhitelistQuestion::paginate(10);

        return view('whitelist_questions', ['whitelist_questions' => $whitelist_questions]);
    }

    public function processWhitelist(WhitelistActionRequest $request)
    {
        $whitelistRequest = \App\Models\WhitelistRequest::where('id', $request->get('whitelistID'))->firstOrFail();

        if ($request->input('action') == "approve") {
            $whitelistRequest->update(['status' => 'Approved']);
            Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
                ->put('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') . '/members/' . $whitelistRequest->user->id . '/roles/' . env('DISCORD_WHITELIST_ROLE_ID'));
            return redirect(route('show_whitelist_requests'))->with('success', 'Successfully approved ' . $whitelistRequest->user->username . '#' . $whitelistRequest->user->discriminator . '!');
        } elseif ($request->input('action') == "reject") {
            $whitelistRequest->delete();
            Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
                ->delete('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') . '/members/' . $whitelistRequest->user->id . '/roles/' . env('DISCORD_WHITELIST_ROLE_ID'));
            return redirect(route('show_whitelist_requests'))->with('success', 'Successfully rejected ' . $whitelistRequest->user->username . '#' . $whitelistRequest->user->discriminator . '!');
        }
    }

    public function processWhitelistQuestion(WhitelistQuestionRequest $request)
    {
        $whitelistQuestion = \App\Models\WhitelistQuestion::where('id', $request->get('whitelistQuestionID'))->firstOrFail();

        if ($request->input('action') == "edit") {
            $whitelistQuestion->update([
                'qid'   => $request->get('questionID'),
                'type'  => $request->get("questionType"),
                'label' => $request->get("questionLabel"),
                'behaviour' => $request->get("questionBehaviour")
            ]);

            return back()->with('success', 'Successfully edited the question!');
        } else {
            $whitelistQuestion->delete();

            return back()->with('success', 'Successfully deleted the question!');
        }
    }

    public function processWhitelistQuestionCreation(WhitelistQuestionCreate $request)
    {
        if ($whitelistQuestion = \App\Models\WhitelistQuestion::where('qid', $request->get('questionID'))->first()) {
            return back()->with('error', 'Question with this id already exist. ('. $whitelistQuestion->label .')');
        }

        \App\Models\WhitelistQuestion::create([
            'qid'   => $request->get('questionID'),
            'type'  => $request->get('questionType'),
            'label' => $request->get('questionLabel'),
            'behaviour' => $request->get('questionBehaviour')
        ]);

        return back()->with('success', 'Successfully created the whitelist question!');
    }

    public function processWhitelistSearch(WhitelistRequestSearchRequest $request)
    {
        $foundRequests = \App\Models\WhitelistRequest::where('user_id', $request->get('discordID'))->get();

        return view("whitelist_requests", ['whitelist_requests' => $foundRequests, 'isSearch' => true]);
    }
}
