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
    protected $signature = 'waitForUserMessage';

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
        $provider = new VkProvider();
        $core = new Core($provider);
        $generator = $provider->handle();
        foreach ($generator as $newEntity) {
            $core->receive($newEntity);
        }
    }
}
