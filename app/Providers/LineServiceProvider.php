<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

use App\Repositories\LineMessageLogRepository;

class LineServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('linebot', function () {
            $channelAccessToken = env('LINEBOT_TOKEN', null);
            $channelUserId = env('LINE_USER_ID', null);
            $channelSecret = env('LINEBOT_SECRET', null);
            return new LINEBot(new CurlHTTPClient($channelAccessToken), [
                'channelSecret' => $channelSecret,
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
