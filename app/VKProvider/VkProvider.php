<?php


namespace App\VKProvider;

use App\Denis\Parts\CorePart;
use App\VKProvider\Parts\Message;
use App\VKProvider\Parts\UserChoice;
use App\VKProvider\Parts\PickData;
use VK\Client\VKApiClient;

class VkProvider
{
    const BINDINGS = [
        'user-choice' => UserChoice::class,
        'pick-data' => PickData::class,
        'message-text' => Message::class
    ];
    protected $access_token;
    protected $group_id;
    public $name = 'vk';

    public function __construct()
    {
        $this->access_token = '3cfcd3da2e24844ec19519fa9ffda0f1151f184b981c5a208ea5fa7cc4ec50cb67233d7a542bc2058b168';
        $this->group_id = 167564984;
    }
    
    public function handle()
    {
        $last_ts = null;
        $vk = new VKApiClient();
        $wait = 25;
        $vk->groups()->setLongPollSettings($this->access_token, array(
            'group_id' => $this->group_id,
            'enabled' => 1,
            'message_new' => 1,
            'message_typing_state' => 0,
            'message_reply' => 0,
            'api_version'=>'5.101'
        ));
        $executor = new VKCallbackApiLongPollExecutorEdit($vk, $this->access_token, $this->group_id, new CallbackApiMyHandler(), $wait);
        while (true) {
            yield from $executor->listen($last_ts);
        }
    }

    public function transmit(CorePart $part)
    {
        $class = self::BINDINGS[$part->type];
        $helper = new $class();
        $helper->transmit($part);
    }
}