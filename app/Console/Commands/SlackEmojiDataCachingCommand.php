<?php

namespace App\Console\Commands;

use App\Repositories\CacheRepository;
use Illuminate\Console\Command;

class SlackEmojiDataCachingCommand extends Command
{
    protected $signature = 'command:emoji_data_caching';

    protected $description = 'slackから絵文字データをqueneにpushする';

    /**
     * メイン処理
     *
     * @throws
     */
    public function handle(CacheRepository $cacheRepository)
    {
        //最初のslack_workspaceのtokenを使う
        //本来なら公用のtokenをもっとくべきだけど致し方なし
        $slack_token= \DB::table('slack_workspaces')
            ->first()
            ->token;

        $api_res = (new \GuzzleHttp\Client())->get(config('slack.api_url').'/emoji.list', [
            'query' => [
                'token' => $slack_token,
            ]
        ]);

        $emojis = json_decode($api_res->getBody()->getContents())->emoji;

        var_dump($emojis->bowtie);

        //caching
        $cacheRepository->setCacheData('emoji', $emojis, 60 * 24);





    }
}
