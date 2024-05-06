<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserBan;
use App\Http\Requests\UserRole;
use App\Http\Requests\UserSerachRequest;
use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class UsersController extends Controller
{
    public function showUsers()
    {
        if (auth()->user()->role == "user" or auth()->user()->role == "banned") return back();

        $users = User::paginate(10);

        return view('users', ['users' => $users, 'isSearch' => false]);
    }

    public function processUserRoleForm(UserRole $request) {
        $user = User::where('id', $request->get('user'))->firstOrFail();

        if ($user->role == $request->get('role')) {
            return back()->with('error', $user->username . '#' . $user->discriminator . ' is already ' . $user->role . '!');
        }

        $user->update(['role' => $request->get('role')]);

        return back()->with('success', $user->username . '#' . $user->discriminator . ' successfully promoted/demoted to ' . $user->role . '!');
    }

    public function processUserBanForm(UserBan $request) {
        $user = User::where('id', $request->get('user'))->firstOrFail();

        if ($user->id == auth()->user()->id) {
            return back()->with('error', 'You can`t ban yourself!');
        }

        $user->update(['role' => 'banned']);

        $user->hasMany(ForumPost::class)->delete();

        $exist = true;

        $result = Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
            ->get('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') .'/members/' . $user->id);

        if ($result->failed()) $exist = false;

        if ($exist) {
            $ban = Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
                ->put('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') .'/bans/' . $user->id, ['delete_message_seconds' => 0]);
        }

        return back()->with('success', $user->username . '#' . $user->discriminator . ' successfully banned!');
    }

    public function processUserSearch(UserSerachRequest $request)
    {
        $foundUser = User::where('id', $request->get('discordID'))->get();

        return view("users", ['users' => $foundUser, 'isSearch' => true]);
    }
}
