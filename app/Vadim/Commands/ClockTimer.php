<?php

namespace App\Vadim\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
        $connection = new AMQPStreamConnection('bot_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('clock_exec', false, false, false, false);
        
        while (true){
            sleep(60);
            //следующий межминутный интервал
            
            $batch = \DB::select("select id,player_id from clock_pool  where in_progress=false and clock_at < transaction_timestamp()");
            if(count($batch)<1){
                continue;
            }
            $players = [];
           
            
            foreach ($batch as $playerid){
               
                \DB::statement("update clock_pool set in_progress=true where id in ?",[$ids]);
                $msg = new AMQPMessage(json_encode([
                    'player' => $playerid,
                    'ids' => $ids
                ]));
                $channel->basic_publish($msg,'','clock_exec');
            }
        }
        $channel->close();
        $connection->close();
    }
}
