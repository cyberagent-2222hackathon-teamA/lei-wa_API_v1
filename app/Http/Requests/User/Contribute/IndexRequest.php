<?php
namespace App\Http\Requests\User\Contribute;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules()
    {
        return [
            'date'    => ['nullable', 'date'],
            'user_id' => ['required', 'integer'],
        ];
    }

    // URLパラメータもバリデートできるようにする
    public function validationData()
    {
        return array_merge($this->all(), [
            'user_id' => $this->route('user_id'),
        ]);
    }
}
