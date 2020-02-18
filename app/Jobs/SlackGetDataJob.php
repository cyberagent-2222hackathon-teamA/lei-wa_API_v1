<?php

namespace App\Jobs;

use App\Repositories\UserRepository;
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
        UserRepository $user_repository
    ){

        $user = $user_repository->getUserById($this->user_id);

        $twitter_id                 = $user['name'];
        $twitter_oauth_token        = $user['twitter_oauth_token'];
        $twitter_oauth_token_secret = $user['twitter_oauth_token_secret'];

        $yesterday = strtotime("-1 day");

        $oldest   = mktime(0,0, 0, date('m', $yesterday),date('d', $yesterday),date('Y', $yesterday));
        $latest   = mktime(23,59, 59, date('m', $yesterday),date('d', $yesterday),date('Y', $yesterday));

        $yesterday_user_data = $user_repository->getTodaySlackData($twitter_id, $oldest, $latest);

        $yesterdays_reaction_count = $yesterday_user_data->sum(function ($el) {
            if(isset($el->reactions)){
                return collect($el->reactions)->sum('count');
            }else{
                return 0;
            };
        });

        $yesterdays_reaction_summary = [
            'user_id'        => $this->user_id,
            'post_count'     => $yesterday_user_data->count(),
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
