<?php

namespace App\Denis;

use App\Denis\Commands\CreatePrototype;
use App\Denis\Commands\waitForUserMessage;

use Illuminate\Support\ServiceProvider;

class DenisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        #$this->app->alias(\App\Denis\VKProvider\Parts\Parts::class, "connect.vk");
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'denis'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/migrations');
            $this->commands([
                CreatePrototype::class,
            ]);
        }
    }
}
