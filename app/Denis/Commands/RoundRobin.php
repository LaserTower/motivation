<?php

namespace App\Denis\Commands;

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
        $connection = new AMQPStreamConnection('bot_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('user_ids', false, false, false, false);
        
        while (true){
            sleep(1);
            $batch = \DB::select("select user_id, provider from wait_pool where in_progress=false
group by user_id,provider
HAVING max(created_at)<  transaction_timestamp()- (interval '8 second' +   (max(created_at)-min(created_at))/count(created_at))");
            if(count($batch)<1){
                continue;
            }
            foreach ($batch as $row){
                $user_id = $row->user_id;
                $provider = $row->provider;
                \DB::statement("update wait_pool set in_progress=true where user_id=? and provider=?",[$user_id, $provider]);
                $msg = new AMQPMessage(json_encode([
                    'user_id' => $user_id,
                    'prov' => $provider
                ]));
                $channel->basic_publish($msg,'','user_ids');
            }
        }
        $channel->close();
        $connection->close();
    }
}
