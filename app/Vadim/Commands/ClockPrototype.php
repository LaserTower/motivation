<?php

namespace App\Vadim\Commands;


use App\Vadim\Models\AlarmClockPrototype;

use App\Vadim\Parts\TimerRelativeBase;
use Illuminate\Console\Command;

class ClockPrototype extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ClockPrototype';

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
        $payload = [
          new TimerRelativeBase(3,'go_to_sleep','-2 hour'),
          new TimerRelativeBase(4,'go_to_sleep','-1 hour'),
          new TimerRelativeBase(5,'go_to_sleep','-30 min'),
        ];
        
        AlarmClockPrototype::create(
            [
                'name' => 'Prototype',
                'settings_bot_id' => 5,
                'payload' => [
                    'timers' => json_encode($payload)
                ]
            ]);
    }
}
