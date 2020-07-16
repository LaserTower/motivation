<?php

namespace App\Denis;

use App\Denis\Commands\CreatePrototype;
use App\Denis\Commands\RoundRobin;
use App\Denis\Commands\ChatExec;

use App\Models\Conversation;
use App\Models\Prototype;
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
        $this->app->alias(Conversation::class, "denis.conversation");
        $this->app->alias(Prototype::class, "denis.prototype");
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
                RoundRobin::class,
                ChatExec::class,
            ]);
        }
    }
}
