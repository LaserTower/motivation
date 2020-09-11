<?php

namespace App\Denis\Commands;

use App\Console\SimpleConsumer;
use App\Denis\Core;
use App\Denis\Models\Conversation;
use App\Denis\Models\MessagePool;
use App\Denis\Parts\Typing;
use App\Models\UserOfProviders;
use App\VKProvider\VkProvider;


class ChatExec extends SimpleConsumer
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:botExec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $routeKey = 'chat_exec';

    /**
     * принимает номер пользователя для которого нужно выбрать сообщения и выполнить
     *
     * @return mixed
     */

    public function consume($msg)
    {
        $data = json_decode($msg->body, 1);
        $rows = MessagePool::where('conversation_id', $data['conversation_id'])->get();
       
        $conversation = Conversation::find($data['conversation_id']);
        if (is_null($conversation)) {
            return $msg->get('channel')->basic_ack($msg->get('delivery_tag'));
        }
        $userOfProvider = UserOfProviders::find($conversation->user_of_provider_id);
        $messages = [];
        $ids = [];
        foreach ($rows as $row) {
            $ids[] = $row->id;
            $temp = unserialize($row->message);
            if ($temp instanceof Typing) {
                continue;
            }
            $messages[] = $temp;
        }

        $providers = [
            'vk' => new VkProvider()
        ];

        $core = new Core();

        if (count($messages) > 0) {
            $core->receive($providers[$userOfProvider->provider], $conversation, $messages);
            MessagePool::whereIn('id', $ids)->delete();
        }
        return $msg->get('channel')->basic_ack($msg->get('delivery_tag'));
    }
}
