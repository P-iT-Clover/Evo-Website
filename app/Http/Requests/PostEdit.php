<?php

namespace App\Http\Requests;

use App\Models\ForumPost;
use Illuminate\Foundation\Http\FormRequest;

class PostEdit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->posts->contains($this->request->get('postID')) or auth()->user()->role == "admin" or auth()->user()->role == "super_admin") {
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
            'postID' => 'required',
            'postTitle' => 'required|max:50',
            'postDescription' => 'required',
        ];
    }
}
