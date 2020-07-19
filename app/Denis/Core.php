<?php


namespace App\Denis;


use App\Denis\Models\Conversation;
use App\Denis\Models\Prototype;
use App\Denis\Parts\CorePart;
use App\Denis\Parts\EmptyPart;
use App\Models\UserOfProviders;

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
            $this->saveEntity($conversation, $part, $next);
            $message = [new EmptyPart()];

        } while (!is_null($next));
    }

    public function saveEntity($conversation, CorePart $part, $next)
    {
        if (!is_null($next)) {
            $conversation->next_part_id = $next;
        }
        $conversation->part_external_data = $part->externalData;
        $conversation->save();
    }

    public function saveMessage($provider_name, CorePart $newEntity)
    {
        $userOfProvidersModel = UserOfProviders::firstOrCreate(
            [
                'provider_user_id' => $newEntity->user_id,
                'provider' => $provider_name
            ]);

        if (is_null($userOfProvidersModel->locked_by_conversation_id)) {
            $conversation = Conversation::make(
                [
                    'user_of_provider_id' => $userOfProvidersModel->id,
                    'prototype_id' => config('denis.default_prototype_id.' . $provider_name)
                ]);
            $this->attachConversation($userOfProvidersModel, $conversation);
        }

        \DB::table('message_pool')->insert([
            'conversation_id' => $userOfProvidersModel->locked_by_conversation_id,
            'message' => serialize($newEntity)
        ]);
    }

    public function attachConversation($userOfProvidersModel, $conversation)
    {
        if (is_null($userOfProvidersModel->locked_by_conversation_id)) {
            $conversation->save();
            $conversation->refresh();
            //это самое первое сообщение
            $userOfProvidersModel->locked_by_conversation_id = $conversation->id;
            $userOfProvidersModel->save();
        }


        //если в очереди есть сообщения для того бота что уже выполняется то придётся подождать

        //если не вышел таймаут прошлого бота тоже подожди
    }
}