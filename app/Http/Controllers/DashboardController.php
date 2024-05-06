<?php

namespace App\Http\Controllers;

use App\Http\Requests\LauncherRegister;
use App\Http\Requests\WhitelistRequest;
use App\Models\User;
use App\Models\WhitelistQuestion;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function showDashboard(): View
    {
        $isFirstLogin = false;

        if (auth()->user()->launcher_username == null) {
            $isFirstLogin = true;
        }

        $isInTheDiscord = Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
                              ->get('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') . '/members/' . auth()->user()->id);

        if ($isInTheDiscord->failed()) {
            $isWhitelisted = false;
        } else {
            $isWhitelisted = in_array("1029620123260178502", (Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
                ->get('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') . '/members/' . auth()->user()->id))->json()["roles"]);
        }



        $result = Http::get('https://servers-frontend.fivem.net/api/servers/single/' . env("SERVER_CFX_CODE"));

        if ($isWhitelisted) {
            if ($result->failed()) {
                return view('dashboard', ['online' => false, 'isFirstLogin' => $isFirstLogin, 'isWhitelisted' => $isWhitelisted]);
            } else {
                return view('dashboard', ['players' => $result->json()['Data']['clients'], 'capacity' => $result->json()['Data']['sv_maxclients'], 'online' => true, 'isFirstLogin' => $isFirstLogin, 'isWhitelisted' => $isWhitelisted]);
            }
        } else {
            session(['constantWhitelistQuestions' => WhitelistQuestion::where('behaviour', 'constant')->limit(env("CONSTANT_WHITELIST_QUESTIONS"))->get()]);
            session(['randomWhitelistQuestions' => WhitelistQuestion::inRandomOrder()->where('behaviour', 'random')->limit(env("RANDOM_WHITELIST_QUESTIONS"))->get()]);
            if ($result->failed()) {
                return view('dashboard', ['online' => false, 'isFirstLogin' => $isFirstLogin, 'isWhitelisted' => $isWhitelisted, 'constantWhitelistQuestions' => session('constantWhitelistQuestions'), 'randomWhitelistQuestions' => session('randomWhitelistQuestions')]);
            } else {
                return view('dashboard', ['players' => $result->json()['Data']['clients'], 'capacity' => $result->json()['Data']['sv_maxclients'], 'online' => true, 'isFirstLogin' => $isFirstLogin, 'isWhitelisted' => $isWhitelisted, 'constantWhitelistQuestions' => session('constantWhitelistQuestions'), 'randomWhitelistQuestions' => session('randomWhitelistQuestions') ]);
            }
        }
    }

    public function processWhitelistRequest(WhitelistRequest $request)
    {
        $questions = array();

        foreach (session('constantWhitelistQuestions') as $constantWhitelistQuestion) {
            array_push($questions, array('id' => $constantWhitelistQuestion->qid, 'type' => $constantWhitelistQuestion->type, 'label' => $constantWhitelistQuestion->label, 'answer' => $request->get($constantWhitelistQuestion->qid), 'behaviour' => $constantWhitelistQuestion->behaviour));
        }

        foreach (session('randomWhitelistQuestions') as $randomWhitelistQuestion) {
            array_push($questions, array('id' => $randomWhitelistQuestion->qid, 'type' => $randomWhitelistQuestion->type, 'label' => $randomWhitelistQuestion->label, 'answer' => $request->get($randomWhitelistQuestion->qid), 'behaviour' => $randomWhitelistQuestion->behaviour));
        }

        \App\Models\WhitelistRequest::create([
            'user_id'  => auth()->user()->id,
            'question' => json_encode($questions),
            'status'   => 'Waiting approval'
        ]);

        return back()->with('success', 'Successfully applied for whitelist!');
    }

    public function processLauncherRegisterForm(LauncherRegister $request)
    {
        if (User::where('launcher_username', $request->get("username"))->first()) {
            return back()->with('error', 'User with this username already exist!');
        }

        $user = User::where('id', auth()->user()->id)->firstOrFail();

        $user->update(['launcher_username' => $request->get('username'), 'launcher_password' => Hash::make($request->get("password"))]);

        return back()->with('success', 'Successful registration!');
    }
}
