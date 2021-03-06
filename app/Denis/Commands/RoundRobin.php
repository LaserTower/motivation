<?php

namespace App\Denis\Commands;

use App\MQ\ReconnectRabbitMq;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RoundRobin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:RoundRobin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    public function handle()
    {
        $connection = new ReconnectRabbitMq('bot_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('chat_exec', false, true, false, false);
        
        while (true){
            sleep(1);
            $batch = \DB::select("select conversation_id from message_pool where in_progress=false
group by conversation_id
HAVING max(created_at)<  transaction_timestamp()- (interval '2 second' +  (max(created_at)-min(created_at))/count(created_at))");
            if(count($batch)<1){
                continue;
            }
            foreach ($batch as $row){
                \DB::statement("update message_pool set in_progress=true where conversation_id=?",[$row->conversation_id]);
                $msg = new AMQPMessage(json_encode([
                    'conversation_id' => $row->conversation_id,
                ]));
                \Log::info('transmit',[
                    'conversation_id' => $row->conversation_id,
                ]);
                $channel->basic_publish($msg,'','chat_exec');
            }
        }
        $channel->close();
        $connection->close();
    }
}
