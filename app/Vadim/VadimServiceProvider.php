<?php

namespace App\Vadim;

use App\Vadim\Commands\CreatePrototype;
use App\Vadim\Commands\ClockTimer;
use App\Vadim\Commands\ClockExec;
use App\Models\Conversation;
use App\Models\Prototype;
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
        $this->app->alias(Conversation::class, "vadim.conversation");
        $this->app->alias(Prototype::class, "vadim.prototype");
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
                ClockTimer::class,
                ClockExec::class,
            ]);
        }
    }
}
