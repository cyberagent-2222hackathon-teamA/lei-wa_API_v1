<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Timeline\PublicRequest;

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
        $res = $this->timeline_service->getPublicTimeline($request->input('page', 1), $request->input('limit', 10));
        return $res;
    }
}
