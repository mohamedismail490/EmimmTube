<?php

namespace App\Http\Requests\Channels;

use Illuminate\Foundation\Http\FormRequest;

class UploadVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->channel->user_id === auth() -> user() -> id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'max:255',
            'video' => 'required',
            'description' => 'max:1000'
        ];
    }
}
