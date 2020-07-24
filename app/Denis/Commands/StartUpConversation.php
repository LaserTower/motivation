<?php

namespace App\Denis\Commands;


use App\Denis\Core;
use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;
use Illuminate\Console\Command;

class StartUpConversation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'StartUpConversation {users_of_providers_id} {conversationPrototypeId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function handle()
    {
        $conversationPrototypeId = $this->argument('conversationPrototypeId');
        $uopId = $this->argument('users_of_providers_id');
        $userOfProvidersModel = UserOfProviders::find($uopId)->first();

        $userOfProvidersModel->locked_by_conversation_id = null;
        $conversation = Conversation::make(
            [
                'user_of_provider_id' => $userOfProvidersModel->id,
                'prototype_id' => $conversationPrototypeId
            ]);
        $denis = new Core();
        $denis->attachConversation($userOfProvidersModel, $conversation);
    }
}
