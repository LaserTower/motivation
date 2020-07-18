<?php


namespace App\Denis\Parts;

use App\Models\PlayerCard;

class MessageOnce extends CorePart
{
    use ApplyVariables;
    public $type = 'message-once';
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

    public function execute($provider, $message, $conversation)
    {        
        $this->body = $this->formatVariables($this->body, $conversation->getVariables());
        $message = new Message($this->id,$this->next,$this->body);
        $message->user_id = $conversation->provider_user_id;
        $provider->transmit($message);
        return $this->next;
    }
}