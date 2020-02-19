<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\SlackWorkspaceUser;
use App\Entities\UserEntity;
use App\Utilities\EntityMapper;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class UserRepository
{

    private $user;


    public function __construct(User $user, SlackWorkspaceUser $slack_workspace_user)
    {
        $this->user                 = $user;
        $this->slack_workspace_user = $slack_workspace_user;
    }


    /**
     * 特定userの指定範囲のslackのデータを返す
     *
     * @param string $twitter_id twitter id
     * @param string $oldest 最も古いメッセージのtimestamp
     * @param string $latest 最も新しいメッセージのtimestamp
     *
     * @return UserEntity
     */
    public function getTodaySlackData($twitter_id, $oldest, $latest){

        //user_idからslackのchannelとuser名を取得
        $user_id = $this->getUserByTwitterId($twitter_id)->id;

        $slack_user_info = $this->slack_workspace_user::with('slack_workspace')
            ->where('user_id', $user_id)
            ->firstOrFail()
            ->toArray();

        $slack_channel_id   = $slack_user_info['channel_id'];
        $slack_user_id      = $slack_user_info['slack_user_id'];
        $slack_token        = $slack_user_info['slack_workspace']['token'];

        $cache_key = "slack_contributes_".date("Ymd");

        if(Cache::has($cache_key)){
            //cacheからデータを取得
            $todays_all_user_activity = Cache::get($cache_key);
        }else{
            //slackと通信
            $api_res = (new \GuzzleHttp\Client())->get(config('slack.api_url').'/channels.history', [
                'query' => [
                    'channel' => $slack_channel_id,
                    'token'   => $slack_token,
                    'oldest'  => $oldest,
                    'latest'  => $latest,
                ]
            ]);

            $todays_all_user_activity = json_decode($api_res->getBody()->getContents())->messages;
            //cacheに保存
            Cache::put($cache_key, $todays_all_user_activity, 60);

        }

        $user_activity = collect($todays_all_user_activity)->where('user', $slack_user_id);

        return $user_activity;

    }

    /**
     * user idでuserを特定し, userの情報を返す
     *
     * @param string $twitter_id users user id
     * @return array
     */
    public function getUserById(int $user_id){

        $user = $this->user
            ->where('id', $user_id)
            ->firstOrFail()
            ->toArray();

        return $user;

    }

    /**
     * twitter idでuserを特定し, userの情報を返す
     *
     * @param string $twitter_id users twitter id
     * @return UserEntity
     */
    public function getUserByTwitterId(string $twitter_id){

        $user = $this->user::with(['contributes', 'follows'])
            ->where('name', $twitter_id)
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
            ->firstOrCreate(['name' => $twitter_id]);

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

    /**
     * twtter_idで指定されたuserのsession tokenを更新し, 更新後のtokenを返す
     *
     * @param string $token authorization token
     */
    public function getUserByToken($token){

        $user = $this->user
            ->where('token', $token)
            ->firstOrFail()
            ->toArray();

        return $user;

    }

    /**
     * user情報を更新する
     *
     * @param int $id user id
     * @param array $params 更新情報
     */
    public function update($id, $params){

        $user = $this->user
            ->where('id', $id)
            ->firstOrFail();

        $user->fill($params)->save();

    }
}
