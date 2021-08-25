<?php

namespace App\Http\Requests\Videos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'body' => 'required|max:1000'
        ];
        if ($this->comment_id) {
            $rules['comment_id'] = [
                Rule::exists('comments','id')->where(function ($q) {
                    $q -> whereNull('comment_id');
                }),
            ];
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'body' => $this->comment_id ? 'Comment Reply' :'Comment',
            'comment_id' => 'Parent Comment'
        ];
    }
}
