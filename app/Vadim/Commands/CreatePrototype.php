<?php

namespace App\Vadim\Commands;

use App\Vadim\Constructor;
use Illuminate\Console\Command;

class CreatePrototype extends Command
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
          
        ];
        
        
        
       PrototypeModel::create(
            [
                'name' => 'Prototype',
                'published' => true,
                'payload' => [
                    'parts' => (new Constructor($payload))->makePrototype() 
                ]
            ]);
    }
}
