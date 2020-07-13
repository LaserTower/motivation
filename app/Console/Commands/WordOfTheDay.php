<?php

namespace App\Console\Commands;

use App\Denis\Commands\RoundRobin;
use Illuminate\Console\Command;

class WordOfTheDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rr = new RoundRobin();

        $tine=time();
        echo 'проверяем сообщение'.PHP_EOL;
        do{
            if(time()-$tine<15){
                echo 'создал сообщение'.PHP_EOL;
            \DB::table('wait_pool')->insert([
                'provider' => 'vk',
                'user_id' => 1,
                'message' => 88
            ]);}
            
            $res=$rr->get_batch();
            sleep(3);
            if(!is_null($res)){
                echo time()-$tine;
                print_r($res);
            }
        }while(time()-$tine<60);
        echo 'конец';
    }
}
