<?php

namespace App\Denis\Parts;

class Message extends CorePart
{
    use ApplyVariables;

    public $type = 'message-text';
    public $next;
    public $body;

    public static $fields = [
        'body',
        'next',
    ];
    
    public function execute($provider, $message, $conversation)
    {
        $this->user_id = $conversation->userId();
        $this->body = $this->formatVariables($this->body, $conversation->getVariables());
        $provider->transmit($this);

        return $this->next;
    }
}