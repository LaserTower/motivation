<?php

namespace App\Vadim\Commands;

use App\MQ\ReconnectRabbitMq;
use App\Vadim\Models\AlarmClockPool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class ClockTimer
 * @package App\Vadim\Commands
 *
 */
class ClockTimer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:ClockTimer';

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

        $channel->queue_declare('clock_exec', false, true, false, false);

        while (true) {
            //следующий межминутный интервал
            
            $pool = AlarmClockPool::where('in_progress', false)->whereRaw('extract(\'epoch\' from (transaction_timestamp() -  "clock_at"))::integer % 86400 BETWEEN 0 and 60')->get();
            if ($pool->count() == 0) {
                continue;
            }
            
            foreach ($pool as $timer) {
                $msg = new AMQPMessage(json_encode([
                    'alarm_clock_pool_id' => $timer->id
                ]));
                $channel->basic_publish($msg, '', 'clock_exec');
                $timer->in_progress = true;
                $timer->save();
            }
            sleep(60);
        }
        $channel->close();
        $connection->close();
    }
}
