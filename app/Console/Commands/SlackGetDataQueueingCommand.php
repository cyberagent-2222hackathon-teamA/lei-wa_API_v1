<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SlackGetDataJob;
use App\Repositories\UserRepository;

class SlackGetDataQueueingCommand extends Command
{
    protected $signature = 'command:slack_get_data_queueing';

    protected $description = 'slackから全ユーザー分の昨日のデータをqueneにpushする';

    /**
     * メイン処理
     *
     * @throws
     */
    public function handle()
    {
        // 全userのidを取得
        $all_user_ids = \DB::table('users')
                             ->get()
                             ->pluck('id')
                             ->toArray();

        foreach ($all_user_ids as $user_id) {

            SlackGetDataJob::dispatch($user_id);

        }
    }
}
