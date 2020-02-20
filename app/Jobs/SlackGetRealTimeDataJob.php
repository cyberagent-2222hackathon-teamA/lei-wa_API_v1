<?php

namespace App\Jobs;

use App\Repositories\UserRepository;
use App\Repositories\CacheRepository;

class SlackGetRealTimeDataJob extends BaseJob
{
    /**
     * slack workspace token
     * @var string
     */
    private $token;

    /**
     * slack channel id
     * @var string
     */
    private $channel_id;

    /**
     * dispatch時の処理
     * @param string $token
     * @param string $channel_id
     */
    public function __construct(string $token, string $channel_id)
    {
        $this->token = $token;
        $this->channel_id = $channel_id;
    }

    /**
     * ジョブの実行
     * @throws
     */
    public function handle(
        UserRepository $user_repository,
        CacheRepository $cache_repository
    ){

        $a_month_ago = strtotime("-30 day");
        $oldest = mktime(0,0, 0, date('m', $a_month_ago),date('d', $a_month_ago),date('Y', $a_month_ago));;
        $latest = mktime(23,59, 59, date('m'),date('d'),date('Y'));;

        //リアルタイムで１ヶ月分のslackデータを取得
        $realtime_data = $user_repository->getSlackDataByChannelAndTime(
            $this->channel_id,
            $this->token,
            $oldest,
            $latest
        );

        //cacheに保存
        $cache_key = "slack_contributes_".$this->channel_id.date("_Ymd");
        $cache_repository->setCacheData($cache_key, $realtime_data, 60 * 24);

    }
}
