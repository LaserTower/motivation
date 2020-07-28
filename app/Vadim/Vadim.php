<?php


namespace App\Vadim;

use App\Denis\Core;
use App\Denis\Models\Conversation;
use App\Models\UserOfProviders;
use App\Vadim\Models\AlarmClockPool;
use App\Vadim\Models\ImproveProgramPrototype;
use App\Vadim\Models\AlarmClockSchedule;
use App\Vadim\Parts\TimerRelativeBase;
use Illuminate\Support\Facades\DB;

class Vadim
{
    protected $bindings = [
        'relative-base' => TimerRelativeBase::class
    ];

    public function attachAlarmsToUserProvider($users_of_providers_id, $alarm_clock_prototype_id)
    {
        DB::transaction(function () use ($users_of_providers_id, $alarm_clock_prototype_id) {
            //за пользователем теперь следит бот
            //привязываем прототип программы мотивации к игроку 
            $scheduler = AlarmClockSchedule::create([
                'users_of_providers_id' => $users_of_providers_id,
                'timezone' => "Asia/Yekaterinburg",
                'alarm_clock_prototype_id' => $alarm_clock_prototype_id,
                'clock_external_data' => ['mode' => 'setup']
            ]);

            //установить будильник на сейчас
            //когда освободится основной диалог должен начаться настроечный бот
            $usersOfProvidersModel = UserOfProviders::find($users_of_providers_id);
            AlarmClockPool::create([
                'clock_at' => \DB::raw('transaction_timestamp()'),
                'alarm_clock_schedule_id' => $scheduler->id,
                'timer_part_id' => 0,
                'users_of_providers_id' => $users_of_providers_id,
                'player_id' => $usersOfProvidersModel->player_id,
            ]);
        });
    }

    public function deployTimers($alarm_clock_schedule_id)
    {
        $scheduler = AlarmClockSchedule::find($alarm_clock_schedule_id);
        $prototype = ImproveProgramPrototype::find($scheduler->alarm_clock_prototype_id);
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

    public function clockExec($alarm_clock_pool_id)
    {
        $clockTimer = AlarmClockPool::find($alarm_clock_pool_id);
        $schedulerModel = AlarmClockSchedule::find($clockTimer->alarm_clock_schedule_id);

        if ($schedulerModel->clock_external_data['mode'] == 'setup') {
            if ($this->setupTimerConversation($schedulerModel)) {
                $temp = $schedulerModel->clock_external_data;
                $temp['mode'] = 'run';
                $schedulerModel->clock_external_data = $temp;
                $schedulerModel->save();
                $clockTimer->delete();
            }
        }
        
        if ($schedulerModel->clock_external_data['mode'] == 'run') {
            $clockPrototype = ImproveProgramPrototype::find($schedulerModel->alarm_clock_prototype_id);
            //как получить номер бота для создания 'prototype_id'
            $prototype_id = $clockPrototype->payload['timers'][$clockTimer->timer_part_id]['bot_id'];
            $userOfProvidersModel = UserOfProviders::find($schedulerModel->users_of_providers_id);
            $conversationModel = Conversation::create([
                'user_of_provider_id' => $schedulerModel->users_of_providers_id,
                'prototype_id' => $prototype_id,
                'next_part_id' => 1,
            ]);
            return (new Core())->attachConversation($userOfProvidersModel, $conversationModel);
        }
        return false;
    }

    public function setupTimerConversation($schedulerModel)
    {
        $clockPrototypeModel = ImproveProgramPrototype::find($schedulerModel->alarm_clock_prototype_id);

        $conversationModel = Conversation::create([
            'user_of_provider_id' => $schedulerModel->users_of_providers_id,
            'prototype_id' => $clockPrototypeModel->settings_bot_id,
            'next_part_id' => 1,
            'parent_schedule_id' => $schedulerModel->id
        ]);
        $conversationModel->refresh();
        $userOfProvidersModel = UserOfProviders::find($schedulerModel->users_of_providers_id);
        return (new Core())->attachConversation($userOfProvidersModel, $conversationModel);
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