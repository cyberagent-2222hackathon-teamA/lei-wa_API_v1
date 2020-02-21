<?php

namespace App\Repositories;

use App\Entities\ContributeSummaryWithUserEntity;
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

        $api_res = (new \GuzzleHttp\Client())->get(config('slack.api_url').'/channel.list', [
            'query' => [
                'token'   => $token,
            ]
        ]);

        $all_channels_info = json_decode($api_res->getBody()->getContents());

        $channel_info = collect($all_channels_info)->where('id', $channel_id);

        return $channel_info ? $channel_info->members : [];

    }

    /**
     * slackから特定userの情報を取得するrequestを返す
     *
     * @param string $token slack token
     * @param string $channel_id slack_channel_id
     *
     * @return
     */
    public function getSlackUserById($token, $channel_id){

        return "aa";

    }

}
