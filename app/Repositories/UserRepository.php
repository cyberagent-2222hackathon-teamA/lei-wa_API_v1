<?php

namespace App\Repositories;

use App\Models\User;
use App\Entities\UserEntity;
use App\Utilities\EntityMapper;

class UserRepository
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * idでuserを特定し, userの情報を返す
     *
     * @param int $user_id user id
     * @return UserEntity
     */
    public function getUserById($user_id){

        $user = $this->user::with('contributes')
            ->where('id', $user_id)
            ->firstOrFail()
            ->toArray();

        return EntityMapper::map($user, UserEntity::class);

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
