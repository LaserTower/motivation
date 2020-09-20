<?php

namespace App\Vadim\Commands;

use App\Core;
use App\Denis\Parts\Typing;
use App\MQ\ReconnectRabbitMq;
use App\Vadim\Models\AlarmClockPool;
use App\Vadim\Models\PlayersProgram;
use App\Vadim\Vadim;
use App\VKProvider\VkProvider;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ClockExec extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:clockExec';

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
        $connection = new ReconnectRabbitMq('bot_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('clock_exec', false, true, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        
        $channel->basic_consume('clock_exec', 'clock_exec', false, false, false, false, [$this,'consume']);

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
         (new Vadim())->clockExec($data['alarm_clock_pool_id']);
        $msg->get('channel')->basic_ack($msg->get('delivery_tag'));
    }
}
