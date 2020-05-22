<?php


namespace App\VKProvider\Parts;


use VK\Client\VKApiClient;

class PickData
{
    protected $access_token;
    protected $group_id;

    public function __construct()
    {
        $this->access_token = '3cfcd3da2e24844ec19519fa9ffda0f1151f184b981c5a208ea5fa7cc4ec50cb67233d7a542bc2058b168';
        $this->group_id = 167564984;
    }
    
    public function transmit(\App\Denis\Parts\CorePart $message)
    {
        $VKApiClient = new VKApiClient();

        $VKApiClient->messages()->send(
            $this->access_token,
            [
                'random_id' => rand(100,200),
                'peer_id' => $message->user_id,
                'message' => $message->question
            ]);
    }
}