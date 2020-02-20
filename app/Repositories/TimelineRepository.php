<?php

namespace App\Repositories;

use App\Entities\ContributeSummaryWithUserEntity;
use App\Models\ContributeSummary;
use App\Models\Follow;
use App\Utilities\EntityMapper;

class TimelineRepository
{

    private $timeline;
    private $follow;


    public function __construct(ContributeSummary $timeline, Follow $follow)
    {
        $this->timeline = $timeline;
        $this->follow   = $follow;
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

        $total = ceil((float)$this->timeline->count()/(float)$limit);

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

    /**
     * 全ユーザーのtimelineを新着順に返す
     *
     * @oaram int $user_id user id
     * @param int $page page num
     * @param int $limit data count per page
     *
     * @return array
     */
    public function getPrivateTimeline($user_id, $page, $limit){

        $total = ceil((float)$this->timeline->count()/(float)$limit);

        $follow_ids = $this->follow
            ->where('follow_id', $user_id)
            ->get()
            ->pluck('followed_id')
            ->toArray();

        if(empty($follow_ids)){
            $contributes = [];
        }else{
            $contributes = $this->timeline::with('user')
                ->where('user_id', $follow_ids)
                ->orderBy('id', 'desc')
                ->forPage($page, $limit)
                ->get()
                ->toArray();
        }



        $res = [
            'total_page'  => $total,
            'page'        => $page,
            'limit'       => $limit,
            'contributes' => EntityMapper::collection($contributes, ContributeSummaryWithUserEntity::class)->toArray()
        ];

        return $res;

    }
}
