<?php

namespace App\Jobs;

use App\Repositories\UserRepository;
use App\Repositories\CacheRepository;
use App\Models\ContributeSummary;

class SlackGetDataJob extends BaseJob
{
    /**
     * users twitter id
     * @var string
     */
    private $user_id;

    /**
     * dispatch時の処理
     * @param int $user_id
     */
    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * ジョブの実行
     * @throws
     */
    public function handle(
        UserRepository $user_repository,
        CacheRepository $cache_repository
    ){

        $user = $user_repository->getUserById($this->user_id);

        $twitter_id                 = $user['name'];
        $twitter_oauth_token        = $user['twitter_oauth_token'];
        $twitter_oauth_token_secret = $user['twitter_oauth_token_secret'];

        //slack情報取得
        $slack_user_info = $user_repository->getSlackInfoByUserId($this->user_id);

        $slack_channel_id   = $slack_user_info['channel_id'];
        $slack_user_id      = $slack_user_info['slack_user_id'];
        $slack_token        = $slack_user_info['slack_workspace']['token'];

        $yesterday = strtotime("-1 day");

        $oldest   = mktime(0,0, 0, date('m', $yesterday),date('d', $yesterday),date('Y', $yesterday));
        $latest   = mktime(23,59, 59, date('m', $yesterday),date('d', $yesterday),date('Y', $yesterday));

        //channelから全ユーザーのcontributesを取得
        $cache_key = "slack_contributes_".$slack_channel_id.date("_Ymd");
        if($cache_repository->isCacheDataPresent($cache_key)){//cacheがある場合はcacheからデータ取得
            $all_user_contributes = $cache_repository->getCacheData($cache_key);
        }else{//なければapiからデータ取得
            $all_user_contributes = $user_repository->getSlackDataByChannelAndTime(
                $slack_channel_id,
                $slack_token,
                $oldest,
                $latest);
        }

        //user_idと日時で特定userの昨日の投稿を絞り込み
        $user_contributes = collect($all_user_contributes)
            ->where('user', $slack_user_id)
            ->where('ts', '>', $oldest)
            ->where('ts', '<', $latest);

        $yesterdays_reaction_count = $user_contributes->sum(function ($el) {
            if(isset($el->reactions)){
                return collect($el->reactions)->sum('count');
            }else{
                return 0;
            };
        });

        $yesterdays_reaction_summary = [
            'user_id'        => $this->user_id,
            'post_count'     => $user_contributes->count(),
            'reaction_count' => $yesterdays_reaction_count,
            'date'           => date("Y-m-d")
        ];

        ContributeSummary::create($yesterdays_reaction_summary);

        PostTwitterJob::dispatch(
            $twitter_id,
            $twitter_oauth_token,
            $twitter_oauth_token_secret,
            $yesterdays_reaction_summary
        );
    }
}
