<?php

namespace App\Denis\Commands;

use App\Denis\Core;
use App\Denis\Models\Conversation;
use App\Denis\Models\MessagePool;
use App\Denis\Parts\Typing;
use App\Models\UserOfProviders;
use App\VKProvider\VkProvider;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ChatExec extends Command
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



    /**
     * принимает номер пользователя для которого нужно выбрать сообщения и выполнить
     *
     * @return mixed
     */
    
    public function handle()
    {
        $connection = new AMQPStreamConnection('bot_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('chat_exec', false, true, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        
        $channel->basic_consume('chat_exec', 'chat_exec', false, false, false, false, [$this,'consume']);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
        return;
    }
    
    public function consume($msg)
    {
        $data = json_decode($msg->body,1);
        $rows =\DB::select('SELECT id,message FROM message_pool WHERE conversation_id=? ', [$data['conversation_id']]);
        $conversation = Conversation::find($data['conversation_id']);
        $userOfProvider = UserOfProviders::find($conversation->user_of_provider_id);
        $messages = [];
        $ids=[];
        foreach ($rows as $row) {
            $ids[]=$row->id;
            $temp = unserialize($row->message);
            if ($temp instanceof Typing) {
                continue;
            }
            $messages[] = $temp;
        }
        
        $providers=[
            'vk'=>new VkProvider()
        ];
        
        $core = new Core();
        
        if(count($messages)>0){
            $core->receive($providers[$userOfProvider->provider],$conversation ,$messages);
            MessagePool::whereIn('id',$ids)->delete();
        }
        return $msg->get('channel')->basic_ack($msg->get('delivery_tag'));
    }
}
