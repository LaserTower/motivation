<?php


namespace App\Denis;


use App\Denis\Models\Conversation;
use App\Denis\Models\Prototype;
use App\Denis\Parts\CorePart;
use App\Denis\Parts\EmptyPart;

class Core
{
    /** @var Conversation $conversation */
    protected $conversation;

    protected $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    public function receive(CorePart $message)
    {
        /** @var Conversation $conversation */
        $this->conversation = Conversation::firstOrCreate(
            [
                'user_id' => $message->user_id,
                'provider' => $this->provider->name
            ],
            [
                'prototype_id' => config('denis.default_prototype_id.' . $this->provider->name)
            ]);
        $this->conversation->saveEntity($message);
        $prototype = Prototype::find($this->conversation->prototype_id);

        $entity = $this->conversation->lastBotMessage();
        if (is_null($entity)) {
            $next = 1;
        } else {
            $next = $entity->id;
        }
        do {
            $part = $prototype->getPart($next);
            if ($part->type == 'goto') {
                //todo переход на другой бот  
            }
            $this->conversation->saveEntity($part);
            $next = $part->execute($this->provider, $message, $this->conversation);
            $message = new EmptyPart();

        } while (!is_null($next));
    }
}