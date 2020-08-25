<?php

namespace App\Denis\Commands;


use App\Denis\Core;
use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;
use App\Vadim\Vadim;
use Illuminate\Console\Command;

class DeployTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeployTimers {schedule_id}';

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
        $schedule_id = $this->argument('schedule_id');
        (new Vadim())->deployTimers($schedule_id);
    }
}
