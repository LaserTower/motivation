<?php

namespace App\Denis\Parts;

class Message extends CorePart
{
    use ApplyVariables;

    public $type = 'message-text';
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
        if (isset($conversation->part_external_data['is_send'])) {
            return $this->next;
        }

        $this->externalData['is_send'] = true;
        $this->user_id = $conversation->userId();
        $this->body = $this->formatVariables($this->body, $conversation->getVariables());
        $provider->transmit($this);

        return $this->next;
    }
}