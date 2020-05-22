<?php


namespace App\ConsoleProvider;


use App\VKProvider\Commands\VKWaitForUserMessage;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/migrations');
            $this->commands([
                ConsoleWaitForUserMessage::class
            ]);
        }
    }
}