<?php
namespace App\Http\Requests\Slack;

use Illuminate\Foundation\Http\FormRequest;

class GetUsersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer']
        ];
    }
}
