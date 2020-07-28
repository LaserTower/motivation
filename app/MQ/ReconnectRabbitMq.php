<?php


namespace App\MQ;


use PhpAmqpLib\Connection\AMQPStreamConnection;

class ReconnectRabbitMq extends AMQPStreamConnection
{
    protected function connect()
    {
        $start = time();
        while (!$this->isConnected() && (time()-$start)<15) {
            try {
                parent::connect();
                if($this->isConnected()){
                    return;
                }
            } catch (\PhpAmqpLib\Exception\AMQPIOException $exception) {
                sleep(1);
            }
        }
        throw new \Exception('подключиться не удалось');
    }
}