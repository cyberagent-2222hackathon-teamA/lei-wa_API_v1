<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

use App\Repositories\UserRepository;

class UserService
{

    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * user_idから特定userの情報を取得する
     *
     * @param int $user_id user id
     * @return array
     */
    public function getUserById(int $user_id)
    {

        return $this->user_repository->getUserById($user_id);

    }

}
