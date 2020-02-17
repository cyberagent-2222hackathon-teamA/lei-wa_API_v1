<?php
namespace App\Http\Requests\Timeline;

use Illuminate\Foundation\Http\FormRequest;

class PrivateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'limit'   => ['nullable', 'integer'],
            'page'    => ['nullable', 'integer'],
        ];
    }
}
