<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * twtter_idでuserを特定し, idを返す userが存在しない場合はuserを作成する
     *
     * @param string $twitter_id twitterのid
     */
    public function getOrCreateUserByTwitterID($twitter_id){

        $user = $this->user
            ->firstOrCreate(['twitter_id' => $twitter_id]);

        return $user->id;

    }

    /**
     * twtter_idで指定されたuserのsession tokenを更新し, 更新後のtokenを返す
     *
     * @param int $id user id
     */
    public function updateToken($id){

        $user = $this->user
            ->where('id', $id)
            ->firstOrFail();

        $new_token = bin2hex(openssl_random_pseudo_bytes(16));

        $user->update(['token' => $new_token]);

        return $user->token;

    }
}
