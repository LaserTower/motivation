<?php


namespace App\VKProvider;


use App\VKProvider\Commands\VKWaitForUserMessage;
use Illuminate\Support\ServiceProvider;

class VKServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/migrations');
            $this->commands([
                VKWaitForUserMessage::class
            ]);
        }
    }
}