<?php

namespace App\Denis\Commands;

use App\Core;
use App\Denis\Parts\Typing;
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
        $channel->queue_declare('chat_exec', false, false, false, false);
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
        $rows =\DB::select('SELECT provider, message FROM message_pool WHERE user_id=? and provider=?', [$data['user_id'], $data['prov']]);

        $messages = [];
        foreach ($rows as $row){
            $temp = unserialize($row->message);
            if($temp instanceof Typing){
                continue;
            }
            $messages[] = $temp;
        }
        
        $provider = new VkProvider();
        $core = new Core('denis', $provider);
        
        if(count($messages)>0){
            $core->receive($messages);
            \DB::statement("delete from message_pool where in_progress=true and  user_id=? and provider=?",[$messages[0]->user_id, $provider->name]);
        }
        return $msg->get('channel')->basic_ack($msg->get('delivery_tag'));
    }
}
