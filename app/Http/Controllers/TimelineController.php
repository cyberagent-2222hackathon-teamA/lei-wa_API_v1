<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Timeline\PublicRequest;
use App\Http\Requests\Timeline\PrivateRequest;

use App\Services\TimelineService;


class TimelineController extends Controller
{

    private $timeline_service;

    public function __construct(TimelineService $timeline_service)
    {
        $this->timeline_service = $timeline_service;
    }

    public function public(PublicRequest $request)
    {
        $res = $this->timeline_service->getPublicTimeline((int)$request->input('page', 1), (int)$request->input('limit', 10));
        return $res;
    }

    public function private(PrivateRequest $request)
    {
        $res = $this->timeline_service->getPrivateTimeline(
            (int)$request->input('user_id'),
            (int)$request->input('page', 1),
            (int)$request->input('limit', 10)
        );
        return $res;
    }
}
