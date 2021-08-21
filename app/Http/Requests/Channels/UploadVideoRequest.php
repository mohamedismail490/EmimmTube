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
            'video' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,video/x-matroska,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'/* ,video/vnd.dlna.mpeg-tts,application/octet-stream */,
            'description' => 'max:1000'
        ];
    }
}
