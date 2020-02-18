<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

use App\Repositories\UserRepository;
use mysql_xdevapi\Collection;

class UserService
{

    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * userのtwitteridから特定userの情報を取得する
     *
     * @param string $twitter_id user id
     * @return array
     */
    public function getUserByTwitterId(string $twitter_id)
    {
        $user_data = $this->user_repository->getUserByTwitterId($twitter_id)->toArray();

        $timestamp_start = mktime(0,0, 0, date('m'),date('d'),date('Y'));
        $timestamp_end   = mktime(23,59, 59, date('m'),date('d'),date('Y'));

        $todays_activity = $this->user_repository->getTodaySlackData($user_data['id'], $timestamp_start, $timestamp_end);

        $todays_reaction_count = $todays_activity->sum(function ($el) {
            if(isset($el->reactions)){
                return collect($el->reactions)->sum('count');
            }else{
                return 0;
            };
        });

        $todays_reaction_summary = [
            'post_count'     => $todays_activity->count(),
            'reaction_count' => $todays_reaction_count,
            'date'           => date("Y-m-d")
        ];

        $user_data['contributes'][] = $todays_reaction_summary;

        return $user_data;
    }

}
