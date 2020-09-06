<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;


class Auth extends PickData
{
    public $type = 'denis-auth';
    public $next;
    public $body;

    public static $fields = [
        'body',
        'next',
    ];

    public function checkAnswer($provider, $messages, $conversation)
    {
        //проверить email
        //привязать этот диалог к player_id
        $userCard = UserOfProviders::firstOrCreate(
            ['email' => $messages[0]->body],
            ['email' => $messages[0]->body]
        );
        $conversation->playerConnect($userCard);
      
        return $this->next;
    }
}