<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlackInfoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id'       => ['required', 'integer'],
            'slack_user_id' => ['required', 'string'],
        ];
    }
}
