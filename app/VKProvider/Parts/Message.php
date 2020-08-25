<?php


namespace App\VKProvider\Parts;


use VK\Client\VKApiClient;

class Message
{
    protected $access_token;
    protected $group_id;
    
    public function __construct()
    {
        $this->access_token = env('VK_ACCESS_TOKEN');
        $this->group_id = env('VK_GROUP_ID');
    }
    
    public function transmit(\App\Denis\Parts\CorePart $message)
    {
        $VKApiClient = new VKApiClient();

        $VKApiClient->messages()->send(
            $this->access_token,
            [
                'random_id' => round((time()-1590914070)/4),
                'peer_id' => $message->user_id,
                'message' => $message->body
            ]);
    }
}