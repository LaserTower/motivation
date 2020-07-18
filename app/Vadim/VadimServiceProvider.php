<?php

namespace App\Vadim;

use App\Vadim\Commands\ClockPrototype;
use App\Vadim\Commands\ClockTimer;
use App\Vadim\Commands\ClockExec;

use Illuminate\Support\ServiceProvider;

class VadimServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
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
                ClockPrototype::class,
                ClockTimer::class,
                ClockExec::class,
            ]);
        }
    }
}
