<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ShowIAMRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
        ];
    }
}
