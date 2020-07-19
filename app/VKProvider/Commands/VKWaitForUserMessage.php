<?php

namespace App\VKProvider\Commands;


use App\Denis\Core;
use App\VKProvider\VkProvider;
use Illuminate\Console\Command;


class VKWaitForUserMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumer:vk_waitForUserMessage';

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

    public function handle()
    {
        $core = new Core();
        $provider = new VkProvider();
        $generator = $provider->handle();
        foreach ($generator as $newEntity) {
            $core->saveMessage('vk',$newEntity);
        }
    }
}
