<?php

namespace App\VKProvider\Input\Callback;


class Typing
{
    public $state;
    public $from_id;
    public $to_id;

    public function __construct($object)
    {
        foreach ($object as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function createEntity()
    {
        if($this->from_id<0){
            //сообщение от группы
            return null;
        }
        $message = new \App\Denis\Parts\Typing();
        $message->from = 'user';
        $message->user_id = $this->from_id;
        return $message;
    }
}