<?php

namespace App\Denis;

use App\Denis\Commands\CreatePrototype;
use App\Denis\Commands\DeployTimers;
use App\Denis\Commands\RoundRobin;
use App\Denis\Commands\ChatExec;

use App\Denis\Commands\StartUpConversation;
use App\Denis\Models\Conversation;
use App\Denis\Models\ConversationsScenario;
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
        $this->app->alias(ConversationsScenario::class, "denis.prototype");
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
                RoundRobin::class,
                ChatExec::class,
                StartUpConversation::class,
                DeployTimers::class,
            ]);
        }
    }
}
