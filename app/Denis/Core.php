<?php


namespace App\Denis;


use App\Denis\Models\Conversation;
use App\Denis\Models\Prototype;
use App\Denis\Parts\CorePart;
use App\Denis\Parts\EmptyPart;
use App\Models\UserOfProviders;

class Core
{
    public function receive($provider, $message)
    {
        $conversation = Conversation::find($message->conversation_id);
        $prototype = Prototype::find($conversation->prototype_id);

        $next = $conversation->next_part_id ?? 1;

        do {
            $part = $prototype->getPart($next);
            if ($part->type == 'goto') {
                //todo переход на другой бот  
            }
            $next = $part->execute($provider, $message, $conversation);
            $this->saveEntity($conversation, $part, $next);
            $message = [new EmptyPart()];

        } while (!is_null($next));
        //как определить конец диалога - next=null и удовлетворённый part
        if (is_null($next) && $part->done) {
            $this->endOfConversation($conversation);
        }
    }

    public function saveEntity($conversation, CorePart $part, $next)
    {
        if (!is_null($next)) {
            $conversation->next_part_id = $next;
        }
        $conversation->part_external_data[$part->id] = $part->externalData;
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

    public function attachConversation($userOfProvidersModel, $conversationModel)
    {
        if (is_null($userOfProvidersModel->locked_by_conversation_id)) {
            $conversationModel->save();
            $conversationModel->refresh();
            //это самое первое сообщение
            $userOfProvidersModel->locked_by_conversation_id = $conversationModel->id;
            $userOfProvidersModel->save();
            return true;
        } else {
            return false;
        }


        //если в очереди есть сообщения для того бота что уже выполняется то придётся подождать

        //если не вышел таймаут прошлого бота тоже подожди
    }
    
    protected function endOfConversation($conversation)
    {
        UserOfProviders::where('locked_by_conversation_id', $conversation->id)->update([
            'locked_by_conversation_id' => null
        ]);
    }
}