<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\SlackRepository;
use App\Repositories\CacheRepository;

class SlackService
{

    private $slack_repository;

    private $user_repository;

    private $cache_repository;

    public function __construct(
        SlackRepository $slack_repository,
        UserRepository $user_repository,
        CacheRepository $cache_repository
    ){
        $this->user_repository = $user_repository;
        $this->cache_repository = $cache_repository;
        $this->slack_repository = $slack_repository;
    }

    /**
     * 指定のuserと同じworkspaceに属するuserをすべて取得する
     *
     * @param int $user_id user id
     * @return
     */
    public function getSameWorkSpaceUsers(int $user_id)
    {
        //user_idからslack情報取得
        $slack_user_info = $this->user_repository->getSlackInfoByUserId($user_id);
        $slack_channel_id   = $slack_user_info['channel_id'];
        $slack_token        = $slack_user_info['slack_workspace']['token'];

        //channel_idからそのchannelに属する全userを取得
        $channel_users = $this->slack_repository->getChannelUsers($slack_token, $slack_channel_id);

        //それぞれのuserに対してinfoを取得するGuzzleObjectを取得
        foreach ($channel_users as $channel_user){
            $promises[] = $this->slack_repository->getSlackUserById($channel_user);
        }
        //並列でリクエスト実行
        $responses = Promise\settle($promises)->wait();

        //parse
        foreach ($responses as $response) {
            $result[] = $response->getBody()->getContents();
        }

        return $result;

    }

}
