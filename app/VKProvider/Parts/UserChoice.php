<?php


namespace App\VKProvider\Parts;


use VK\Client\VKApiClient;

class UserChoice
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
        $buttons = [];
        foreach ($message->variants as $key => $value) {
            $buttons[] = [[
                'action' =>
                    [
                        'type' => 'text',
                        'payload' => '{"' . $message->variable . '": "' . $key . '"}',
                        'label' => $value,
                    ],
                'color' => 'positive',
            ]];
        }
    
       
        $VKApiClient->messages()->send(
            $this->access_token,
            [
                'random_id' => rand(100,200),
                'peer_id' => $message->user_id,
                'message' => $message->question,
                'keyboard' =>json_encode([
                    'one_time' => true,
                    'buttons' => $buttons
                ])
            ]);
    }
}