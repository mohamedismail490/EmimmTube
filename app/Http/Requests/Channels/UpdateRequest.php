<?php

namespace App\Http\Requests\Channels;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255',
            'description' => 'max:1000'
        ];
        if ($this->hasFile('image')){
            $rules['image'] = 'image|mimes:jpeg,png,gif,jpg|max:2048';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'image' => 'Channel Avatar',
            'name' => 'Channel Name',
            'description' => 'Channel Description'
        ];
    }
}
