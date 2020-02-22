<?php

namespace App\Repositories;

use App\Entities\UserEntity_xs;
use App\Models\ContributeSummary;
use App\Models\Follow;
use App\Utilities\EntityMapper;

class SlackRepository
{

    private $timeline;
    private $follow;


    public function __construct(ContributeSummary $timeline, Follow $follow)
    {
        $this->timeline = $timeline;
        $this->follow   = $follow;
    }




    /**
     * 全ユーザーのtimelineを新着順に返す
     *
     * @param string $token slack token
     * @param string $channel_id slack_channel_id
     *
     * @return
     */
    public function getChannelUsers($token, $channel_id){

        $api_res = (new \GuzzleHttp\Client())->get(config('slack.api_url').'/conversations.members', [
            'query' => [
                'token'   => $token,
                'channel' => $channel_id
            ]
        ]);

        return json_decode($api_res->getBody()->getContents())->members;

    }

    /**
     * slackから特定userの情報を取得するrequestを返す
     *
     * @param string $token slack token
     * @param string $channel_id slack_channel_id
     *
     * @return
     */
    public function getSlackUserById($token, $user_id){

        $promise = (new \GuzzleHttp\Client())->requestAsync(
            'GET',
            config('slack.api_url').'/users.info',
            [
                'query' => [
                    'token' => $token,
                    'user'  => $user_id
                ],
            ]
        );

        return $promise;

    }

}
