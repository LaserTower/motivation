<?php


namespace App\Denis;


use App\Denis\Models\Conversation;
use App\Denis\Models\MessagePool;
use App\Denis\Models\ConversationsScenario;
use App\Denis\Parts\CorePart;
use App\Denis\Parts\EmptyPart;
use App\Models\UserOfProviders;
use Carbon\Carbon;

class Core
{
    public function receive($provider, $conversation, $message)
    {
        $scenario = ConversationsScenario::find($conversation->scenario_id);

        $next = $conversation->next_part_id ?? 1;

        do {
            $part = $scenario->getPart($next);
            if ($part->type == 'goto') {
                //todo переход на другой бот  
            }
            $next = $part->execute($provider, $message, $conversation);
            $this->saveEntity($conversation, $part, $next);
            $message = $this->createEmptyPart($conversation);

        } while (!is_null($next));
        //как определить конец диалога - next=null и удовлетворённый part
        if ($part->done) {
            $this->endOfConversation($provider, $conversation);
        }
    }

    public function saveEntity($conversation, CorePart $part, $next)
    {
        if (!is_null($next)) {
            $conversation->next_part_id = $next;
        }
        $conversation->part_external_data = $part->externalData;
        $conversation->save();
    }

    public function createEmptyPart($conversation)
    {
        $userOfProvider = UserOfProviders::find($conversation->user_of_provider_id);
        $ep = new EmptyPart();
        $ep->user_id = $userOfProvider->provider_user_id;
        $ep->user_of_provider_id = $userOfProvider->id;
        return [$ep];
    }

    public function providerMessage($provider_name, CorePart $newEntity)
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
                    'scenario_id' => config("denis.$provider_name.doorman_scenario_id")
                ]);
            $this->attachConversation($userOfProvidersModel, $conversation);
        }

        $this->saveMessage($userOfProvidersModel, $newEntity);
    }

    protected function saveMessage($userOfProvidersModel, $newEntity)
    {
        \DB::table('message_pool')->insert([
            'conversation_id' => $userOfProvidersModel->locked_by_conversation_id,
            'message' => serialize($newEntity)
        ]);
    }

    public function attachConversation($userOfProvidersModel, $conversationModel)
    {
        if ($this->conversationIsLock($userOfProvidersModel, $conversationModel)) {
            return false;
        }

        $conversationModel->save();
        $conversationModel->refresh();
        //это самое первое сообщение
        $userOfProvidersModel->locked_by_conversation_id = $conversationModel->id;
        $userOfProvidersModel->next_part_id = 1;
        $userOfProvidersModel->save();
        $newEntity = new EmptyPart();
        $newEntity->user_id = $userOfProvidersModel->provider_user_id;
        $newEntity->provider = $userOfProvidersModel->provider;
        $this->saveMessage($userOfProvidersModel, $newEntity);
        return true;
    }

    protected function conversationIsLock($userOfProvidersModel, $conversationModel)
    {
        if (is_null($userOfProvidersModel->locked_by_conversation_id)) {
            return false;
        }
        //если не вышел таймаут прошлого бота подожди
        if ($conversationModel->updated_at > Carbon::now()->subMinutes(10)) {
            return false;
        }
     
        //если в очереди есть сообщения для того бота что уже выполняется то придётся подождать
        if (MessagePool::where('conversation_id', $userOfProvidersModel->locked_by_conversation_id)->where('in_progress', false)->count() > 0) {
            return true;
        }
        return false;
    }

    protected function endOfConversation($provider, $conversation)
    {
        //не удалять дефолтный диалог
        if ($conversation->scenario_id == config("denis.{$provider->name}.doorman_scenario_id")) {
            return;
        }
        $conversation->delete();

        //по окончании мини бота должен переходить на сценарий поддержки

        $portier = Conversation::where([
            ['scenario_id', config("denis.{$provider->name}.lifeline_scenario_id") ],
            ['user_of_provider_id', $conversation->user_of_provider_id]
        ])->first();
        $userOfProvidersModel = UserOfProviders::find($portier->user_of_provider_id);
        $this->attachConversation($userOfProvidersModel, $portier);
    }
}