<?php

namespace App\Jobs;

use Abraham\TwitterOAuth\TwitterOAuth;

class PostTwitterJob extends BaseJob
{
    /**
     * users twitter id
     * @var string
     */
    private $twitter_id;

    /**
     * users twitter oauth token
     * @var string
     */
    private $twitter_oauth_token;

    /**
     * users twitter oauth token secret
     * @var string
     */
    private $twitter_oauth_token_secret;

    /**
     * users yesterdays activity
     * @var array
     */
    private $yesterdays_reaction_summary;

    /**
     * dispatch時の処理
     * @param string $twitter_id
     */
    public function __construct(
        string $twitter_id,
        string $twitter_oauth_token,
        string $twitter_oauth_token_secret,
        array $yesterdays_reaction_summary
    ){
        $this->twitter_id                  = $twitter_id;
        $this->twitter_oauth_token         = $twitter_oauth_token;
        $this->twitter_oauth_token_secret  = $twitter_oauth_token_secret;
        $this->yesterdays_reaction_summary = $yesterdays_reaction_summary;
    }

    /**
     * ジョブの実行
     * @throws
     */
    public function handle(){

        $twitter = new TwitterOAuth(
            config('twitter.consumer_key'),
            config('twitter.consumer_secret'),
            $this->twitter_oauth_token,
            $this->twitter_oauth_token_secret
        );
        # 指定したユーザーのタイムラインを取得
        $timeline = $twitter->post("statuses/update", [
            "status" => $this->twitter_id."さんの".time()."のアクティビティ:"
                ."質問数->".$this->yesterdays_reaction_summary['post_count']
                . " リアクション数->".$this->yesterdays_reaction_summary['reaction_count']
                ." #reiwa"
        ]);

    }
}
