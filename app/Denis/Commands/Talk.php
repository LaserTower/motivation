<?php

namespace App\Denis\Commands;

use App\Denis\Core;
use App\VKProvider\VkProvider;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Talk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:bot_step';

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
        $channel->queue_declare('user_ids', false, false, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        
        $channel->basic_consume('user_ids', 'user_ids', false, false, false, false, [$this,'consume']);

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
        $rows =\DB::select('SELECT provider, message FROM wait_pool WHERE user_id=? and provider=?', [$data['user_id'], $data['prov']]);

        $messages = [];
        foreach ($rows as $row){
            $messages[] = unserialize($row->message);
        }
        
        $provider = new VkProvider();
        $core = new Core($provider);
        
        if(count($messages)>0){
            $core->receive($messages);
            \DB::statement("delete from  wait_pool where in_progress=true and  user_id=? and provider=?",[$messages[0]->user_id, $provider->name]);
        }
        return $msg->get('channel')->basic_ack($msg->get('delivery_tag'));
    }
}
