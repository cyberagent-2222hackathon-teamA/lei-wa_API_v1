<?php

namespace App\Console\Commands;

use App\Repositories\CacheRepository;
use App\Services\SlackService;
use Illuminate\Console\Command;

class SlackUserDataCachingCommand extends Command
{
    protected $signature = 'command:user_data_caching';

    protected $description = 'slackのchannel user dataをcachingする';

    /**
     * メイン処理
     *
     * @throws
     */
    public function handle(SlackService $slack_service, CacheRepository $cache_repository)
    {

        //slack userデータを取得（今回は全員同じチャンネルという前提）
        $users_data = $slack_service->getSameWorkSpaceUsers(1);

        //caching
        $cache_repository->setCacheData("CU2T0UT9N"."_users", $users_data, 60 * 24);

    }
}
