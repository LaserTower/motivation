<?php


namespace App\Denis\Parts;


use App\Denis\Models\Conversation;
use App\Denis\Models\UserCard;

class Auth extends CorePart
{
    use PickDataTrait;
    public $type = 'denis-auth';
    public $next;
    public $body;

    public function __construct($id = null, $next = null, $body = null)
    {
        $this->id = $id;
        $this->body = $body;
        $this->next = $next;
    }

    public function constructor()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'body' => $this->body,
            'next' => $this->next
        ];
    }

    public function askQuestion($provider, $messages, $conversation)
    {
        $message = new Message(null,null,$this->body);
        $message->user_id = $messages[0]->user_id;
        $provider->transmit($message);
        return null;
    }

    public function checkAnswer($provider, $messages, $conversation)
    {
        //проверить email
        //привязать этот диалог к player_id
        $userCard = UserCard::firstOrCreate(
            ['email' => $messages[0]->body],
            ['email' => $messages[0]->body]
        );
        $conversation->playerConnect($userCard);
        return $this->next;
    }
    
    function execute($provider, $messages, $conversation)
    {
        return $this->pickData($provider, $messages, $conversation);
    }
}