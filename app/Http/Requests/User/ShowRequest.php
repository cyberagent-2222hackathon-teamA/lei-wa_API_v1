<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }

    // URLパラメータもバリデートできるようにする
    public function validationData()
    {
        return array_merge($this->all(), [
            'name' => $this->route('name'),
        ]);
    }
}
