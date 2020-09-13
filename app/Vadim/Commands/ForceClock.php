<?php

namespace App\Vadim\Commands;


use App\Vadim\Models\ProgramScenario;

use App\Vadim\Parts\TimerRelativeBase;
use App\Vadim\Vadim;
use Illuminate\Console\Command;

class ForceClock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ForceClock {alarm_clock_pool_id}';

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
        $uopId = $this->argument('alarm_clock_pool_id');
        (new Vadim())->clockExec($uopId);
    }
}
