<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StreamerEdit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role == "admin" || auth()->user()->role == "super_admin") {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'streamerID' => 'required',
            'name' => 'required',
            'twitchName' => 'required',
            'image' => 'required'
        ];
    }
}
