<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Repositories\CacheRepository;

use App\Utilities\EntityMapper;

use App\Entities\DailyContributesEntity;


class ContributeService
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
     * userのactivity情報の詳細を取得する
     *
     * @param string $name user name(twitter id)
     * @param array $params params
     * @return array
     */
    public function getUserContributesByName(string $name, array $params)
    {
        $oldest = '';
        $latest = '';

        $user_data = $this->user_repository->getUserByName($name)->toArray();
        $user_name = $user_data['name'];
        $slack_user_info = $this->user_repository->getSlackInfoByUserId($user_data['id']);

        $slack_channel_id   = $slack_user_info['channel_id'];
        $slack_user_id      = $slack_user_info['slack_user_id'];
        $slack_token        = $slack_user_info['slack_workspace']['token'];

        if(isset($params['date'])){
          $date_str = $params['date'];
          $date = strtotime($date_str);
          $oldest   = mktime(0,0, 0, date('m', $date),date('d', $date),date('Y', $date));
          $latest   = mktime(23,59, 59, date('m', $date),date('d', $date),date('Y', $date));
        }

        //channelから全ユーザーのcontributesを取得
        $cache_key = "slack_contributes_".$slack_channel_id.date("_Ymd");
        if($this->cache_repository->isCacheDataPresent($cache_key)){//cacheがある場合はcacheからデータ取得
            $all_user_contributes = $this->cache_repository->getCacheData($cache_key);
        }else{//なければapiからデータ取得
            $all_user_contributes = $this->user_repository->getSlackDataByChannelAndTime(
                $slack_channel_id,
                $slack_token,
                $oldest,
                $latest
            );
        }

        //user_idで特定userの投稿を絞り込み
        $user_contributes = collect($all_user_contributes)
            ->where('user', $slack_user_id);

        //date指定されている場合はdateで絞り込み
        if(isset($params['date'])){
            $user_contributes = $user_contributes
                ->where('ts', '>', $oldest)
                ->where('ts', '<', $latest);
        }

        //自分がかかわらないイベントに関しては除去
        $user_contributes = $user_contributes->filter(function($item) use ($slack_user_id){
            return !(isset($item->subtype) && $item->user != $slack_user_id);
        });

        //自分が関わるイベントに関してはmessageの主語をtwitter_idに変換
        $user_contributes = $user_contributes->each(function($item) use ($user_name){
            if(isset($item->subtype)){
                $message_array = preg_split("/\s/", $item->text);
                $message_array[0] = $user_name;
                $message = join(' ', $message_array);
                $item->text = $message;
            }
        });

        //index追加
        $user_contributes->each(function($item, $index){
         $item->id = $index;
        });

        //replyがある場合reply追加
        $user_contributes->each(function($user_contribute) use ($user_contributes){
            //replyがある場合
            if(isset($user_contribute->replies)){
                $replies = collect($user_contribute->replies)->map(function($item) use ($user_contributes){
                    return $user_contributes->firstWhere('ts', $item->ts);
                });
                $user_contribute->replies = $replies;
            }
        });

        //replyは除去
        $user_contributes = $user_contributes->filter(function($item){
            return !isset($item->parent_user_id);
        });

        return EntityMapper::collection($user_contributes->toArray(), DailyContributesEntity::class);
    }

}
