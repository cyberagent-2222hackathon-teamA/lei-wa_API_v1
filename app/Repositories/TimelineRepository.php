<?php

namespace App\Repositories;

use App\Entities\ContributeSummaryWithUserEntity;
use App\Models\ContributeSummary;
use App\Utilities\EntityMapper;

class TimelineRepository
{

    private $timeline;


    public function __construct(ContributeSummary $timeline)
    {
        $this->timeline = $timeline;
    }


    /**
     * 全ユーザーのtimelineを新着順に返す
     *
     * @param int $page page num
     * @param int $limit data count per page
     *
     * @return array
     */
    public function getPublicTimeline($page, $limit){

        $total = $this->timeline->count();

        $contributes = $this->timeline::with('user')
            ->orderBy('id', 'desc')
            ->forPage($page, $limit)
            ->get()
            ->toArray();

        $res = [
            'total_page'  => $total,
            'page'        => $page,
            'limit'       => $limit,
            'contributes' => EntityMapper::collection($contributes, ContributeSummaryWithUserEntity::class)->toArray()
        ];

        return $res;

    }
}
