<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

class PostCreate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $exist = true;

        $result = Http::withHeaders(['Authorization' => 'Bot ' . env('BOT_API_KEY')])
            ->get('https://discordapp.com/api/guilds/' . env('DISCORD_GUILD_ID') .'/members/' . auth()->user()->id);

        if ($result->failed()) $exist = false;

        return $exist;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'postTitle' => 'required|max:50',
            'postDescription' => 'required',
        ];
    }
}
