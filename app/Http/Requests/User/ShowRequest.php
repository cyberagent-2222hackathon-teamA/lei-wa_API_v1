<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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
