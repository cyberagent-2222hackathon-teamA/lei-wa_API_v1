<?php
namespace App\Http\Requests\Auth\Login\Twitter;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
{
    public function rules()
    {
        return [
            'oauth_token'    => ['required', 'string'],
            'oauth_verifier' => ['required', 'string'],
        ];
    }
}
