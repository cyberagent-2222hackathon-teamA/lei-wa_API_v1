<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SlackGetRealTimeDataJob;

class SlackGetRealtimeDataCachingCommand extends Command
{
    protected $signature = 'command:slack_realtime_data_caching';

    protected $description = 'slackから全ユーザー分の昨日のデータをqueneにpushする';

    /**
     * メイン処理
     *
     * @throws
     */
    public function handle()
    {
        // 全slack workspaceのidを取得
        $all_slack_workspace_info = \DB::table('slack_workspaces')
            ->select('id', 'token')
            ->get()
            ->toArray();

        foreach ($all_slack_workspace_info as $slack_workspace_info) {

            //特定workspaceの全チャンネル取得
            $slack_channel_ids = \DB::table('slack_workspace_users')
                ->where('slack_workspace_id', $slack_workspace_info->id)
                ->get()
                ->pluck('channel_id')
                ->toArray();

            foreach ($slack_channel_ids as $slack_channel_id){
                SlackGetRealTimeDataJob::dispatch($slack_workspace_info->token, $slack_channel_id);
            }

        }
    }
}
