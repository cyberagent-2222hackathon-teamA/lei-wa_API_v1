<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

use App\Repositories\UserRepository;
use App\Repositories\CacheRepository;

class UserService
{

    private $user_repository;

    private $cache_repository;

    public function __construct(
        UserRepository $user_repository,
        CacheRepository $cache_repository
    ){
        $this->user_repository = $user_repository;
        $this->cache_repository = $cache_repository;
    }

    /**
     * user nameから特定userの情報を取得する
     *
     * @param string $name user name
     * @return array
     */
    public function getUserByName(string $name)
    {
        $user_data = $this->user_repository->getUserByName($name)->toArray();
        $slack_user_info = $this->user_repository->getSlackInfoByUserId($user_data['id']);

        $slack_channel_id   = $slack_user_info['channel_id'];
        $slack_user_id      = $slack_user_info['slack_user_id'];
        $slack_token        = $slack_user_info['slack_workspace']['token'];

        $timestamp_start = mktime(0,0, 0, date('m'),date('d'),date('Y'));
        $timestamp_end   = mktime(23,59, 59, date('m'),date('d'),date('Y'));

        //channelから全ユーザーのcontributesを取得
        $cache_key = "slack_contributes_".$slack_channel_id.date("_Ymd");
        if($this->cache_repository->isCacheDataPresent($cache_key)){//cacheがある場合はcacheからデータ取得
            $all_user_contributes = $this->cache_repository->getCacheData($cache_key);
        }else{//なければapiからデータ取得
            $all_user_contributes = $this->user_repository->getSlackDataByChannelAndTime(
                $slack_channel_id,
                $slack_token,
                $timestamp_start,
                $timestamp_end);
        }

        //user_idと日時で特定userの投稿を絞り込み
        $user_contributes = collect($all_user_contributes)
            ->where('user', $slack_user_id)
            ->where('ts', '>', $timestamp_start)
            ->where('ts', '<', $timestamp_end);

        //reaction_count計算
        $reaction_count = $user_contributes->sum(function ($el) {
            if(isset($el->reactions)){
                return collect($el->reactions)->sum('count');
            }else{
                return 0;
            };
        });

        //post_count計算
        $post_count = $user_contributes->count();

        $todays_contributes_summary = [
            'id'             => empty($user_data['contributes']->toArray()) ? 1 : $user_data['contributes']->last()->id + 1,
            'post_count'     => $post_count,
            'reaction_count' => $reaction_count,
            'date'           => date("Y-m-d")
        ];

        $user_data['contributes'][] = $todays_contributes_summary;

        return $user_data;
    }

}
