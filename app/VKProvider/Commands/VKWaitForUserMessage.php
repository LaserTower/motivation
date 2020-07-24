<?php

namespace App\VKProvider\Commands;

use App\Denis\Core;
use App\VKProvider\CallbackApiMyHandler;
use Illuminate\Console\Command;
use VK\CallbackApi\LongPoll\VKCallbackApiLongPollExecutor;
use VK\Client\VKApiClient;

class VKWaitForUserMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:vk_waitForUserMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function handle()
    {
        $access_token = '3cfcd3da2e24844ec19519fa9ffda0f1151f184b981c5a208ea5fa7cc4ec50cb67233d7a542bc2058b168';
        $group_id = 167564984;
        $vk = new VKApiClient();
        $vk->groups()->setLongPollSettings($access_token, [
            'group_id'      => $group_id,
            'enabled' => 1,
            'message_new' => 1,
            'message_typing_state' => 1,
            'message_allow' => 0,
            'message_reply' => 0,
            'api_version'=>'5.101'
        ]);
        $timestamp = 12;
        $wait = 25;
        
        $executor = new VKCallbackApiLongPollExecutor($vk, $access_token, $group_id, new CallbackApiMyHandler(new Core()), $wait);
        while (true){
            $executor->listen();
        }
    }
}
