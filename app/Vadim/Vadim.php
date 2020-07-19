<?php


namespace App\Vadim;


use App\Vadim\Models\AlarmClockPool;
use App\Vadim\Models\AlarmClockPrototype;
use App\Vadim\Models\AlarmClockSchedule;
use App\Vadim\Parts\TimerRelativeBase;
use Illuminate\Support\Facades\DB;

class Vadim
{
    protected $bindings = [
        'relative-base' => TimerRelativeBase::class
    ];

    public function attachAlarmsToUserProvider($users_of_providers_id, $prototype_id)
    {
        DB::transaction(function () use ($users_of_providers_id, $prototype_id) {
            //за пользователем теперь следит бот
            $scheduler = AlarmClockSchedule::create([
                'users_of_providers_id' => $users_of_providers_id,
                'timezone' => "Asia/Yekaterinburg",
                'alarm_clock_prototype_id' => $prototype_id,
            ]);
            //установить будильник на сейчас
            //когда освободится основной диалог должен начаться настроечный бот
            AlarmClockPool::create([
                'clock_at' => \DB::raw('transaction_timestamp()'),
                'alarm_clock_schedule_id' => $scheduler->id,
                'timer_part_id' => 0,
            ]);
        });
    }

    public function deployTimers($alarm_clock_schedule_id)
    {
        $scheduler = AlarmClockSchedule::find($alarm_clock_schedule_id);
        $prototype = AlarmClockPrototype::find($scheduler->alarm_clock_prototype_id);
        $timers = [];
        foreach ($prototype->payload['timers'] as $raw) {
            $timers[] = $this->gyrate($raw);
        }

        DB::transaction(function () use ($scheduler, $timers, $prototype) {
            foreach ($timers as $timer) {
                AlarmClockPool::create([
                    'clock_at' => $timer->getTimer($scheduler),
                    'alarm_clock_schedule_id' => $scheduler->id,
                    'timer_part_id' => $timer->id,
                ]);
            }
        });
    }

    protected function gyrate($el)
    {
        $obj = new $this->bindings[$el->type]();

        foreach ($el as $field => $value) {
            $obj->$field = $value;
        }
        return $obj;
    }
}