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

    public function receive($message)
    {
        /** @var Conversation $conversation */
        $this->conversation = Conversation::firstOrCreate(
            [
                'provider_user_id' => $message[0]->user_id,
                'provider' => $this->provider->name
            ],
            [
                'prototype_id' => config('denis.default_prototype_id.' . $this->provider->name)
            ]);
        $prototype = Prototype::find($this->conversation->prototype_id);

        $next = $this->conversation->next_part_id ?? 1;
        
        do {
            $part = $prototype->getPart($next);
            if ($part->type == 'goto') {
                //todo переход на другой бот  
            }
            $next = $part->execute($this->provider, $message, $this->conversation);
            $this->conversation->saveEntity($part,$next);
            $message = [new EmptyPart()];

        } while (!is_null($next));
    }
}