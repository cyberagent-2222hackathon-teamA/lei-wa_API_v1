<?php

namespace App\Services;

use App\Repositories\TimelineRepository;

class TimelineService
{

    private $timeline_repository;

    public function __construct(TimelineRepository $timeline_repository)
    {
        $this->timeline_repository = $timeline_repository;
    }

    /**
     * timelineの全データを取得する
     *
     * @param int $user_id user id
     * @return array
     */
    public function getPublicTimeline($page, $limit)
    {
        return $this->timeline_repository->getPublicTimeline($page, $limit);
    }

}
