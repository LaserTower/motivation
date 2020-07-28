<?php


namespace App\Console;


use App\MQ\ReconnectRabbitMq;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class SimpleConsumer extends Command
{
    public $routeKey = '';
    public function handle()
    {
        $connection = new ReconnectRabbitMq('bot_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare($this->routeKey, false, true, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $channel->basic_consume($this->routeKey, $this->routeKey, false, false, false, false, [$this,'consume']);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
        return;
    }
}