<?php

namespace App\Services\User;

use \App\Repositories\UserRepository;

use App\Utilities\EntityMapper;

use App\Entities\DailyContributesEntity;


class ContributeService
{

    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * userのactivity情報の詳細を取得する
     *
     * @param string $twitter_id twitter id
     * @param array $params params
     * @return array
     */
    public function getUserContributesById(string $twitter_id, array $params)
    {
          $oldest = '';
          $latest = '';

          if(isset($params['date'])){
              $date_str = $params['date'];
              $date = strtotime($date_str);
              $oldest   = mktime(0,0, 0, date('m', $date),date('d', $date),date('Y', $date));
              $latest   = mktime(23,59, 59, date('m', $date),date('d', $date),date('Y', $date));
          }

          $res = $this->user_repository->getTodaySlackData($twitter_id, $oldest, $latest);

          return EntityMapper::collection($res->toArray(), DailyContributesEntity::class);
    }

}
