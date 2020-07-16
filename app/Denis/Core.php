<?php


namespace App;


use App\Denis\Models\Conversation;
use App\Denis\Models\Prototype;
use App\Denis\Parts\CorePart;
use App\Denis\Parts\EmptyPart;

class Core
{
    protected $provider;

    public function __construct($provider = null)
    {
        $this->provider = $provider;
    }

    public function receive($message)
    {
        $conversation = Conversation::find($message->conversation_id);
        $prototype = Prototype::find($conversation->prototype_id);

        $next = $conversation->next_part_id ?? 1;
        
        do {
            $part = $prototype->getPart($next);
            if ($part->type == 'goto') {
                //todo переход на другой бот  
            }
            $next = $part->execute($this->provider, $message, $conversation);
            $this->saveEntity($conversation,$part,$next);
            $message = [new EmptyPart()];

        } while (!is_null($next));
    }

    public function saveEntity($conversation,CorePart $part, $next)
    {
        if(!is_null($next)){
            $conversation->next_part_id = $next;
        }
        $conversation->part_external_data = $part->externalData;
        $conversation->save();
    }

    public function saveMessage(CorePart $newEntity)
    {
        $conversation = Conversation::firstOrCreate(
            [
                'provider_user_id' => $newEntity->user_id,
                'provider' => $this->provider->name
            ],
            [
                'prototype_id' => config('denis.default_prototype_id.' . $this->provider->name)
            ]);
        
        \DB::table('message_pool')->insert([
            'conversation_id' => $conversation->id,
            'message' => serialize($newEntity)
        ]);
    }
}