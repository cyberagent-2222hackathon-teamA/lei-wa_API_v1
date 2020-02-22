<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\SlackWorkspaceUser;
use App\Entities\UserEntity;
use App\Utilities\EntityMapper;
use GuzzleHttp\Client;

class UserRepository
{

    private $user;


    public function __construct(User $user, SlackWorkspaceUser $slack_workspace_user)
    {
        $this->user                 = $user;
        $this->slack_workspace_user = $slack_workspace_user;
    }


    /**
     * 特定channelの指定期日範囲のslackのデータを返す
     *
     * @param string $slack_channel_id slack channelのid
     * @param string $oldest 最も古いメッセージのtimestamp
     * @param string $latest 最も新しいメッセージのtimestamp
     *
     * @return UserEntity
     */
    public function getSlackDataByChannelAndTime($slack_channel_id, $slack_token, $oldest, $latest){

        $api_res = (new \GuzzleHttp\Client())->get(config('slack.api_url').'/channels.history', [
            'query' => [
                'channel' => $slack_channel_id,
                'token'   => $slack_token,
                'oldest'  => $oldest,
                'latest'  => $latest,
            ]
        ]);

        $all_user_contributes = json_decode($api_res->getBody()->getContents())->messages;

        return $all_user_contributes;

    }

    /**
     * user idでuserを特定し, userのslack関連情報を返す
     *
     * @param int user_id user id
     * @return array
     */
    public function getSlackInfoByUserId(int $user_id){

        $slack_user_info = $this->slack_workspace_user::with('slack_workspace')
            ->where('user_id', $user_id)
            ->firstOrFail()
            ->toArray();

        return $slack_user_info;

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
     * user nameでuserを特定し, userの情報を返す
     *
     * @param string $twitter_id users twitter id
     * @return UserEntity
     */
    public function getUserByName(string $name){

        $user = $this->user::with(['contributes'])
                     ->where('name', $name)
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

    /**
     * userのslack情報を作成する
     *
     * @param int $user_id user id
     * @param array $params slack情報
     */
    public function createSlackInfo($user_id, $params){

        $user = $this->user
            ->where('id', $user_id)
            ->firstOrFail();

        //すでに情報が存在する場合は作成せずにreturn
        $slack_info = $this->slack_workspace_user
        ->where('user_id', $user_id)
        ->first();

        if(isset($slack_info)){
            return;
        }

        $user->slack_info()->create($params);

    }

    /**
     * userのslack情報を更新する
     *
     * @param int $user_id user id
     * @param array $params slack情報
     */
    public function updateSlackInfo($user_id, $params){
        $this->slack_workspace_user
             ->where('user_id', $user_id)
             ->firstOrFail()
             ->update($params);
    }

    /**
     * userのslack情報入力が完了しているかどうかを確認する
     */
    public function isSettingCompleted($user_id){

        $user = $this->user::with('slack_info')
            ->where('id', $user_id)
            ->first();

        return isset($user->slack_info->slack_user_id);
    }
}
