<?php

namespace App\Vadim\Commands;


use App\Vadim\Models\AlarmClockPrototype;

use App\Vadim\Parts\TimerRelativBase;
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
          new TimerRelativBase(3,1,'HH:II:SS','-2 hour'),
          new TimerRelativBase(4,1,'HH:II:SS','-1 hour'),
          new TimerRelativBase(5,1,'HH:II:SS','-30 min'),
        ];
 
        
        AlarmClockPrototype::create(
            [
                'name' => 'Prototype',
                'published' => true,
                'payload' => [
                    'timers' => json_encode($payload)
                ]
            ]);
    }
}
