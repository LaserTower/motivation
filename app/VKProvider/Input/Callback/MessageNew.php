<?php

namespace App\VKProvider\Input\Callback;

use App\Denis\Parts\Message;

class MessageNew
{
    public $date;
    public $from_id;
    public $id;
    public $out;
    public $peer_id;
    public $text;
    public $conversation_message_id;
    public $fwd_messages;
    public $important;
    public $random_id;
    public $attachments;
    public $payload;
    public $is_hidden;
    
    public function __construct($object)
    {
        foreach ($object as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function createEntity(): Message
    {
        $message = new Message(0, 0, $this->text);
        $message->from = 'user';
        $message->date = $this->date;
        $message->user_id = $this->from_id;
        $message->externalData=json_decode($this->payload,1);
        return $message;
    }
}