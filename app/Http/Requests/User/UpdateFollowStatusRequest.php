<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id'   => ['required', 'integer'],
            'follow_id' => ['required', 'integer'],
        ];
    }
}
