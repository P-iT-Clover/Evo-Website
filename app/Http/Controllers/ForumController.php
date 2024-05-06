<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreate;
use App\Http\Requests\PostEdit;
use App\Models\ForumPost;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;

class ForumController extends Controller
{
    public function showForum(): View
    {
        $posts = ForumPost::latest()->paginate(4);

        $exist = true;

        $result = Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
            ->get('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') .'/members/' . auth()->user()->id);

        if ($result->failed() or auth()->user()->role == "banned") $exist = false;

        return view('forum', ['posts' => $posts, 'exist' => $exist]);
    }

    public function processPostCreationForm(PostCreate $request) {
        ForumPost::create([
            'user_id' => auth()->user()->id,
            'title' => $request->get('postTitle'),
            'description' => $request->get('postDescription'),
        ]);

//        return redirect()->route('show_forum');
        return back()->with('success', 'Post created successfully');
    }

    public function processPostEditForm(PostEdit $request) {
        $post = ForumPost::where('id', $request->get('postID'))->firstOrFail();

        $post->update(['title' => $request->get('postTitle'), 'description' => $request->get('postDescription')]);

//        return redirect()->route('show_forum');
        return back()->with('success', 'Post edited successfully');
    }

    public function destroyPost(ForumPost $post) {
        if (auth()->user()->posts->contains($post) or auth()->user()->role == "admin" or auth()->user()->role == "super_admin") {
            $post->delete();

//            return back();
            return back()->with('success', 'Post deleted successfully');
        }

        return back();
    }
}
