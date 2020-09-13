<?php

namespace App\Vadim\Commands;


use App\Vadim\Models\ProgramScenario;

use App\Vadim\Parts\TimerRelativeBase;
use App\Vadim\Vadim;
use Illuminate\Console\Command;

class AttachProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AttachProgram';

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
        $users_of_providers_id = 8;
        $alarm_clock_prototype_id = 1;
        (new Vadim())->attachAlarmsToUserProvider($users_of_providers_id, $alarm_clock_prototype_id);
    }
}
