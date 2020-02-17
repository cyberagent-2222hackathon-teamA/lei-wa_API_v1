<?php
namespace App\Http\Requests\Timeline;

use Illuminate\Foundation\Http\FormRequest;

class PublicRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => ['nullable', 'integer'],
            'page'  => ['nullable', 'integer'],
        ];
    }
}
