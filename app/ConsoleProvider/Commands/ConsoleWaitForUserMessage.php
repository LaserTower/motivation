<?php


namespace App\ConsoleProvider\Commands;


use App\ConsoleProvider\ConsoleProvider;
use App\Core;

use Illuminate\Console\Command;

class ConsoleWaitForUserMessage extends Command
{
    public function handle()
    {
        $provider = new ConsoleProvider();
        $core = new Core($provider);
        $generator = $provider->handle();
        foreach ($generator as $newEntity) {
            $core->receive($newEntity);
        }
    }
}