<?php
namespace App\Http\Requests\User\Contribute;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules()
    {
        return [
            'date'    => ['nullable', 'date'],
            'twitter_id' => ['required', 'string'],
        ];
    }

    // URLパラメータもバリデートできるようにする
    public function validationData()
    {
        return array_merge($this->all(), [
            'twitter_id' => $this->route('twitter_id'),
        ]);
    }
}
