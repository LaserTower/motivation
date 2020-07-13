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
            usleep(1000*500);
            $batch = $this->get_batch();
            if(is_null($batch)){
                continue;
            }
            [$user_id, $provider] = $batch;
            $msg = new AMQPMessage(json_encode([
                'user_id' => $user_id,
                'prov' => $provider
            ]));
            
            $channel->basic_publish($msg,'','user_ids');
        }
        
        $channel->close();
        $connection->close();
    }
    
    public function get_batch()
    {
        //1 000 000 это одна секунда
        $row =  \DB::selectOne("select old.user_id,old.provider
from wait_pool as old
         left join wait_pool next on (old.provider = next.provider and old.user_id = next.user_id)
where old.in_progress=false and (
      (next.created_at - old.created_at) < interval '3 second'
   or (old.created_at + interval '3 second') > transaction_timestamp()) limit 1");
        if(is_null($row)){
            return null;
        }
        \DB::statement("update wait_pool set in_progress=true where user_id=? and provider=?",[$row->user_id,$row->provider]);
        return [$row->user_id, $row->provider];
    }
}
